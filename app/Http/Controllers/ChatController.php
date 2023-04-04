<?php

namespace App\Http\Controllers;

use App\Drivers\SupportChat\SupportChatInterface;
use App\Drivers\SupportChat\TelegramChat;
use App\Constants;
use App\Events\DialogComplete;
use App\Events\ErrorOccurred;
use App\Events\MessageReceived;
use App\Events\NewDialogAccepted;
use App\Events\NewDialogRejected;
use App\Events\NewDialogStarted;
use App\Http\Controllers\Auth\UserController;
use App\Models\Auth\User;
use App\Models\Chat;
use App\Models\Message;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Storages\Drivers\RedisStorage;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Pusher\Pusher;
use Pusher\PusherException;

/**
 * Контроллер для реализации работы чата
 */
class ChatController extends Controller
{

    public SupportChatInterface $supportChat;

    public function __construct()
    {
    }

    /**
     * @param int $chatSource
     * @return void
     */
    public function driverInit(int $chatSource)
    {
        switch ($chatSource) {
            case \config('constants.chatSources.telegram'):
            {
                $this->supportChat = new TelegramChat();
                break;
            }
        }
    }

    /**
     * Завершить диалог
     * @param int $id ID чата
     * @return mixed Результат
     */
    public function completeDialog(Chat $chat): mixed
    {
        $this->driverInit($chat->source);

        if ($chat->active)
            $chat->active = false;
        try {
            //Были изменения - сохраняем
            if ($chat->isDirty()) {
                $chat->save();
            }
        } catch (\Exception $exception) {
            ErrorOccurred::dispatch('Сохранение чата при завершении', $exception, true);
            return ['result' => -1, 'text' => 'Исключение при сохранении чата: ' . $exception->getMessage()];
        }

        Redis::command('del', ["ChatsData:{$chat->id}:RefusedOperators"]);
        Redis::set("ClientsData:{$chat->source}:{$chat->client_id}:condition", Constants\ClientConditions::EXPECTATION);    //Клиент снова в режиме ожидания
//        $this->supportChat->setUserCondition($chat->client_id, Constants\ClientConditions::EXPECTATION);

//        $this->supportChat->sendText($chat, "Диалог с оператором завершён");
        //Отправляем стартовую клавиатуру
        $this->supportChat->sendInlineKeyboard($chat->client_id, "Диалог с оператором завершён", [['Меню' => '/start'],
            ['Связаться с оператором' => '/startDialogRequest']]);
//        $question->addButton(Button::create('Начать')->value('/start'));
//        $question->addButtons([
//            Button::create('Контакт')->value('requestContact'),
//            Button::create('Связаться с оператором')->value('/startDialogRequest'),
//            Button::create('Инкремент')->value('/getIncrement')

        DialogComplete::dispatch($chat); //Вызываем событие
        return null;
    }

    /**
     * Запросить начало диалога
     * @return void
     * @throws PusherException|GuzzleException
     */
    public function startDialogRequest($clientId, int $source)
    {
        $this->driverInit($source);

        if (Redis::get("ClientsData:{$source}:{$clientId}:condition") != Constants\ClientConditions::DIALOGUE) {
            try {
                //Отправляем клавиатуру, чтобы клиент мог завершить диалог
                $this->supportChat->sendInlineKeyboard($clientId, 'Выполняется поиск оператора...', [['Завершить диалог' => '/completeDialog']]);

                $chats = Chat::whereClientId($clientId)
                    ->where('active', true)
                    ->where('source', $source)
                    ->get();

                if ($chats->count() > 0) {
                    //У пользователя оказался уже готовый чат
                    $chat = $chats->get(0);
                } else {
                    //Создаём чат
                    $chat = Chat::create(['active' => true,
                        'source' => \config('constants.chatSources.telegram'),
                        'snils' => Redis::get("ClientsData:{$source}:{$clientId}:snils"),
                        'client_id' => $clientId]);
                }

                //Состояние клиента теперь - "диалог"
                Redis::set("ClientsData:{$chat->source}:{$clientId}:condition", Constants\ClientConditions::DIALOGUE);
//                $this->supportChat->setUserCondition($clientId, Constants\ClientConditions::DIALOGUE);  //\config('constants.conditions.dialogue')

                //Ищем оператора для чата
                $operatorId = $this->searchOperator($chat);

                switch ($operatorId) {
                    case null:
                    {
                        $this->supportChat->sendText($chat, "К сожалению, в данный момент нет свободных операторов");
                        $this->completeDialog($chat);
                        break;
                    }
                    case -1:
                    {
                        $this->supportChat->sendText($chat, "К сожалению, ни один оператор не принял запрос");
                        $this->completeDialog($chat);
                        break;
                    }
                    default:
                    {
                        //Выбрали оператора, которому будет предложено взять диалог на себя
                        Redis::set("ChatsData:{$chat->id}:SelectedOperator", $operatorId);  //помечаем у этого чата выбранного оператора
                        NewDialogStarted::dispatch($chat, $operatorId);
                        break;
                    }
                }

/////
//                $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), \config('broadcasting.connections.pusher.options'));
//                $channels = (array)($pusher->get('/channels', [], true))['channels'];    //Получим ассоциативный массив массивов, соответствующих каналам
//                $channels = new Collection(array_keys($channels));  //Получаем массив имен каналов
//
//                //Считаем сколько у пользователей активных чатов, сортируем по возрастанию
//                $activeChatCounts = Chat::where('active', true)
//                    ->whereNotNull('user_id')
//                    ->whereNot('user_id', -1)
//                    ->groupBy('user_id')
//                    ->select('user_id', DB::raw('count(*) as count'))
//                    ->orderBy('count', 'asc')
//                    ->get();
//
//                //Считаем сколько у активных пользователей активных чатов
//                $usersActiveChatsCounts = [];
//                foreach ($channels as $channel){
//                    $usersActiveChatsCounts[explode('.', $channel)[1]] = 0;
//                }
//
//                //Пробегаем все активные чаты
//                foreach ($activeChatCounts as $chatCount) {
//                    //Этот пользователь сейчас активен
//                    if (isset($usersActiveChatsCounts[$chatCount->user_id])) {
//                        $usersActiveChatsCounts[$chatCount->user_id] = $chatCount->count;   //Ставим ему нужное количество активных чатов
//                    }
//                }
//
//                $message = "В ближайшее время оператор ответит вам";
//                if ($channels->count() == 0) {
//                    //Вообще нет активных операторов
//                    $message = "К сожалению, в данный момент нет активных операторов";
////                    $this->supportChat->sendText($chat, "К сожалению, в данный момент нет активных операторов");
//                } else {
//                    $minClients = \config('constants.limits.maxClientsForOperator');    //Минимальное число активных чатов на данный момент - верхний предел
//                    $userIdTarget = -1;   //ID пользователя с минимальным количеством клиентов. Он же искомый пользователь
//                    //Выбираем пользователя с минимальным количеством активных чатов
//                    foreach ($usersActiveChatsCounts as $userId => $count) {
//                        if ($count < $minClients) {
//                            $minClients = $count;
//                            $userIdTarget = $userId;
//                        }
//                    }
//                    //Пользователь найден
//                    if ($userIdTarget != -1) {
//                        NewDialogStarted::dispatch($chat, $userIdTarget);
//                    } else {
//                        $message = "К сожалению, в данный момент нет свободных операторов";
////                    $this->supportChat->sendText($chat, "К сожалению, в данный момент нет свободных операторов");
////                    TelegramUtilityController::sendStartMenu($botman);
//                    }
//                }

//                //Отправляем клавиатуру, чтобы клиент мог завершить диалог
//                $this->supportChat->sendInlineKeyboard($chat->client_id, $message, [['Завершить диалог' => '/completeDialog']]);

            } catch (\Exception $exception) {
                ErrorOccurred::dispatch("Исключение при запросе нового диалога", $exception);
            }
        } else {
            $this->supportChat->sendInlineKeyboard($clientId, 'Сначала завершите диалог!', [['Завершить диалог' => '/completeDialog']]);
        }

    }

    /**
     * Поиск оператора для обработки диалога
     * @return integer Id оператора,
     * или -1 если все операторы отказались от диалога,
     * или null если нет активных операторов
     * @throws PusherException|GuzzleException
     */
    public static function searchOperator(Chat $chat)
    {
//        $this->driverInit($source);
        $value = null;

        try {
            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), \config('broadcasting.connections.pusher.options'));
            $channels = (array)($pusher->get('/channels', [], true))['channels'];    //Получим ассоциативный массив массивов, соответствующих каналам
            $channels = new Collection(array_keys($channels));  //Получаем массив имен каналов

            //Считаем сколько у пользователей активных чатов, сортируем по возрастанию
            $activeChatCounts = Chat::where('active', true)
                ->whereNotNull('user_id')
                ->whereNot('user_id', -1)
                ->groupBy('user_id')
                ->select('user_id', DB::raw('count(*) as count'))
                ->orderBy('count', 'asc')
                ->get();

            $refusedOperators = Redis::command('SMEMBERS', ["ChatsData:{$chat->id}:RefusedOperators"]); //Получаем операторов, которые отказались от этого чата
            //Считаем сколько у активных пользователей активных чатов
            $operators = [];    //Массив Id операторов
            $usersActiveChatsCounts = [];
            foreach ($channels as $channel) {
                $operatorId = explode('.', $channel)[1];
                $operators[] = $operatorId;
                $usersActiveChatsCounts[$operatorId] = 0;   //Изначально 0 активных чатов
            }

            //Удаляем из массива тех, которые отказались
            foreach ($refusedOperators as $refusedOperator) {
                if (isset($usersActiveChatsCounts[$refusedOperator]))
                {
                    unset($usersActiveChatsCounts[$refusedOperator]);
                }
            }

            //Пробегаем все кортежи из выборки и получаем количество активных чатов
            foreach ($activeChatCounts as $chatCount) {
                //Этот пользователь сейчас активен
                if (isset($usersActiveChatsCounts[$chatCount->user_id])) {
                    $usersActiveChatsCounts[$chatCount->user_id] = $chatCount->count;   //Ставим ему нужное количество активных чатов
                }
            }

            if ($channels->count() == 0) {
                //Вообще нет активных операторов
//                $value = null;
            } elseif (count($usersActiveChatsCounts) == 0)
            {
                //Активные операторы есть, но никто из них не принял диалог
                $value = -1;
            } else
            {

                $minClients = \config('constants.limits.maxClientsForOperator');    //Минимальное число активных чатов на данный момент - верхний предел
                $userIdTarget = -1;   //ID пользователя с минимальным количеством клиентов. Он же искомый пользователь
                //Выбираем пользователя с минимальным количеством активных чатов
                foreach ($usersActiveChatsCounts as $userId => $count) {
                    if ($count < $minClients) {
                        $minClients = $count;
                        $userIdTarget = $userId;
                    }
                }

                $value = $userIdTarget;
//                    //Пользователь найден
//                    if ($userIdTarget != -1) {
//                        NewDialogStarted::dispatch($chat, $userIdTarget);
//                    } else {
//                        $message = "К сожалению, в данный момент нет свободных операторов";
////                    $this->supportChat->sendText($chat, "К сожалению, в данный момент нет свободных операторов");
////                    TelegramUtilityController::sendStartMenu($botman);
//                    }
            }

        } catch (\Exception $exception) {
            ErrorOccurred::dispatch("Исключение при запросе нового диалога", $exception);
        }
        return $value;
    }

    /**
     * Принять диалог
     * @param Chat $chat
     * @return void Результат
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function acceptDialog(Chat $chat): void
    {
        try {
            $this->driverInit($chat->source);

            $user = UserController::getAuthentifiedUser();

            //Закрепляем чат за оператором
            $chat->user_id = $user->getUserId();
            $chat->save();
            Redis::command('del', ["ChatsData:{$chat->id}:SelectedOperator"]);  //Удаляем выбранного оператора для этого чата

            $this->supportChat->sendText($chat, "Оператор принял вашу заявку");
            NewDialogAccepted::dispatch($chat);
        } catch (Exception $e) {
            ErrorOccurred::dispatch('Исключение при подтверждении диалоага оператором', $e, true);
        }
    }

    /**
     * Отказ оператора принимать диалог
     * @param Chat $chat
     * @return void
     */
    public function rejectDialog(Chat $chat)
    {
        try {
            $this->driverInit($chat->source);
            $userId = UserController::getAuthentifiedUser()->getUserId();
            //Добавляем оператора в список "отказников" для этого чата
            Redis::command('sadd', ["ChatsData:{$chat->id}:RefusedOperators", $userId]);
            NewDialogRejected::dispatch($chat, $userId);
        } catch (Exception $e) {
            ErrorOccurred::dispatch('Исключение при отклонении диалоага оператором', $e, true);
        }
    }

    /**
     * Отправить сообщение клиенту
     * @param Chat $chat
     * @return void Результат
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function sendMessage(Chat $chat): void
    {
        try {
            $text = request('text');

            //Создаём сообщение. Оно по умолчанию считается прочитанным
            $message = Message::create(['from_client' => false,
                'chat_id' => $chat->id,
                'text' => $text,
                'read' => true]);

            //В зависимости от источника чата отправляем сообщение клиенту
            switch ($chat->source) {
                case config('constants.chatSources.telegram'):
                {
                    $sourceController = new TelegramUtilityController();
                    $sourceController->sendMessage($message);
                    break;
                }
                default:
                {
                    ErrorOccurred::dispatch("Не удалось определить источник чата #{$chat->id}");
                }
            }

            MessageReceived::dispatch($message);
        } catch (Exception $e) {
            ErrorOccurred::dispatch('Исключение при отправке сообщения от оператора', $e, true);
        }


//        $chat = Chat::find($id);    //Получаем запись
//
//        if ($chat == null) {
//            ErrorOccurred::dispatch('Чат не найден', -1, true);
////            return ['result' => -1, 'text' => 'Чат не найден'];
//        }
    }

    /**
     * Прочитать сообщения этого чата
     * @param Chat $chat
     * @return false|string
     */
    public function readMessages(Chat $chat): bool|string
    {
        try {
            $builder = Message::where('chat_id', $chat->id)
                ->whereNot(function ($query) {
                    $query->where('read', '=', true);
                })
                ->orWhereNull('read');
            $idArray = $builder->pluck('id')->toArray(); //Нам нужен массив ID сообщений, чтобы пометить те, которые прочитали
            //Отмечаем все непрочитанные сообщения как прочитанные. Мы не стали изменять готовые модели сообщений из чата, т.к. их пришлось бы перебирать по одной
            $builder->update(['read' => true]);
        } catch (Exception $exception) {
            return json_encode(['result' => -1, 'data' => $exception->getMessage()]);
        }

//        return ['result' => 0, 'text' => 'Чат не найден'];
        return json_encode(['result' => 0, 'data' => $idArray]);
    }

//    /**
//     * Получить диалоги, которые предложены этому оператору
//     * @param User $user
//     * @return array
//     */
//    private function getRequestDialog (User $user): array
//    {
//        //Получаем все активные чаты с неподтверждённым оператором
//        $noConfirmedChats = Chat::where('active', true)
//            ->whereNull('user_id')
//            ->get();
//
//        $requestedChats = [];
//        foreach ($noConfirmedChats as $chat) {
//            if (Redis::get("ChatsData:{$chat->id}:SelectedOperator"))
//            {
//                $requestedChats[] = $chat;
//            }; //Получаем данные всех чатов, которые ещё  операторов, которые отказались от этого чата
//        }
//
//        return $requestedChats;
//    }
}
