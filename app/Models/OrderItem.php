<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

     public function order(){
            return $this->belongsTo(Order::class);
        }

    public function returnItems(){
            return $this->hasMany(OrderReturnItem::class,'order_item_id')->where('return_type',true);
        }

    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function product(){
        return $this->belongsTo(Post::class,'product_id')->where('type',2);
    }
    
    public function productWeightTotal(){
        
        
        $qty=0;
        $unit=null;
        $weight =0;
        
        if($this->product){
            $unit =$this->product->weight_unit?:'';
            if(is_numeric($this->quantity)){
                $qty =$this->quantity;
            }
            if(is_numeric($this->product->weight_amount)){
                $weight =$this->product->weight_amount;
            }
        }
        
        $UnitWeight =$weight*$qty;
        
        if($UnitWeight >= 1000){

            if($unit=='gram'){
                $unit='Kg';
            }elseif($unit=='ml'){
                $unit='Liter';
            }
            
            $UnitWeight=$UnitWeight/1000;
            
        }
        
        return $UnitWeight.' '.$unit; 
    }
    
    public function review(){
        return $this->hasOne(ProductReview::class,'item_id');
    }

    public function sellerDelivery(){
        return $this->belongsTo(User::class,'seller_delivery_user');
    }


}
