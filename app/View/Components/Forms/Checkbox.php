<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Checkbox extends Component
{
    public $for;
    public $label;
    public $value;
    public $name;
    public $checked;
    /**
     * Create a new component instance.
     */
    public function __construct($for, $label, $value, $name, $checked)
    {
        $this->for = $for;
        $this->label = $label;
        $this->value = $value;
        $this->name = $name;
        $this->checked = $checked;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.checkbox');
    }
}
