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
        // 1. Buat Hotel Westin
        $westin = Venue::create([
            'name' => 'The Westin Surabaya',
            'address' => 'Pakuwon Mall, Surabaya',
        ]);


        // 2. Buat Area: Ballroom
        $ballroomArea = VenueArea::create([
            'venue_id' => $westin->id,
            'name' => 'Grand Ballroom',
        ]);

        // 3. Buat Rooms di dalam Ballroom
        // Contoh pengisian data dengan gambar dummy (URL placeholder)
        VenueRoom::create([
            'venue_area_id' => $ballroomArea->id,
            'name' => 'Grand Ballroom 1',
            'images' => [
                'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=800',
                'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=800'
            ]
        ]);
        // ... Lakukan hal sama untuk room lain ...
        VenueRoom::create([
            'venue_area_id' => $ballroomArea->id,
            'name' => 'Grand Ballroom 2',
            // Simpan dalam format Array JSON
            'images' => [
                'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=800',
                'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=800'
            ]
        ]);
        VenueRoom::create([
            'venue_area_id' => $ballroomArea->id,
            'name' => 'Grand Ballroom 3',
            // Simpan dalam format Array JSON
            'images' => [
                'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=800',
                'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=800'
            ]
        ]);

        $prefunctionArea = VenueArea::create([
            'venue_id' => $westin->id,
            'name' => 'Prefunction Area',
        ]);

        VenueRoom::create(['venue_area_id' => $prefunctionArea->id, 'name' => 'Prefunction 1']);
        VenueRoom::create(['venue_area_id' => $prefunctionArea->id, 'name' => 'Prefunction 2']);

        // 4. Buat Area: Meeting Room
        $meetingArea = VenueArea::create([
            'venue_id' => $westin->id,
            'name' => 'Meeting Rooms',
        ]);

        // 5. Buat Rooms di Meeting Room
        VenueRoom::create(['venue_area_id' => $meetingArea->id, 'name' => 'Meeting Room A']);
        VenueRoom::create(['venue_area_id' => $meetingArea->id, 'name' => 'Meeting Room B']);
    }
}
