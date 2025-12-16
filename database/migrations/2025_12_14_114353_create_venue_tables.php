<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('venue_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained('venues')->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('venue_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_area_id')->constrained('venue_areas')->onDelete('cascade');
            $table->string('name'); // Cth: Grand Ballroom 1
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('venue_rooms');
        Schema::dropIfExists('venue_areas');
        Schema::dropIfExists('venues');
    }
};
