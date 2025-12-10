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
    Schema::create('fabrics', function (Blueprint $table) {
        $table->id();
        // Relasi FK
        $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
        $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
        
        $table->string('name');
        $table->string('color', 100)->nullable();
        $table->string('material')->nullable();
        $table->decimal('price_per_meter', 10, 2);
        $table->decimal('stock_meter', 10, 2)->default(0);
        $table->image_url('image')->nullable();
        $table->string('description',255)->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fabric');
    }
};
