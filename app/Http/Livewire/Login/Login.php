<?php

namespace App\Http\Livewire\Login;

use App\Traits\WithInputErrorClass;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Login extends Component
{
    use WithInputErrorClass;

    public $email;
    public $password;

    protected $rules = [
        'email' => 'required',
        'password' => 'required',
    ];

    protected $messages = [
        'email.required' => 'Email jest wymagany.',
        'password.required' => 'Hasło jest wymagane.',
    ];

    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function login(): void
    {
        $this->validate();

        if (auth()->attempt(['email' => $this->email, "password" => $this->password])) {
            request()->session()->regenerate();

            redirect("/")->with(["message" => "Zalogowano pomyślnie"]);
            return;
        }

        $this->addError("login", "Niepoprawne dane");
    }

    public function render(): View
    {
        return view('livewire.login.login');
    }
}
