<?php

namespace App\Drivers\SupportChat;

use App\Models\Chat;
use App\Models\Message;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Storages\Drivers\RedisStorage;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Support\Facades\Redis;

class TelegramChat implements SupportChatInterface
{
    private Botman $botman;

    public function __construct()
    {
        $this->botman = app('botman');
    }

    /**
     * @inheritDoc
     */
    public function setUserCondition($clientId, int $condition)
    {
        $this->setUserStorageValue($clientId, 'condition', $condition);
    }

    /**
     * @inheritDoc
     */
    public function sendMessage(Message $message)
    {
        // TODO: Implement sendMessage() method.
    }

    /**
     * @inheritDoc
     */
    public function setStorageValue($key, $value)
    {
        Redis::set($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function getStorageValue($key)
    {
        $value = Redis::get($key);

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getUserStorageValue($clientId, $key)
    {
        return $this->getStorageValue("ClientsData:Telegram:{$clientId}:{$key}");
    }

    /**
     * @inheritDoc
     */
    public function setUserStorageValue($clientId, $key, $value)
    {
//        //Получаем все хранилища и находим хранилище искомого пользователя
//        $userStorage = $this->botman->userStorage()->find($clientId); //get($clientId);
//
//        $userStorage[$key] = $value;    //Устанавливаем значение в пользовательский массив (даже если массив null)
//        //Нужно задать ID клиента, если не задано
//        if (!isset($userStorage['sender_id'])){
//            $userStorage['sender_id'] = $clientId;
//        }
//
//        $this->botman->userStorage()->save($userStorage->toArray(), $clientId);

        $this->setStorageValue("ClientsData:Telegram:{$clientId}:{$key}", $value);
    }

    /**
     * @inheritDoc
     */
    public function sendText(Chat $chat, string $message)
    {
        $this->botman->say($message, $chat->client_id, TelegramDriver::class);
    }

    /**
     * @inheritDoc
     */
    public function sendKeyboard(string $clientId, string $message, array $buttons)
    {
        $keyboard = Keyboard::create()->resizeKeyboard()->type(Keyboard::TYPE_KEYBOARD);
        foreach ($buttons as $label => $value) {
            $keyboard->addRow(KeyboardButton::create($label)->callbackData($value));
        }
//        $this->botman->sendPayload($keyboard);
        $keyboardArray = $keyboard->toArray();
        $this->botman->say($message, $clientId, TelegramDriver::class, $keyboardArray);

    }

    /**
     * @inheritDoc
     */
    public function sendInlineKeyboard(string $clientId, string $message, array $buttons)
    {
        $keyboard = Keyboard::create()->resizeKeyboard()->type(Keyboard::TYPE_INLINE);
        foreach ($buttons as $buttonGroup) {
            $buttonsArray = [];
            foreach ($buttonGroup as $label => $value) {
                $buttonsArray[] = KeyboardButton::create($label)->callbackData($value);
            }
            $keyboard->addRow(...$buttonsArray);
        }
//        $this->botman->sendPayload($keyboard);
        $keyboardArray = $keyboard->toArray();
        $this->botman->say($message, $clientId, TelegramDriver::class, $keyboardArray);
    }
}
