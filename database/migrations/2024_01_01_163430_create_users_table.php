<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('address', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('city')->nullable();
            $table->string('zip_code', 6)->nullable();
            $table->string('street')->nullable();
            $table->string('building_number', 16)->nullable();
            $table->string('flat_number', 16)->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('nickname', 16)->notnull();
            $table->string('password', 512)->notnull();
            $table->string('email', 128)->nullable();
            $table->boolean('admin')->default(0)->notnull();
            $table->string('phone_number', 32)->nullable();
            $table->integer('address_id')->nullable();
            $table->timestamps();

            // Definicje klucza obcego
            $table->foreign('address_id')->references('id')->on('address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('address');
    }
};
