<?php

declare(strict_types=1);

namespace App\Livewire\Layouts;

use Illuminate\View\View;
use Livewire\Component;

class Navbar extends Component
{
    public function render(): View
    {
        return view('livewire.layouts.navbar');
    }
}
