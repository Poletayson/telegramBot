<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use BotMan\BotMan\BotMan;

//class TelegramController
//{
//    private BotMan $botman;
//
//    public function __construct (BotMan $botman) {
//        $this->botman = $botman;
//    }
//
//    public function sendMessage(Chat $chat, $message)
//    {
//        /**
//         * @var User $user Пользователь, приславший сообщение
//         */
//        $question = Question::create('Выберите интересующий пункт');
//        $question->addButton(Button::create('Начать')->value('/start'));
//        $question->addButtons([
//            Button::create('Контакт')->value('requestContact'),
//            Button::create('Расположение')->value('requestLocation'),
//            Button::create('Инкремент')->value('/getIncrement')
//        ]);
//        $question->addButton((new Button('Выход'))->value('/exit'));
//        $this->botman->reply($message);
//    }
//}
