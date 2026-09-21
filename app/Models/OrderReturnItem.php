<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturnItem extends Model
{
    use HasFactory;
    
     public function order(){
            return $this->belongsTo(Order::class);
    }
    
    public function Mitem(){
        return $this->belongsTo(OrderItem::class,'order_item_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    
}
