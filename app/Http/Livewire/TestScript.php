<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class TestScript extends Component
{
    public $checked = [];

    public function setChecked($key, $value)
    {
        error_log("key: $key, value: $value");
        $this->checked[$key] = $value;
    }

    public function render()
    {
        return view('livewire.test-script', [
            'checked' => $this->checked,
        ]);
    }
}
