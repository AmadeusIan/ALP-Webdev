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
    Schema::create('inventory_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('fabric_id')->constrained('fabrics')->onDelete('cascade');
        $table->string('change_type', 50); // restock, sale, adjustment
        $table->decimal('change_amount', 10, 2);
        $table->text('note')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_log');
    }
};
