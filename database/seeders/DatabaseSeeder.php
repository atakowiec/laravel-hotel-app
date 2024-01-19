<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Reservation;
use App\Models\RoomRating;
use App\Models\RoomTags;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
//        Address::factory(100)->create();
//        User::factory(100)->create();
//        Reservation::factory(1000)->create();
        RoomRating::factory(2000)->create();
        RoomTags::factory(200)->create();
    }
}
