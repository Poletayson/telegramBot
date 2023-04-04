<?php

namespace App\View\Components\admin;

use App\View\Components\Component;  //Кастомный компонент, предусматривающий заголовок и массив классов
use Illuminate\Contracts\View\View;

class AdminPanel extends Component
{
    /**
     * Конструктор по умолчанию
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('Панель администратора', []);
    }

//    /**
//     * Create a new component instance.
//     *
//     * @return void
//     */
//    public function __construct(string $title = 'Панель администратора',
//                                array $styles = [])
//    {
//        parent::__construct('Панель администратора', []);
//    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.admin-panel', ['title' => $this->title,
            'styles' => $this->styles]);
    }
}
