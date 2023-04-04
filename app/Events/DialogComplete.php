<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

/**
 * Событие "Диалог завершён". Может происходить по инициативе как оператора, так и пользователя
 */
class DialogComplete implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Chat $chat;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $userId = $this->chat->user_id;
        //Если чат не был присвоен какому-либо оператору, нужно попытаться взять из Redis оператора, которому в данный момент предложен диалог
        if (is_null($userId) || $userId === 0) {
            try {
                $userId = Redis::get("ChatsData:{$this->chat->id}:SelectedOperator");
            } catch (\Exception $exception) {

            }
        }

        return new PrivateChannel("user.{$userId}");   //Канал, связанный с конкретным пользователем, который ведёт этот чат
    }
}
