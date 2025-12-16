<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fabric extends Model
{
    use HasFactory;
    protected $guarded = ['id']; 

    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }
    public function logs() {
        return $this->hasMany(InventoryLog::class);
    }
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
    public function reviews(){
        return $this->hasManyThrough(
            ReviewItem::class,
            OrderItem::class,
            'fabric_id',
            'order_item_id'
        );
    }
    public function getImageUrl()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : 'https://images.unsplash.com/photo-1620799140408-ed5341cd2431?q=80&w=1000&auto=format&fit=crop';
    }


}