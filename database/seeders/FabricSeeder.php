<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fabric;
use App\Models\Category; // Sesuai model Anda
use App\Models\Supplier; // Sesuai model Anda
use Illuminate\Support\Facades\DB;

class FabricSeeder extends Seeder
{
    public function run()
    {
        // 1. Pastikan Kategori & Supplier Ada (Sesuai kode Anda)
        // Kita pakai firstOrCreate agar tidak error jika dijalankan berulang
        $catCotton = Category::firstOrCreate(['name' => 'Cotton']);
        $catSilk   = Category::firstOrCreate(['name' => 'Silk']);
        $catWool   = Category::firstOrCreate(['name' => 'Wool']);

        $supA = Supplier::firstOrCreate(['name' => 'PT. Tekstil Maju']);

        // 2. DATA DUMMY: STRUKTUR AGAR DROPDOWN BERTINGKAT JALAN
        // Format: [ 'Kategori' => [ 'Nama Kain' => ['Warna1', 'Warna2'] ] ]
        
        $fabricCatalog = [
            // Kategori Cotton
            'Cotton' => [
                'Cotton Combed 30s' => ['Jet Black', 'Navy Blue', 'Maroon', 'White Solid'],
                'Cotton Bamboo'     => ['Mist Grey', 'Sage Green', 'Soft Pink'],
            ],
            // Kategori Silk
            'Silk' => [
                'Satin Velvet'      => ['Gold', 'Silver', 'Dusty Pink', 'Emerald Green'],
                'Maxmara'           => ['Broken White', 'Champagne'],
            ],
            // Kategori Wool
            'Wool' => [
                'Wool Cashmere'     => ['Charcoal', 'Brown', 'Black'],
            ],
        ];

        // 3. Loop untuk Insert ke Database
        foreach ($fabricCatalog as $categoryName => $fabrics) {
            
            // Ambil ID Kategori berdasarkan nama di array
            $category = Category::where('name', $categoryName)->first();

            foreach ($fabrics as $fabricName => $colors) {
                foreach ($colors as $color) {
                    Fabric::create([
                        'category_id'     => $category->id, // Hubungkan ke ID Kategori
                        'supplier_id'     => $supA->id,     // Hubungkan ke ID Supplier
                        'name'            => $fabricName,   // INI MASUK KE DROPDOWN TIPE (Dropdown 1)
                        'color'           => $color,        // INI MASUK KE DROPDOWN WARNA (Dropdown 2)
                        'stock_meter'     => rand(50, 200),
                        'price_per_meter' => rand(25000, 75000), // Harga acak
                        // Tambahkan kolom lain jika ada, misal: 'description' => '...'
                    ]);
                }
            }
        }
    }
}