<?php

namespace App\Http\Livewire;

use App\Models\Address;
use App\Models\Reservation;
use App\Traits\WithFlashMessage;
use App\Traits\WithFloatingConfirmation;
use Illuminate\Database\Eloquent\Collection;
use \Illuminate\Contracts\View\View;
use Livewire\Component;

class Profile extends Component
{
    use WithFlashMessage;
    use WithFloatingConfirmation {
        showFloatingComponent as protected traitShowFloatingComponent;
        hideFloatingComponent as protected traitHideFloatingComponent;
    }

    public Collection $pastReservations;
    public ?Reservation $nextReservation;
    public Collection $currentReservations;
    public Collection $futureReservations;
    public string $oldPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';
    public bool $hasAnyReservations = true;

    public int $rating = 0;
    public string $comment = '';

    protected array $rules = [
        'oldPassword' => 'required',
        'newPassword' => 'required|min:8',
        'newPasswordConfirmation' => 'required|same:newPassword',
    ];

    protected array $commentRules = [
        'rating' => ['required', 'integer', 'min:1', 'max:5'],
        'comment' => ['nullable', 'string', 'min:10', 'max:1000'],
    ];

    protected array $messages = [
        'oldPassword.required' => 'Aktualne hasło jest wymagane',
        'newPassword.required' => 'Nowe hasło jest wymagane',
        'newPassword.min' => 'Nowe hasło musi mieć co najmniej 8 znaków',
        'newPasswordConfirmation.required' => 'Potwierdzenie nowego hasła jest wymagane',
        'newPasswordConfirmation.same' => 'Hasła muszą być takie same',
        'rating.required' => 'Ocena jest wymagana',
        'rating.integer' => 'Ocena musi być liczbą',
        'rating.min' => 'Ocena jest wymagana',
        'rating.max' => 'Ocena musi być mniejsza od 6',
        'comment.string' => 'Komentarz musi być tekstem',
        'comment.min' => 'Komentarz musi mieć co najmniej 10 znaków',
        'comment.max' => 'Komentarz musi mieć co najwyżej 1000 znaków',
    ];

    public function cancelReservation($reservationId): void
    {
        if(!auth()->check()) {
            $this->addFlashMessage('Musisz być zalogowany, aby anulować rezerwację');
            return;
        }

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
        if(!auth()->check()) {
            $this->addFlashMessage('Musisz być zalogowany, aby zmienić hasło');
            return;
        }

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

        $this->hideFloatingComponent('change_password');
    }

    public function updated($propertyName): void
    {
        if ($propertyName === 'rating' || $propertyName === 'comment') {
            $this->validateOnly($propertyName, $this->commentRules);
            return;
        }

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
        if(!auth()->check()) {
            $this->addFlashMessage('Musisz być zalogowany, aby usunąć konto');
            return;
        }

        $address_id = auth()->user()->address_id;

        auth()->user()->delete();
        Address::where('id', $address_id)->delete();
        auth()->logout();
        redirect()->to('/');
    }

    public function hideFloatingComponent($id): void
    {
        $this->traitHideFloatingComponent($id);

        if ($id != "review") return;
        $this->rating = 0;
        $this->comment = '';
        $this->resetErrorBag();
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

    public function addReview(): void
    {
        if(!auth()->check()) {
            $this->addFlashMessage('Musisz być zalogowany, aby dodać opinię');
            return;
        }

        $this->validate($this->commentRules);

        $reservationId = $this->getParam('review', 0);
        if ($reservationId == 0) {
            $this->hideFloatingComponent('review');
            $this->addFlashMessage('Nie udało się dodać opinii');
            return;
        }

        $reservation = Reservation::find($reservationId);
        if (!$reservation) {
            $this->hideFloatingComponent('review');
            $this->addFlashMessage('Nie udało się dodać opinii');
            return;
        }

        if ($reservation->date_to > now()->format('Y-m-d')
            || $reservation->user_id != auth()->user()->id
            || $reservation->room->ratings()->where('user_id', auth()->user()->id)->count() > 0
            || $reservation->date_to > now()->format('Y-m-d')) {

            $this->hideFloatingComponent('review');
            $this->addFlashMessage('Nie udało się dodać opinii');
            return;
        }

        $reservation->room->ratings()->create([
            'value' => $this->rating,
            'comment' => $this->comment,
            'user_id' => auth()->user()->id
        ]);

        $this->rating = 0;
        $this->comment = '';
        $this->hideFloatingComponent('review');
    }
}
