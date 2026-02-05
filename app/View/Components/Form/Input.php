<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{   
    public $for;
    public $placeholder;
    public $type;
    public $width;
    public $label;
    public $required;
    public $readonly;
    public $value;
    /**
     * Create a new component instance.
     */
    public function __construct($for, $placeholder, $type, $width, $label, $required, $readonly, $value)
    {
        $this->for = $for;
        $this->placeholder = $placeholder;
        $this->type = $type;
        $this->width = $width;
        $this->label = $label;
        $this->required = $required;
        $this->readonly = $readonly;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.input');
    }
}
