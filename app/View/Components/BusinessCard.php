<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BusinessCard extends Component
{
    public $businessName;
    public $businessId;
    public $businessRole;
    public $businessAdmins;
    /**
     * Create a new component instance.
     */
    public function __construct($businessName, $businessId, $businessRole, $businessAdmins)
    {
        $this->businessName = $businessName;
        $this->businessId = $businessId;
        $this->businessRole = $businessRole;
        $this->businessAdmins = $businessAdmins;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.business-card');
    }
}
