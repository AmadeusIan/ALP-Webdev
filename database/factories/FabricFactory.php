<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Supplier;
use App\Models\Fabric;
use Illuminate\Database\Eloquent\Factories\Factory;

class FabricFactory extends Factory
{
    protected $model = Fabric::class;

    public function definition(): array
    {

        $imageUrls = [
            '/fabrics/kain.jpg',
            '/fabrics/kain1.jpg',
            '/fabrics/kain2.jpg',
            '/fabrics/kain3.jpg',
        ];

        $categoryNames = [
            'Wool', 'Silk', 'Cotton'
        ];

        $randomCatName = $this->faker->randomElement($categoryNames);

        $category = Category::firstOrCreate(['name' => $randomCatName]);

        $supplier = Supplier::firstOrCreate(['name' => 'PT. Tekstil Maju']);

        return [
            'name' => $this->faker->words(2, true) . ' Fabric',
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'color' => $this->faker->safeColorName(),
            'material' => $this->faker->words(1, true),
            'price_per_meter' => $this->faker->numberBetween(1000000, 5000000),
            'stock_meter' => $this->faker->numberBetween(1, 200),
            'image' => $this->faker->randomElement($imageUrls),
            'description' => $this->faker->paragraph(),
        ];
    }
}