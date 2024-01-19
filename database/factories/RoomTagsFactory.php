<?php

namespace Database\Factories;

use App\Models\RoomTags;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RoomTagsFactory extends Factory
{
    protected $model = RoomTags::class;

    public function definition(): array
    {
        return [
            'room_id' => $this->faker->numberBetween(1, 15),
            'tag_id' => function () {
                return $this->faker->numberBetween(1, 29);
            },
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
