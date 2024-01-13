<?php

namespace App\Http\Controllers;

use App\Traits\FloatingComponent;

class ProfileController extends Controller
{
    public function index()
    {
        return $this->livewire_nav_view('profile');
    }
}
