<?php

namespace App\View\Components\chat;

use App\View\Components\Component;
use Illuminate\Support\Collection;

//Кастомный компонент, предусматривающий заголовок и массив классов

class SupportChat extends Component
{

    /**
     * @var Collection Массив чатов
     */
    private Collection $chats;

    /**
     * @var Collection
     */
    private Collection $requestedChats;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Collection $chats, Collection $requestedChats)
    {
        parent::__construct('Чат поддержки', []);
        $this->chats = $chats;
        $this->requestedChats = $requestedChats;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.chat.support-chat', [
            'title' => 'Панель оператора',
            'styles' => ['/css/chat.css'],
            'chats' => $this->chats,
            'requestedChats' => $this->requestedChats,
        ]);
    }
}
