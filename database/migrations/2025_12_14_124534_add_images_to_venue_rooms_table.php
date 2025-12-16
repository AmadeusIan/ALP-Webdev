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
        Schema::table('venue_rooms', function (Blueprint $table) {
            $table->json('images')->nullable()->after('name'); 
        });
    }

    public function down()
    {
        Schema::table('venue_rooms', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
