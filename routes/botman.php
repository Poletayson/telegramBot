<?php

use App\Http\Controllers\TelegramBotController;
use App\Http\Middleware\VerifyCsrfToken;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use BotMan\Drivers\Telegram\Extensions\User;



/**
 * @var BotMan $botman
 */

$botman = resolve('botman');

$botman->hears ('/prestart', function ($botman) {
    /**
     * @var User $user Пользователь, приславший сообщение
     * @var BotMan $botman
     */
    $question = Question::create('Вас приветствует бот поддержки Министерства здравоохранения Алтайского края');
    $question->addButton(Button::create('Начать')->value('/start'));

    $botman->reply($question);

//    $botman->ask($question, function (Answer $answer) use ($botman) {
//        // здесь можно указать какие либо условия, но нам это не нужно сейчас
//
//        $botman->reply($answer->getText() . ' ' . $answer->getValue());
//    });
});

$botman->hears ('/start', TelegramBotController::class . '@getStart');

$botman->hears ('/startDialogRequest', TelegramBotController::class . '@startDialogRequest');   //Запросить начало диалога
$botman->hears ('/completeDialog', TelegramBotController::class . '@completeDialog');   //Запросить начало диалога

//Любое другое сообщение
$botman->fallback(TelegramBotController::class . '@getMessage');

