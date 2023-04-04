<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\View\Components\admin\AdminPanel;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Telegram\Console\Commands\TelegramRegisterCommand;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use BotMan\Drivers\Telegram\Extensions\User;
use BotMan\Drivers\Telegram\TelegramDriver;
use GuzzleHttp\Client;
use BotMan\BotMan\BotMan;
use Illuminate\Support\Facades\Gate;

/**
 * Класс для совершения вспомогательных действий
 */
class TelegramUtilityController extends Controller
{
    //Обработка запросов от Телеграма
    public function handle() {
        $botman = app('botman');

//        http_response_code(200);    //Устанавливаем код ответа 200
//        fastcgi_finish_request();   //Сбрасываем все данные ответа клиенту и завершаем выполнение запроса - и выполнение продолжится, а Телеграм не будет повторно стучаться
        $botman->listen();
    }

    public function __invoke(): void
    {
        $botman = app('botman');

        $botman->fallback(function (BotMan $bot) {
            $bot->reply('Вас приветствует бот службы поддержки контакт-центра Министерства Здравоохранения Алтайского края');
        });

        $botman->listen();
    }

    /**
     * Отправить сообщение клиенту из Телеграма
     * @param Message $message Модель Сообщения
     * @return void
     */
    public function sendMessage (Message $message) {
        /**
         * @var Botman $botman
         */
        $botman = app('botman');
        $botman->say($message->text, $message->chat->client_id, TelegramDriver::class);
    }

    /**
     * Отправить стартовое меню клиенту
     * @param BotMan $botman
     * @return void
     */
    public static function sendStartMenu (BotMan $botman): void
    {
        /**
         * @var User $user Пользователь, приславший сообщение
         * @var BotMan $botman
         */
        $question = Question::create('Выберите интересующий пункт');
        $question->addButton(Button::create('Начать')->value('/start'));
        $question->addButtons([
            Button::create('Контакт')->value('requestContact'),
            Button::create('Связаться с оператором')->value('/startDialogRequest'),
            Button::create('Инкремент')->value('/getIncrement')
        ]);
        $question->addButton((new Button('Выход'))->value('/exit'));
        $botman->reply($question);

//        $botman->sendPayload(Keyboard::create()
//            ->type(Keyboard::TYPE_KEYBOARD)
//            ->addRow(KeyboardButton::create('Начать'))
//            ->addRow(KeyboardButton::create('Связаться с оператором'))
//            ->addRow(KeyboardButton::create('Связаться с оператором'))
//            ->addRow(KeyboardButton::create('Инкремент'))
//            ->toArray());
    }

    /**
     * Отправить клиенту клавиатуру
     * @param BotMan $botman
     * @return void
     */
    public static function sendKeyboard (BotMan $botman, array $buttons)
    {
        $keyboard = Keyboard::create()->type(Keyboard::TYPE_KEYBOARD);
        foreach ($buttons as $label => $value) {
            $keyboard->addRow(KeyboardButton::create($label)->callbackData($value));
        }
        $botman->reply($keyboard->toArray());
    }

    /**
     * Зарегистрировать бота. Отправляется запрос на установку вебхука
     * @return
     */
    public function registerBot () {
        try {
            $url = request('url');
            return $this->setWebHook ($url . '/' . env('TELEGRAM_BOT_URL_PREFIX'));
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

    }

    /**
     * Установить вебхук в телеграм-бот
     * @param string $
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function setWebHook (string $webHookAddress) {
        return $this->sendPOST('setWebhook', ['url' => $webHookAddress]);
    }

    private function sendGET (string $address, ?array $parameters = null) {
        $client = new Client([
            'base_uri' => env('TELEGRAM_API_URL') . 'bot' . env('TELEGRAM_API_KEY') . '/'
        ]);

        return $client->get($address, [
            'query' => $parameters
        ]);
    }

    private function sendPOST (string $address, ?array $parameters = null) {
//        $url = env('TELEGRAM_API_URL') . 'bot' . env('TELEGRAM_API_KEY') . '/' . $address;

        $client = new Client([
            'base_uri' => env('TELEGRAM_API_URL') . 'bot' . env('TELEGRAM_API_KEY') . '/'
        ]);

        return $client->post($address, [
            'form_params' => $parameters
        ]);
    }

    /**
     * Открыть панель администратора
     * @return \Closure|\Illuminate\Contracts\View\View|string
     */
    public function adminPanel () {

        //Проверяем авторизацию
        abort_if(($response = Gate::inspect('AdminPanelPolicy-adminPanel'))->denied(), $response->code(), $response->message());
        return (new AdminPanel())->render();
    }
}
