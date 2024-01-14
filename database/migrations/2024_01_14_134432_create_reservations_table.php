<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('user_id');
            $table->integer('room_id');
            $table->date('date_from');
            $table->date('date_to');
            $table->double('total_cost', 11, 2);
            $table->tinyInteger('cancelled')->default(0);
            $table->timestamps();

            // Definicje kluczy obcych
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('reservations');
    }
};
