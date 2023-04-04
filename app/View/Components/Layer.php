<?php

namespace App\View\Components;

use App\Http\Controllers\Auth\UserController;
use App\Models\Auth\User;
use Closure;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;


/**
 * Основной компонент страницы
 */
class Layer extends Component
{
    /**
     * @var User|null
     */
    public ?User $user;

    /**
     * @var string|null
     */
    public ?string $title;
    public ?array $styles;
    private string $error;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(?string $title, ?array $styles = null)
    {
        $this->title = $title;
        $this->styles = $styles;
        try {
            $this->user = UserController::getAuthentifiedUser();
        } catch (Exception $e) {
            $this->user = null;
            $this->error = 'Исключение: ' . $e->getMessage();
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|string|Closure
    {
        return view('components.layer');
    }
}
