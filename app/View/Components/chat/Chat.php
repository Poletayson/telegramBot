<?php

namespace App\View\Components\chat;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

/**
 * @mixin Builder
 */
class Chat extends Component
{
    public ?\App\Models\Chat $chat;

    public int $unreadedMessages = 0;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(\App\Models\Chat $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.chat.chat', [
            'chat' => $this->chat
        ]);
    }
}
