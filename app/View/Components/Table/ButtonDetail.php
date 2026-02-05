<?php

namespace App\View\Components\Table;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ButtonDetail extends Component
{
    public $redirect;
    /**
     * Create a new component instance.
     */
    public function __construct($redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table.button-detail');
    }
}
