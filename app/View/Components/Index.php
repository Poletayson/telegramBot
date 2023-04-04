<?php

namespace App\View\Components;

use App\View\Components\Component;  //Кастомный компонент, предусматривающий заголовок и массив классов

class Index extends Component
{
    /**
     * Конструктор по умолчанию
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('Чат поддержки', []);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.index', [
            'title' => $this->title,
            'styles' => $this->styles
        ]);
    }
}
