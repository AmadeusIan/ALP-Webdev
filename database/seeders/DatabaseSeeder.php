<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ==========================================
        // 1. USERS (Pengguna)
        // ==========================================
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin Tekstil',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $budiId = DB::table('users')->insertGetId([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sitiId = DB::table('users')->insertGetId([
            'name' => 'Siti Aminah',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ==========================================
        // 2. SUPPLIERS (Pemasok)
        // ==========================================
        $supplier1 = DB::table('suppliers')->insertGetId([
            'name' => 'CV. Tenun Jaya Bandung',
            'contact' => '0812-3333-4444',
            'address' => 'Jl. Cigondewah No. 10, Bandung',
            'created_at' => now(),
        ]);

        $supplier2 = DB::table('suppliers')->insertGetId([
            'name' => 'PT. Sutra Abadi Solo',
            'contact' => '0812-5555-6666',
            'address' => 'Jl. Klewer No. 5, Solo',
            'created_at' => now(),
        ]);

        // ==========================================
        // 3. CATEGORIES (Kategori Kain)
        // ==========================================
        $catKatun = DB::table('categories')->insertGetId(['name' => 'Katun (Cotton)', 'description' => 'Bahan sejuk dan menyerap keringat']);
        $catSutra = DB::table('categories')->insertGetId(['name' => 'Sutra (Silk)', 'description' => 'Bahan premium halus dan berkilau']);
        $catRayon = DB::table('categories')->insertGetId(['name' => 'Rayon', 'description' => 'Bahan jatuh dan dingin']);

        // ==========================================
        // 4. FABRICS (Produk Kain) & INVENTORY LOG
        // ==========================================
        
        // Produk 1: Katun Toyobo
        $fabric1 = DB::table('fabrics')->insertGetId([
            'category_id' => $catKatun,
            'supplier_id' => $supplier1,
            'name' => 'Katun Toyobo Royal Mix',
            'color' => 'Navy Blue',
            'material' => '100% Cotton Premium',
            'price_per_meter' => 45000,
            'stock_meter' => 100,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        // Log Stok Awal Produk 1
        DB::table('inventory_logs')->insert([
            'fabric_id' => $fabric1,
            'change_type' => 'initial',
            'change_amount' => 100,
            'note' => 'Stok awal pembukaan toko',
            'created_at' => now(),
        ]);

        // Produk 2: Sutra Batik
        $fabric2 = DB::table('fabrics')->insertGetId([
            'category_id' => $catSutra,
            'supplier_id' => $supplier2,
            'name' => 'Sutra Batik Tulis Halus',
            'color' => 'Coklat Emas',
            'material' => 'Sutra Asli',
            'price_per_meter' => 150000,
            'stock_meter' => 50,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        // Log Stok Awal Produk 2
        DB::table('inventory_logs')->insert([
            'fabric_id' => $fabric2,
            'change_type' => 'initial',
            'change_amount' => 50,
            'note' => 'Stok awal',
            'created_at' => now(),
        ]);

        // ==========================================
        // 5. SCHEDULES (Jadwal Janji Temu)
        // ==========================================
        // Admin membuat jadwal available
        $schedule1 = DB::table('schedules')->insertGetId([
            'user_id' => $adminId, // Staff/Admin yg jaga
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'purpose' => 'Konsultasi pembelian grosir',
            'created_at' => now(),
        ]);

        // ==========================================
        // 6. MEETING REQUESTS (Permintaan Temu)
        // ==========================================
        // Siti request meeting untuk jadwal di atas
        DB::table('meeting_requests')->insert([
            'user_id' => $sitiId,
            'schedule_id' => $schedule1,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        // ==========================================
        // 7. ORDERS (Transaksi)
        // ==========================================
        
        // --- Order 1 (Budi membeli Katun) - Status: Completed ---
        $order1Id = DB::table('orders')->insertGetId([
            'user_id' => $budiId,
            'order_date' => Carbon::yesterday(),
            'total_price' => 225000, // 45.000 x 5 meter
            'status' => 'completed',
        ]);

        // Order Item untuk Order 1
        $orderItem1 = DB::table('order_items')->insertGetId([
            'order_id' => $order1Id,
            'fabric_id' => $fabric1,
            'quantity_meter' => 5,
            'total_price' => 225000,
        ]);
        
        // Kurangi stok karena ada order (Manual log simulation)
        DB::table('fabrics')->where('id', $fabric1)->decrement('stock_meter', 5);
        DB::table('inventory_logs')->insert([
            'fabric_id' => $fabric1, 'change_type' => 'sale', 'change_amount' => -5, 'note' => 'Order #'.$order1Id,
        ]);


        // --- Order 2 (Siti membeli Sutra) - Status: Pending ---
        $order2Id = DB::table('orders')->insertGetId([
            'user_id' => $sitiId,
            'order_date' => now(),
            'total_price' => 300000, // 150.000 x 2 meter
            'status' => 'pending',
        ]);
        
        $orderItem2 = DB::table('order_items')->insertGetId([
            'order_id' => $order2Id,
            'fabric_id' => $fabric2,
            'quantity_meter' => 2,
            'total_price' => 300000,
        ]);
        // Stok belum dikurangi karena status masih pending (tergantung logika bisnis, di sini kita anggap pending belum mengurangi stok fisik)


        // ==========================================
        // 8. REVIEWS (Ulasan)
        // ==========================================

        // A. Review Toko (Budi puas dengan toko)
        DB::table('reviews')->insert([
            'user_id' => $budiId,
            'title' => 'Pelayanan Mantap',
            'rating' => 5,
            'comment' => 'Admin ramah dan pengiriman cepat ke Surabaya.',
            'created_at' => now(),
        ]);

        // B. Review Item (Budi mereview barang yang dibelinya di Order 1)
        DB::table('review_items')->insert([
            'order_item_id' => $orderItem1,
            'title' => 'Bahannya adem!',
            'rating' => 5,
            'comment' => 'Sesuai deskripsi, toyobo asli, warnanya solid.',
            'created_at' => now(),
        ]);

        // ==========================================
        // 9. NOTIFICATIONS
        // ==========================================
        DB::table('notifications')->insert([
            'user_id' => $budiId,
            'title' => 'Pesanan Selesai',
            'message' => 'Pesanan kain katun Anda telah sampai.',
            'type' => 'info',
            'is_read' => false,
            'created_at' => now(),
        ]);
    }
}