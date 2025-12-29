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
        Schema::table('orders', function (Blueprint $table) {
            $table->text('add_on_detail')->nullable()->after('note');
        });

        Schema::table('order_items', function (Blueprint $table) {

            $table->foreignId('venue_room_id')->nullable()->after('fabric_id')->constrained('venue_rooms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('add_on_detail');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['venue_room_id']);
            $table->dropColumn('venue_room_id');
        });
    }
};
