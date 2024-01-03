<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LoadingAnimation extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View
    {
        return view('components.loading-animation');
    }
}
