<?php

namespace App\Http\Livewire;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Profile extends Component
{
    public Collection $pastReservations;
    public ?Reservation $nextReservation;
    public Collection $currentReservations;
    public Collection $futureReservations;
    public string $oldPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';
    public bool $hasAnyReservations = true;

    protected array $rules = [
        'oldPassword' => 'required',
        'newPassword' => 'required|min:8',
        'newPasswordConfirmation' => 'required|same:newPassword',
    ];

    protected array $messages = [
        'oldPassword.required' => 'Aktualne hasło jest wymagane',
        'newPassword.required' => 'Nowe hasło jest wymagane',
        'newPassword.min' => 'Nowe hasło musi mieć co najmniej 8 znaków',
        'newPasswordConfirmation.required' => 'Potwierdzenie nowego hasła jest wymagane',
        'newPasswordConfirmation.same' => 'Hasła muszą być takie same'
    ];

    public function cancel_reservation($reservationId): void
    {

    }

    public function mount(): void
    {
        $all = Reservation::where('user_id', auth()->user()->id)
            ->orderBy('date_from')->get();

        $today = now()->format('Y-m-d');
        $this->hasAnyReservations = $all->count() > 0;
        $this->pastReservations = $all->where('date_to', '<', $today);
        $this->futureReservations = $all->where('date_from', '>', $today);
        $this->currentReservations = $all->where('date_from', '<=', $today)->where('date_to', '>=', $today);
        if ($this->currentReservations->count() > 0) {
            $this->nextReservation = null;
            return;
        }

        $this->nextReservation = $this->futureReservations->first();

        if ($this->nextReservation) {
            $this->futureReservations = $this->futureReservations->filter(function ($value, $key) {
                return $value->id != $this->nextReservation->id;
            });
        }
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function render(): View
    {
        return view('livewire.profile');
    }
}
