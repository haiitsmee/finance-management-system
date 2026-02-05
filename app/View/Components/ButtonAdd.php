<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ButtonAdd extends Component
{
    public $direct;
    public $value;
    /**
     * Create a new component instance.
     */
    public function __construct($direct, $value)
    {
        $this->direct = $direct;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button-add');
    }
}
