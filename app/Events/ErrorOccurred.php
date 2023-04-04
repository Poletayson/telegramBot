<?php

namespace App\Events;

use App\Http\Controllers\Auth\UserController;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Событие "Произошла ошибка"
 */
class ErrorOccurred implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ?Exception $exception;

    public ?string $text;

    /**
     * @var bool Отправлять ли пользователю уведомление об ошибке. По умолчанию нет
     */
    public bool $sendToUser;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(?string $text, Exception $exception = null, bool $sendToUser = false)
    {
        $this->exception = $exception;
        $this->text = $text;
        $this->sendToUser = $sendToUser;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if ($this->sendToUser) {
            try {
                $user = UserController::getAuthentifiedUser();
                Log::error('Обработанное исключение: ' . $this->text, $this->exception != null ? $this->exception->getTrace() : []);
                return new PrivateChannel("user.{$user->getUserId()}");   //Канал, связанный с конкретным пользователем, который ведёт этот чат
            } catch (Exception $e) {
                //TODO Здесь бы запись в лог об исключении
                return [];
            }

        } else {
            return [];
        }
        //TODO Здесь бы запись в лог об исключении
    }
}
