<?php

namespace App\Listeners;

use App\Events\NewDialogAccepted;
use BotMan\BotMan\BotMan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Слушатель события "Новый диалог принят оператором"
 */
class NewDialogAcceptedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewDialogAccepted  $event
     * @return void
     */
    public function handle(NewDialogAccepted $event)
    {
//        //Сообщаем о том, что оператор подключился
//        switch ($event->chat->source) {
//            case config('constants.chatSources.telegram'): {
//                $botman = app('botman');
////                $botman = \BotMan\BotMan\BotManFactory::create(config('botman.telegram'));
//                $botman->say("--Оператор подключился--", [$event->chat->client_id])->set;
//            }
//        }
    }
}
