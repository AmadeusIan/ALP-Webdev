<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];
    
    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
];


    // Relasi User punya banyak Order
    public function orders() {
        return $this->hasMany(Order::class);
    }

    // Relasi User punya banyak Review Toko
    public function reviews() {
        return $this->hasMany(Review::class);
    }

    // Relasi User punya banyak Notifikasi
    public function notifications() {
        return $this->hasMany(Notification::class);
    }
}