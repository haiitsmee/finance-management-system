<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{
    public $for;
    public $label;
    public $width;
    /**
     * Create a new component instance.
     */
    public function __construct($for, $label, $width)
    {
        $this->for = $for;
        $this->label = $label;
        $this->width = $width;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.select');
    }
}
