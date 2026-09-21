<?php

namespace App\Models;

use App\Models\PostAttribute;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
	protected $fillable = [
        'user_id', 
        'product_id', 
        'quantity', 
		'trans_date',
		'color',
		'size',
		'cookie',

    ];

    public function product()
    {
    	return $this->belongsTo(Post::class, 'product_id')->where('status','active');
    }

    public function itemprice()
    {
        if($this->product){

            if($this->sku_id)
            {
                $price = PostAttribute::where('type',4)->where('src_id',$this->product_id)->where('sku_id',$this->sku_id)->value('value_1');

                if($price !== null && $price !== '')
                {
                    return (float)$price;
                }
            }

            return $this->product->offerPrice();
        }else{
            return 0;
        }

    }
    
    

    public function subtotal()
    {
        return $this->quantity * $this->itemprice();
    }
    
    public function InDhakaDeliveryCharge()
    {
        if($this->product){
         return   $this->quantity * $this->product->shipping_cost;
        }else{
         return   $this->quantity*0;
        }
    }
    public function OurOfDhakaDeliveryCharge()
    {
        if($this->product){
         return   $this->quantity * $this->product->shipping_cost2;
        }else{
         return   $this->quantity*0;
        }
        
        
    }
}
