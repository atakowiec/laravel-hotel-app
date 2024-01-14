<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('room_rating', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('room_id');
            $table->integer('user_id');
            $table->double('value', 2, 1)->nullable();
            $table->timestamps();

            // Definicje kluczy obcych
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('room_rating');
    }
};
