<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function cancelItems()
    {
        return $this->hasMany(OrderReturnItem::class,'order_id')->where('return_type',false);
    }
    
    public function returnItems()
    {
        return $this->hasMany(OrderReturnItem::class,'order_id')->where('return_type',true);
    }
    
    public function TotalGramUnit(){
        
        $gram =0;
        $weight =0;
        $qty =0;
        
        $un='';
        
        foreach($this->items as $item){
            if(is_numeric($item->quantity)){
                $qty =$item->quantity;
            }
            if($item->product){
                if($item->product->weight_unit=='gram'){
                    if(is_numeric($item->product->weight_amount)){
                        $weight =$item->product->weight_amount;
                    }
                    $gram+= $qty*$weight; 
                }
            }
            
        }
        
        return $gram;
        
    }
    
    public function TotalGramWeight(){
        if($this->TotalGramUnit() >= 1000){
            return ($this->TotalGramUnit()/1000).' Kg';
        }else{
            return ($this->TotalGramUnit()).' Gram';
        }
    }
    
    public function TotalLiterWeight(){
        if($this->TotalLiterUnit() >= 1000){
            return ($this->TotalLiterUnit()/1000).' Liters';
        }else{
            return ($this->TotalLiterUnit()).' ml';
        }
    }
    
    public function TotalLiterUnit(){
        $ml =0;
        $weight =0;
        $qty =0;
        foreach($this->items as $item){
            if(is_numeric($item->quantity)){
                $qty =$item->quantity;
            }
            if($item->product){
                
                if(is_numeric($item->product->weight_amount)){
                        $weight =$item->product->weight_amount;
                    }
                
               if($item->product->weight_unit=='ml'){
                   $ml+=$weight*$qty;
               }
            }
            
        }
        
        return $ml;
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function posByuser(){
        return $this->belongsTo(User::class,'pending_by');
    }

     public function customerDelivery(){
        return $this->belongsTo(User::class,'order_delivery_By');
    }
    
    public function transections()
    {
        return $this->hasMany(Transaction::class,'src_id')->whereIn('type',[0,3,8])->where('status','<>','temp');
    }
    
    public function transectionsAll()
    {
        return $this->hasMany(Transaction::class,'src_id')->whereIn('type',[0,3,2])->where('status','<>','temp');
    }

    public function transectionsTemp()
    {
        return $this->hasMany(Transaction::class,'src_id')->whereIn('type',[0,3])->where('type',3)->where('status','temp');
    }

    public function transectionsSuccess()
    {
        return $this->hasMany(Transaction::class,'src_id')->whereIn('type',[0,3])->where('status','success');
    }
    
    public function transectionsRefund()
    {
        return $this->hasMany(Transaction::class,'src_id')->whereIn('type',[2])->where('status','success');
    }

    
    public function returnTransections()
    {
        return $this->hasMany(Transaction::class,'order_id')->where('type',2);
    }
    
    public function countryN(){
        return $this->belongsTo(Country::class,'country');
    }
    
    public function divitionN(){
        return $this->belongsTo(Country::class,'division');
    }

    public function districtN(){
        return $this->belongsTo(Country::class,'district');
    }
    
    
    public function cityN(){
        return $this->belongsTo(Country::class,'city');
    }


    public function fullAddress(){
        
        $addr ='';
        
        if($this->postal_code){
           $addr .=$this->postal_code;
        }
        if($this->districtN){
           $addr .=', '.$this->districtN->name;
        }
        if($this->city_name){
           $addr .=', '.$this->city_name.' - Japan';
        }
        if($this->address){
          $addr .=', '.$this->address;  
        }

        return $addr;
        
    }
    


}
