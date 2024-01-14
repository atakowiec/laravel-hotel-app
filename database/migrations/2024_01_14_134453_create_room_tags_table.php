<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_tags', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('room_id')->nullable();
            $table->integer('tag_id')->nullable();
            $table->timestamps();

            // Definicje kluczy obcych
            $table->foreign('tag_id')->references('id')->on('available_tags');
            $table->foreign('room_id')->references('id')->on('rooms');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_tags');
    }
};
