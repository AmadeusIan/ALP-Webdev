<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueRoom extends Model
{
    protected $guarded = [];

    protected $casts = [
        'images' => 'array', // Ini kuncinya!
    ];

    public function areas()
    {
        return $this->belongsTo(VenueArea::class);
    }
}
