<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewItem extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false; 
    
    public function orderItem() {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
}