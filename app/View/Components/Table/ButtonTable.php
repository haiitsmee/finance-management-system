<?php

namespace App\View\Components\Table;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ButtonTable extends Component
{
    public $redirect;
    public $value;
    public $color;
    public $type;
    public $submit;
    /**
     * Create a new component instance.
     */
    public function __construct($redirect, $value, $color, $type, $submit)
    {
        $this->redirect = $redirect;
        $this->value = $value;
        $this->color = $color;
        $this->type = $type;
        $this->submit = $submit;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table.button-table');
    }
}
