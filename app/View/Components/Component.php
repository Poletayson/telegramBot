<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component as ComponentBase;

abstract class Component extends ComponentBase
{
    public ?string $title = null;
    public ?array $styles = null;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $title,
                                array $styles = [])
    {
        $this->styles = $styles;
        $this->title = $title;
    }
}
