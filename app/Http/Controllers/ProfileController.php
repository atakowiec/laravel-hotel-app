<?php

namespace App\Http\Controllers;

use App\Traits\WithFloatingComponent;

class ProfileController extends Controller
{
    public function index()
    {
        return $this->livewire_nav_view('profile');
    }
}
