<?php

namespace App\Http\Controllers;

use App\Drivers\SupportChat\SupportChatInterface;
use App\Drivers\SupportChat\TelegramChat;
use App\Constants;
use App\Events\ErrorOccurred;
use App\Events\MessageReceived;
use App\Events\NewDialogStarted;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
use App\Models\Chat;
use App\Models\Message;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Telegram\Extensions\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Pusher\Pusher;
use Pusher\PusherException;

/**
 * Контроллер для обработки сообщений от пользователей из Телеграма
 */
class TelegramBotController extends Controller
{

    private bool $wasInit = false;
    public \App\Models\Auth\User $user;

    private SupportChatInterface $supportChat;

    public function __construct()
    {
        $this->supportChat = new TelegramChat();    //Здесь используем сразу определённый драйвер
    }

//    /**
//     * @return void
//     * @throws \Psr\Container\ContainerExceptionInterface
//     * @throws \Psr\Container\NotFoundExceptionInterface
//     */
//    private function init () {
//        if (!$this->wasInit) {
//            //Под вопросом
//            $this->user = UserController::getAuthentifiedUser();    //Инициализируется не в конструкторе, т.к. объект создается перед срабатыванием посредника аутентификации
//            $this->wasInit = true;
//        }
//    }

    /**
     * Запросить начало диалога
     * @return void
     * @throws PusherException|GuzzleException
     */
    public function startDialogRequest (BotMan $botman) {

        $chatController = new ChatController();
        $chatController->startDialogRequest($botman->getUser()->getId(), \config('constants.chatSources.telegram'));
    }

    /**
     * Запрос от клиента на завершение диалога
     * @param BotMan $botman
     * @return void
     */
    public function completeDialog (BotMan $botman) {
        $chats = Chat::whereClientId($botman->getUser()->getId())
            ->where('active', true)
            ->where('source', Config::get('constants.chatSources.telegram'))
            ->get();

        if ($chats->count() > 0) {
            //Нужный чат найден, завершаем диалог
            $chatController = new ChatController();
            $chatController->completeDialog($chats->get(0));
        } else {
            ErrorOccurred::dispatch('Чат не найден', null, true);
        }
    }


    public function getStart (BotMan $botman)
    {
        $source = Constants\ChatSources::TELEGRAM;
        $clientId = $botman->getUser()->getId();
        if (Redis::get("ClientsData:{$source}:{$clientId}:condition") != Constants\ClientConditions::DIALOGUE) {
            Redis::set("ClientsData:{$source}:{$clientId}:condition", Constants\ClientConditions::EXPECTATION);
//            $this->supportChat->setUserCondition($botman->getUser()->getId(), Constants\ClientConditions::EXPECTATION);   //, \config('constants.conditions.expectation')
            //Отправляем стартовую клавиатуру
            $this->supportChat->sendInlineKeyboard($botman->getUser()->getId(), 'Выберите действие на клавиатуре', [['Меню' => '/start'],
                ['Связаться с оператором' => '/startDialogRequest']]);
        } else {
            $this->supportChat->sendInlineKeyboard($botman->getUser()->getId(), 'Сначала завершите диалог!', [['Завершить диалог' => '/completeDialog']]);
        }
    }



//    $botman->ask($question, function (Answer $answer) use ($botman) {
//        // здесь можно указать какие либо условия, но нам это не нужно сейчас
//
//        $botman->reply($answer->getText() . ' ' . $answer->getValue());
//    });

//    $keyboard = Keyboard::create(Keyboard::TYPE_KEYBOARD)
//        ->oneTimeKeyboard(true)
//        ->addRow((new KeyboardButton('Начать'))->callbackData('start'))
//        ->addRow((new KeyboardButton('requestContact'))->requestContact())
//        ->addRow((new KeyboardButton('Расположение'))->requestLocation())
//        ->addRow(new KeyboardButton('Выход'))
//    ->toArray();
//    $botman->reply('Вас приветствует бот поддержки Министерства здравоохранения Алтайского края', $keyboard);

//    $info = '';
//    foreach ($user->getInfo() as $item) {
//        $info .= $item;
//    }
//
//    $user = $botman->getUser();
//    $botman->reply($user->getId()
//        . ' ' . $user->getLanguageCode()
//        . ' ' . $user->getStatus()
//        . ' ' . $user->getUsername()
//        . ' ' . $user->getFirstName()
//        . ' ' . $user->getLastName() . ' ' );
//
//    }

    /**
     * Обработать полученное сообщение
     * @param BotMan $botman
     * @return void
     */
    public function getMessage (BotMan $botman) {
//        $this->init();
//        $userID = $botman->getUser()->getId();
        $text = $botman->getMessage();   //Сообщение

        //Получаем активный чат с пользователем, из Телеграма
        $chats = Chat:://whereUserId($this->user->getUserId())
            where('client_id', $botman->getUser()->getId())
            ->where('source', Config::get('constants.chatSources.telegram'))
            ->where('active', true)
            ->get();

        if ($chats->count() == 1) {
            //Создаём сообщение
            $message = Message::create(['from_client' => true,
                'chat_id' => $chats->get(0)->id,
                'text' => $text->getText()]);
            $chat_id_user_id = $message->chat->user_id;
            MessageReceived::dispatch($message);
        } else {
            //Чатов почему-то несколько
            ErrorOccurred::dispatch('Полученное от пользователя сообщение невозможно однозначно отнести к какому-либо чату', null, true);
        }

    }

    /**
     * Получить сохраненное ранее значение с инкрементом
     * @param BotMan $botman
     * @return int|mixed\
     */
    public function getIncrementedValue (BotMan $botman) {
        $value = $botman->userStorage()->get('value') ?? 0;
        $value++;
        $botman->userStorage()->save(['value' => $value]);
        $botman->reply($value);
    }

    public function sendMessage (BotMan $botman) {
//        $botman->
    }

    /**
     * Завершить общение с ботом. Пользовательские данные удаляются
     * @param BotMan $botman
     * @return void
     */
    public function exit (BotMan $botman) {
        $botman->userStorage()->delete();
        $this->getStart($botman);
    }
}
