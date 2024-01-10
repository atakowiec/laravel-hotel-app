<?php

namespace App\Http\Livewire\Login;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class Register extends Component
{
    public string $nickname;
    public string $email;
    public string $password;
    public string $password_confirmation;
    public bool $terms = false;

    // polish messages
    protected array $messages = [
        'nickname.required' => 'Nazwa uzytkownika jest wymagane.',
        'nickname.min' => 'Nazwa uzytkownika musi mieć minimum :min znaki.',
        'nickname.max' => 'Nazwa uzytkownika może mieć maksymalnie :max znaków.',
        'nickname.unique' => 'Nazwa uzytkownika jest już zajęty.',
        'email.required' => 'Email jest wymagany.',
        'email.email' => 'Email musi być poprawny.',
        'email.unique' => 'Email jest już zajęty.',
        'password.required' => 'Hasło jest wymagane.',
        'password.min' => 'Hasło musi mieć minimum :min znaki.',
        'password.confirmed' => 'Hasła muszą być takie same.',
        'password_confirmation.same' => 'Hasła muszą być takie same.',
        'password_confirmation.required' => 'Potwierdzenie hasła jest wymagane.',
        'terms.required' => 'Musisz zaakceptować regulamin.',
        'terms.accepted' => 'Musisz zaakceptować regulamin.',
    ];

    protected array $rules = [
        'nickname' => ['required', 'min:3', 'max:20', 'unique:users'],
        'email' => ['required', 'email', 'unique:users'],
        'password' => ['required', 'min:8'],
        'password_confirmation' => ['required', 'same:password'],
        'terms' => ['accepted'],
    ];

    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName);

        if ($propertyName === 'password' && isset($this->password_confirmation)) {
            if ($this->password != $this->password_confirmation) {
                $this->addError('password_confirmation', 'Hasła muszą być takie same.');
            } else {
                $this->resetErrorBag('password_confirmation');
            }
        }
    }

    public function register(): void
    {
        $this->validate();

        $user = User::create([
            "nickname" => $this->nickname,
            "password" => bcrypt($this->password),
            "email" => $this->email,
            "permission" => 0
        ]);

        auth()->login($user);

        redirect('/')->with("message", "Konto utworzone pomyślnie");
    }

    public function getErrorClass($field): string
    {
        return $this->getErrorBag()->has($field) ? 'class=is-invalid' : '';
    }

    public function render(): View
    {
        return view('livewire.login.register');
    }
}
