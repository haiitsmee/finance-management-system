<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Textarea extends Component
{
    public $for;
    public $label;
    public $rows;
    public $cols;
    public $placeholder;
    public $value;
    /**
     * Create a new component instance.
     */
    public function __construct($for, $label, $rows, $cols, $placeholder, $value = '')
    {
        $this->for = $for;
        $this->label = $label;
        $this->rows = $rows;
        $this->cols = $cols;
        $this->placeholder = $placeholder;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.textarea');
    }
}
