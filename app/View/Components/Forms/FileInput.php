<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FileInput extends Component
{
    public $label;
    public $width;
    public $for;
    public $required;
    public $note;
    /**
     * Create a new component instance.
     */
    public function __construct(
        string $for,
        string $label,
        bool $required = false,
        string $width = null,
        string $note = null
    ) {
        $this->for = $for;
        $this->label = $label;
        $this->required = $required;
        $this->width = $width;
        $this->note = $note;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.file-input');
    }
}
