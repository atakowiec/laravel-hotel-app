<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'city' => $this->faker->city,
            'zip_code' => $this->faker->postcode,
            'street' => $this->faker->streetAddress,
            'building_number' => $this->faker->buildingNumber,
            'flat_number' => $this->faker->optional()->randomNumber(2),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
