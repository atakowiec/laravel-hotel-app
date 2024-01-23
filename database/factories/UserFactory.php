<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nickname' => $this->faker->userName,
            'password' => bcrypt("1"),
            'email' => $this->faker->unique()->safeEmail,
            'admin' => 0,
            'phone_number' => $this->faker->regexify('[0-9]{9}'),
            'address_id' => $this->faker->numberBetween(1, 100)
        ];
    }
}
