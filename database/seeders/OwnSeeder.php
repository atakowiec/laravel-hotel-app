<?php

use App\Models\Room;

function seedRoomTags(): void
{
    $rooms = Room::all();

    foreach ($rooms as $room) {
        $x = rand(15, 30);
        $max = floor($x * $x / 60);

        for ($i = 0; $i < $max; $i++) {
            do {
                $tag = rand(1, 30);
            } while ($room->tags()->where('tag_id', $tag)->exists());

            $room->tags()->attach($tag);
        }

        $room->save();
    }
}

function seedRoomRating(): void
{
    $rooms = Room::all();

    foreach ($rooms as $room) {
        $target = rand(2, 5);
        $max = rand(10, 60);
        $used = [];

        for ($i = 0; $i < $max; $i++) {
            $user = rand(1, 100);
            while (in_array($user, $used)) {
                $user = rand(1, 100);
            }
            $used[] = $user;
            $value = min(5, max(1, $target + rand(-2, 2)));
            $value = round($value * 2) / 2.0;

            $room->ratings()->create([
                'user_id' => $user,
                'value' => $value,
                'comment' => rand(0, 1) ? null : fake()->text(rand(50, 200))
            ]);
        }
    }
}

function seedReservations1(): void
{
    $rooms = Room::all();

    foreach ($rooms as $room) {
        error_log("Room " . $room->id);
        $max = rand(20, 100);

        for ($i = 0; $i < $max; $i++) {
            $from = fake()->dateTimeBetween('-2 year', '-1 year');
            $to = fake()->dateTimeBetween($from, 'now');

            $days = strtotime($to->format('Y-m-d')) - strtotime($from->format('Y-m-d'));
            $days = $days / (60 * 60 * 24);

            $room->reservations()->create([
                'user_id' => rand(1, 100),
                'room_id' => $room->id,
                'date_from' => $from,
                'date_to' => $to,
                'total_cost' => $room->price * $days,
                'cancelled' => rand(0, 6) == 10
            ]);
        }

        $room->save();
    }
}
