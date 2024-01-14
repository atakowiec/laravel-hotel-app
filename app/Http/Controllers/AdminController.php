<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function index()
    {
        return $this->livewire_nav_view("admin-panel");
    }
    public function create()
    {
        return $this->livewire_nav_view("edit-room");
    }
}
