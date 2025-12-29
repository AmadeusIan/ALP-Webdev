<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venue;
use App\Models\VenueArea;
use App\Models\VenueRoom;

class VenueSeeder extends Seeder
{
    public function run()
    {
        // ==========================================
        // 1. THE WESTIN SURABAYA
        // ==========================================
        $westin = Venue::create([
            'name'    => 'The Westin Surabaya',
            'address' => 'Pakuwon Mall, Jl. Puncak Indah Lontar No.2, Surabaya',
        ]);

        // Area 1: Grand Ballroom Floor
        $westinBallroom = $westin->areas()->create(['name' => 'Grand Ballroom Floor']);
        
        $westinBallroom->rooms()->createMany([
            [
                'name' => 'Grand Ballroom 1', 
                'images' => [
                    'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=800',
                    'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=800'
                ]
            ],
            [
                'name' => 'Grand Ballroom 2', 
                'images' => ['https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=800']
            ],
            [
                'name' => 'Grand Ballroom 3', 
                'images' => ['https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=800']
            ],
        ]);

        // Area 2: Meeting Floor
        $westinMeeting = $westin->areas()->create(['name' => 'Meeting & Conference Floor']);

        $westinMeeting->rooms()->createMany([
            ['name' => 'Galaxy Room' ],
            ['name' => 'Star Room'],
        ]);


        // ==========================================
        // 2. HOTEL MULIA SENAYAN
        // ==========================================
        $mulia = Venue::create([
            'name'    => 'Hotel Mulia Senayan',
            'address' => 'Jl. Asia Afrika, Senayan, Jakarta Pusat',
        ]);

        // Area 1: Main Tower
        $muliaMain = $mulia->areas()->create(['name' => 'Main Tower']);

        $muliaMain->rooms()->createMany([
            [
                'name' => 'Vanda Ballroom', 
                'images' => [
                    'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=800',
                    'https://images.unsplash.com/photo-1561501900-3701fa6a0864?q=80&w=800'
                ]
            ],
            [
                'name' => 'Narcissus Room', 
                'images' => ['https://images.unsplash.com/photo-1431540015161-0bf868a2d407?q=80&w=800']
            ],
        ]);


        // ==========================================
        // 3. PLATARAN HUTAN KOTA
        // ==========================================
        $plataran = Venue::create([
            'name'    => 'Plataran Hutan Kota',
            'address' => 'Gelora Bung Karno, Jl. Jend. Sudirman, Jakarta',
        ]);

        // Area 1: Outdoor Area
        $plataranOutdoor = $plataran->areas()->create(['name' => 'Garden Area']);

        $plataranOutdoor->rooms()->createMany([
            [
                'name' => 'Tiga Dari', 
                'images' => [
                    'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?q=80&w=800',
                    'https://images.unsplash.com/photo-1510076857177-7470076d4098?q=80&w=800'
                ]
            ],
            [
                'name' => 'Pidari Lounge', 
                'images' => ['https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=800']
            ]
        ]);


        // ==========================================
        // 4. GRIYA BABATAN MUKTI
        // ==========================================
        $griya = Venue::create([
            'name'    => 'Griya Babatan Mukti',
            'address' => 'Jl. Griya Babatan Mukti Blok P No.20, Wiyung, Surabaya',
        ]);

        // Area 1: Main Building
        $griyaMain = $griya->areas()->create(['name' => 'Main Building']);

        $griyaMain->rooms()->createMany([
            [
                'name' => 'Main Hall', 
                'images' => [
                    'https://images.unsplash.com/photo-1519225421980-715cb0202128?q=80&w=800'
                ]
            ],
            [
                'name' => 'Outdoor Terrace', 
                'images' => [
                    'https://images.unsplash.com/photo-1533090161767-e6ffed986c88?q=80&w=800'
                ]
            ]
        ]);
    }
}