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
    Schema::dropIfExists('schedules');
}

public function down()
{
    Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
        $table->date('date');
        $table->time('start_time');
        $table->time('end_time');
        $table->text('purpose')->nullable();
        $table->timestamp('created_at')->useCurrent();
    });
}

};
