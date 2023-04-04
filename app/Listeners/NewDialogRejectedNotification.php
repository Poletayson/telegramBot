<?php

namespace App\Listeners;

use App\Events\NewDialogRejected;
use App\Events\NewDialogStarted;
use App\Http\Controllers\ChatController;
use App\Models\Chat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Событие, возникающее при отказе оператора от диалога
 */
class NewDialogRejectedNotification
{

    private ChatController $chatController;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ChatController $chatController)
    {
        $this->chatController = $chatController;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NewDialogRejected $event)
    {

        $operatorId = $this->chatController->searchOperator($event->chat);  //Пытаемся выбрать нового подходящего оператора
        switch ($operatorId) {
            case null:
            {
                $this->supportChat->sendText($event->chat, "К сожалению, в данный момент нет свободных операторов");
                break;
            }
            case -1:
            {
                $this->chatController->driverInit($event->chat->source);
                $this->chatController->supportChat->sendText($event->chat, "К сожалению, в данный момент ни один оператор не принял ваше обращение");
                $this->chatController->completeDialog($event->chat);
                break;
            }
            default:
            {
                NewDialogStarted::dispatch($event->chat, $operatorId);
                break;
            }
        }
    }
}
