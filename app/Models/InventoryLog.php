<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = true; 



    public function fabric()
    {
        return $this->belongsTo(Fabric::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


