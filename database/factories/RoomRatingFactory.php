<?php

namespace Database\Factories;

use App\Models\RoomRating;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RoomRatingFactory extends Factory
{
    protected $model = RoomRating::class;

    public function definition(): array
    {
        return [
            'room_id' => $this->faker->numberBetween(1, 15),
            'user_id' => $this->faker->numberBetween(1, 100),
            'value' => 0.5 * $this->faker->numberBetween(1, 10),
            'comment' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
