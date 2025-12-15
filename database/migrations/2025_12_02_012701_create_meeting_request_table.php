<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('meeting_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('title');
        $table->text('description')->nullable();
        $table->dateTime('start');
        $table->dateTime('end');
        $table->enum('status', ['pending','approved','rejected'])->default('pending');
        $table->timestamps();
        });
    }

};
