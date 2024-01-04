<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function livewire_blank_view(string $liveWireComponent, array $params = [])
    {
        return view('layouts.livewire.blank-base',
            ["liveWireComponent" => $liveWireComponent, "params" => $params]);
    }

    public function livewire_nav_view(string $liveWireComponent, array $params = [])
    {
        return view('layouts.livewire.nav-base',
            ["liveWireComponent" => $liveWireComponent, "params" => $params]);
    }
}
