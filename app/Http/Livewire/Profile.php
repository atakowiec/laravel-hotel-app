<?php

namespace App\Http\Livewire;

use App\Models\Address;
use App\Models\Reservation;
use App\Traits\WithFloatingConfirmation;
use Illuminate\Database\Eloquent\Collection;
use \Illuminate\Contracts\View\View;
use Livewire\Component;

class Profile extends Component
{
    use WithFloatingConfirmation {
        showFloatingComponent as protected traitShowFloatingComponent;
    }

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

    public function cancelReservation($reservationId): void
    {
        error_log("cancelReservation has been called with id: " . $reservationId);

        $reservation = Reservation::find($reservationId);
        if (!$reservation || $reservation->cancelled) {
            return;
        }

        $reservation->update([
            'cancelled' => true
        ]);

        $this->mount();
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

    public function changePassword(): void
    {
        $this->validate();

        if (!auth()->attempt(['email' => auth()->user()->email, 'password' => $this->oldPassword])) {
            $this->oldPassword = '';
            $this->addError('oldPassword', 'Podane aktualne hasło jest nieprawidłowe');
            return;
        }

        auth()->user()->update([
            'password' => bcrypt($this->newPassword)
        ]);

        $this->oldPassword = '';
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);

        if ($propertyName === 'newPassword' && isset($this->newPasswordConfirmation)) {
            if ($this->newPassword != $this->newPasswordConfirmation) {
                $this->addError('newPasswordConfirmation', 'Hasła muszą być takie same.');
            } else {
                $this->resetErrorBag('newPasswordConfirmation');
            }
        }
    }

    private function removeAccount(): void
    {
        $address_id = auth()->user()->address_id;

        auth()->user()->delete();
        Address::where('id', $address_id)->delete();
        auth()->logout();
        redirect()->to('/');
    }

    public function showFloatingComponent($id, ...$params): void
    {
        $this->traitShowFloatingComponent($id, ...$params);

        if ($id != "change_password") return;
        $this->oldPassword = '';
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        $this->resetErrorBag();
    }

    public function render(): View
    {
        return view('livewire.profile');
    }

    public function onConfirm($id, ...$params): void
    {
        if ($id == "remove_account") {
            $this->removeAccount();
            return;
        }


        if ($id != "cancel_reservation") return;
        if (count($params) != 1) return;

        $this->cancelReservation($params[0]);
    }
}
