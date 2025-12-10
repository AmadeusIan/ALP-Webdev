<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil satu user untuk dikasih notifikasi (User pertama yang ditemukan)
        $user = User::first();

        // Jaga-jaga kalau database user masih kosong
        if (!$user) {
            $this->command->info('Tabel users kosong. Jalankan migrate --seed atau buat user dulu.');
            return;
        }

        // Hapus notifikasi lama milik user ini (biar bersih saat di-seed ulang)
        Notification::where('user_id', $user->id)->delete();

        // 2. Buat Data Dummy
        $notifications = [
            [
                'title'   => 'Pesanan Disetujui',
                'message' => 'Hore! Pesanan kain #ORD-2025-001 kamu sudah disetujui admin. Silakan lanjut ke pembayaran.',
                'type'    => 'order_info', // Tipe Order (Ikon Hitam)
                'is_read' => false,        // Belum dibaca (Background Putih Terang)
                'time'    => Carbon::now()->subMinutes(5), // 5 menit lalu
            ],
            [
                'title'   => 'Pembayaran Diterima',
                'message' => 'Terima kasih! Pembayaran sebesar Rp 500.000 telah kami terima. Barang sedang diproses.',
                'type'    => 'order_info',
                'is_read' => false,        // Belum dibaca
                'time'    => Carbon::now()->subHours(2), // 2 jam lalu
            ],
            [
                'title'   => 'Selamat Datang di Kana Covers!',
                'message' => 'Terima kasih telah bergabung. Lengkapi profilmu untuk memudahkan proses penyewaan.',
                'type'    => 'info',       // Tipe Umum (Ikon Abu)
                'is_read' => true,         // Sudah dibaca (Agak transparan)
                'time'    => Carbon::now()->subDays(1), // 1 hari lalu
            ],
            [
                'title'   => 'Promo Akhir Tahun',
                'message' => 'Dapatkan diskon 20% untuk penyewaan kain velvet khusus bulan Desember.',
                'type'    => 'promo',      // Tipe Lain (Ikon Abu)
                'is_read' => true,         // Sudah dibaca
                'time'    => Carbon::now()->subDays(3), // 3 hari lalu
            ],
        ];

        // 3. Masukkan ke Database
        foreach ($notifications as $data) {
            Notification::create([
                'user_id'    => $user->id,
                'title'      => $data['title'],
                'message'    => $data['message'],
                'type'       => $data['type'],
                'is_read'    => $data['is_read'],
                'created_at' => $data['time'], // Kita manipulasi waktunya biar bervariasi
            ]);
        }
    }
}