<?php

namespace App\View\Components\chat;

use Illuminate\View\Component;

class Message extends Component
{
    public ?\App\Models\Message $message;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(\App\Models\Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.chat.message', [
            'message' => $this->message
        ]);
    }
}
