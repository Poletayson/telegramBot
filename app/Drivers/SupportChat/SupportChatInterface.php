<?php

namespace App\Drivers\SupportChat;

use App\Models\Chat;
use App\Models\Message;

interface SupportChatInterface
{
    /**
     * Установить значение в пользовательском хранилище
     * @param $clientId
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setStorageValue ($key, $value);

    /**
     * Получить значение из хранилища
     * @param $clientId
     * @param $key
     * @return mixed
     */
    public function getStorageValue ($key);

    /**
     * Получить значение из пользовательского хранилища
     * @param $clientId
     * @param $key
     * @return mixed
     */
    public function getUserStorageValue ($clientId, $key);

    /**
     * Установить значение в пользовательском хранилище
     * @param $clientId
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setUserStorageValue ($clientId, $key, $value);

    /**
     * Установить состояние пользователя
     * @param $clientId
     * @param int $condition
     * @return mixed
     */
    public  function setUserCondition ($clientId, int $condition);

    /**
     * Отправить сообщение оператора клиенту
     * @param $message
     * @return mixed
     */
    public function sendMessage (Message $message);

    /**
     * Отправить клиенту клавиатуру вместе с сообщением
     * @param string $clientId ID клиента
     * @param string $message Сообщение, с которым будет отправлена клавиатура
     * @param array $buttons Ассоциативный массив кнопок
     * @return mixed
     */
    public function sendKeyboard (string $clientId, string $message, array $buttons);

    /**
     * Отправить клиенту клавиатуру, встроенную в сообщение
     * @param string $clientId ID клиента
     * @param string $message Сообщение, с которым будет отправлена клавиатура
     * @param array $buttons Двумерный массив, содержащий ассоциативные массивы кнопок
     * @return mixed
     */
    public function sendInlineKeyboard (string $clientId, string $message, array $buttons);

    /**
     * Отправить тектовое сообщение в заданный чат
     * @param Chat $chat
     * @param string $message
     * @return mixed
     */
    public function sendText (Chat $chat, string $message);
}
