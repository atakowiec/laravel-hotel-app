<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct()
    {
        // empty
    }

    public function index()
    {
        return $this->livewire_blank_view("login.login");
    }

    public function create()
    {
        return $this->livewire_blank_view("login.register");
    }
}
