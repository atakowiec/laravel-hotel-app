<?php

namespace App\Http\Livewire\Login;

use App\Models\Address;
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
    public string $phone_number;
    public string $city;
    public string $zip_code;
    public string $street;
    public string $building_number;
    public string $flat_number;

    public bool $nextStage = false;

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
        'phone_number' => ['required', 'regex:/^\d{3}[-\s]?\d{3}[-\s]?\d{3}$/', 'unique:users']
    ];

    protected array $nextStageRules = [
        'city' => ['required', 'not_regex:/^(\d)$/'],
        'zip_code' => ['required', 'regex:/^([0-9]{2}-[0-9]{3})$/'],
        'street' => ['required'],
        'building_number' => ['required', 'regex:/^(\d+[a-zA-Z]{0,1})$/'],
        'flat_number' => ['regex:/^\d+[a-zA-Z]{0,1}$/']
    ];

    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName, $this->nextStage ? $this->nextStageRules : $this->rules);

        if ($propertyName === 'password' && isset($this->password_confirmation)) {
            if ($this->password != $this->password_confirmation) {
                $this->addError('password_confirmation', 'Hasła muszą być takie same.');
            } else {
                $this->resetErrorBag('password_confirmation');
            }
        }
    }

    public function runNextStage(): void
    {
        $this->validate($this->rules);

        $this->nextStage = true;
    }

    public function register()
    {
        $this->validate($this->nextStageRules);

        try {
            $this->validate($this->rules);
        } catch (\Throwable $th) {
            $this->nextStage = false;
            return;
        }

        $address = Address::create([
            "city" => $this->city,
            "street" => $this->street,
            "zip_code" => $this->zip_code,
            "building_number" => $this->building_number,
            "flat_number" => $this->flat_number
        ]);

        $user = User::create([
            "nickname" => $this->nickname,
            "password" => bcrypt($this->password),
            "email" => $this->email,
            "permission" => 0,
            "phone_number" => $this->getPreparedPhoneNumber(),
            "address_id" => $address->id
        ]);

        auth()->login($user);

        redirect('/')->with("message", "Konto utworzone pomyślnie");
    }

    private function getPreparedPhoneNumber()
    {
        $stage1 = str_replace("-", "", $this->phone_number);
        return str_replace(" ", "", $stage1);
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
