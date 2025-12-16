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
        Schema::create('review_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade');
        $table->unsignedTinyInteger('rating');
        $table->text('comment')->nullable();
        $table->timestamps(); // created_at + updated_at
        $table->unique('order_item_id');

    });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_items');
    }
};
