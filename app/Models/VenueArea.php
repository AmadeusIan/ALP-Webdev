<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueArea extends Model
{
    protected $guarded = [];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function rooms()
    {
        return $this->hasMany(VenueRoom::class);
    }
}
