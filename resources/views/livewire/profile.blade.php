@section('title', 'Profil')

@push("css")
    @vite('resources/sass/profile.scss')
@endpush

<div class="row mx-auto col-12 col-md-9 col-xxl-6 profile-box">
    <div class="left-panel col-3">
        <div class="avatar-box">
            <img src="{{ asset("images/user.png") }}" alt="avatar" class="avatar">
        </div>
        <div class="info-box">
            <div class="name">John Doe</div>
            <h4>
                Dane kontaktowe
            </h4>
            <div class="email">johndoe@gmail.com</div>
            <div class="phone-number">081234567890</div>
            <h4>
                Adres
            </h4>
            <div class="address">
                Lublin 20-001,<br> ul. Lubartowska 1
            </div>
        </div>
    </div>
    <div class="reservations-box col-9">
        <div class="top-buttons-box">
            <button class="active">
                Aktywna rezerwacja
            </button>
            <button>
                Poprzednie rezerwacje
            </button>
            <button>
                Anulowane rezerwacje
            </button>
        </div>
        <div class="content-box">
            <h3>
                Aktywna rezerwacja
            </h3>
        </div>
    </div>
</div>
