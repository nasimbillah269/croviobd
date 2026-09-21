<?php

namespace App\Models;

use App\Models\General;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //Models Information Data
    /********
     * 
     * 
     * Column:
     * 
     * id               =bigint(20):None,
     * user_id          =bigint(20):null,
     * provider_name    =varchar(255):null,
     * provider_id      =varchar(255):null,
     * provider_token   =varchar(255):null,
     * provider_img_url =varchar(255):null,
     * created_at       =timestamp:null
     * updated_at       =timestamp:null
     * 
     * 
     * 
     ****/

    public function methodOptions(){
        return $this->hasMany(Attribute::class,'parent_id','method_id');
    }

    public function method(){
        return $this->belongsTo(Attribute::class,'method_id');
    }

    public function formmethod(){
        return $this->belongsTo(Attribute::class,'src_id');
    }
    
    public function formmethodOptions(){
        return $this->hasMany(Attribute::class,'parent_id','src_id');
    }

    public function expenseType(){
        return $this->belongsTo(Attribute::class,'src_id');
    }

    public function methodOption(){
        return $this->belongsTo(Attribute::class,'method_option_id');
    }

    public function fullAmount(){

        $formatAmount ='';

        $amount =$this->amount;

        $amountFormet = number_format($amount,general()->currency_decimal);

        if(general()->currency_position==0){
        $formatAmount = $this->currency.' '.$amountFormet;
        }else{
         $formatAmount = $this->currency.' '.general()->currency;
        }
          
        return $formatAmount;

    }

    
}
