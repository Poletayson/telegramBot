<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use function Termwind\render;

/**
 * Событие "Новый диалог принят оператором"
 */
class NewDialogAccepted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Chat $chat;

    /**
     * @var array Сообщения, которые уже есть на данный момент
     */
    public Collection $messages;

    /**
     * @var string|\Closure|\Illuminate\Contracts\View\View Отрендеренная вкладка с чатом
     */
    public string $renderedChat;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
        $this->messages = $chat->messages;

        $this->renderedChat = (new \App\View\Components\chat\Chat($this->chat))->render();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("user.{$this->chat->user_id}");   //Канал, связанный с конкретным пользователем, который ведёт этот чат
    }
}
