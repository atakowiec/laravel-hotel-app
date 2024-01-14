<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'room_id' => $this->faker->numberBetween(1, 8),
            'date_from' => $this->faker->date,
            'date_to' => $this->faker->date,
            'total_cost' => $this->faker->randomFloat(2, 50, 500),
            'cancelled' => $this->faker->boolean(10), // 10% szansy na anulowanie
        ];
    }
}
