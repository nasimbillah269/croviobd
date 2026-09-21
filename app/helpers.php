<?php

use App\Models\General;
use App\Models\Coupon;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Attribute;

function new_slug($text='')
{

  $text = $text ?: '-';

  return Str::slug($text);
}

function general(){
  return $general =General::first();
}

function assetLink(){
  return general()->theme;
}

function myRole(){
   return Auth::user()->permission;
}


function priceFormat($amount=0)
{
  $formatAmount ='';

  $formatAmount = number_format($amount,general()->currency_decimal);

  return $formatAmount;

}

function priceFullFormat($amount=0)
{
  $formatAmount ='';

  $amountFormet = number_format($amount,general()->currency_decimal);

  if(general()->currency_position==0){
    $formatAmount = general()->currency.' '.$amountFormet;
  }else{
     $formatAmount = $amountFormet.' '.general()->currency;
  }
  
  return $formatAmount;

}

function en2bnNumber ($number){
    $search_array= array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
    $replace_array= array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
    $en_number = str_replace($search_array, $replace_array, $number);

    return $en_number;
}

function slider($location=null){
  return Attribute::latest()->where('type',1)->where('status','active')->where('location',$location)->first();
}

function menu($location=null){
  return Attribute::latest()->where('type',8)->where('status','active')->where('location',$location)->first();
}

function hasCart($cookie=null,$id){
    
    $cart=null;
    if($cookie){
        $cart = Cart::where('cookie', $cookie)->where('product_id',$id)->first();
    }
    return $cart;
}

function sendMail($toEmail,$toName,$subject,$datas,$template,$attachments=null){
    
  try {
    Mail::send($template,compact('datas'), function ($message) use ($toEmail,$toName,$subject,$attachments) {
        $message->from(general()->mail_from_address, general()->mail_from_name);
        $message->to($toEmail,$toName);
        //To bb mail 
        //$message->cc($ccRecipients);
        //To Replay diffrent mail 
        //$message->replyTo('replyto@example.com', 'Reply To Name');
        
        $message->subject($subject);
        
        if($attachments){
            // Attachments
            foreach ($attachments as $attachment) {
                $message->attach($attachment['path'], [
                    'as' => $attachment['name'],
                    'mime' => $attachment['mime'],
                ]);
            }
        }
        
    });
      return true;
  } catch (Exception $ex) {
      // Debug via $ex->getMessage();
      return false;
  }

}


function myCart($cookie){
    
    $cartsCount=0;
    $carts=null;
    $cartTotalPrice=0;
    $couponDisc =0;
    $grandTotal =0;
    $cartTax =0;
    $shippingCharge=0;
    $bonusCoin=0;
    
    if($cookie){
        $carts = Cart::where('cookie', $cookie)->latest()->paginate(500);
        $cartsCount =$carts->sum('quantity');
        foreach ($carts as $cart) 
        {
            if($cart->product){
                $cartTotalPrice +=  $cart->subtotal();
                $cart->product_type =$cart->product->itemType();
                $cart->save();
            }else{
                $cart->delete();
            }
            
            unset($cart->product);
            
        }

        if ($mci = Session::get('my_coupon_id')) 
        {
            $mc =Coupon::where('id',$mci)->first();

            if($mc)
            {
              $couponDisc = $cartTotalPrice * ($mc->discount / 100);
            }
        }
        
        // Shipping Charge
        $shippingCharge=general()->defult_shipping_charge?:0;
        $frozen =$carts->where('product_type',1)->count();
        $Dry = $carts->where('product_type',2)->count();
        
        $frozenAmount =general()->frozen_amount?:0;
        $dyeAmount =general()->dye_amount?:0;
        $mixAmount =general()->mix_amount?:0;
        
        if($frozen > 0 && $Dry==0 && $cartTotalPrice >= $frozenAmount || $Dry > 0 && $frozen==0 && $cartTotalPrice >= $dyeAmount || $Dry > 0 && $frozen > 0 && $cartTotalPrice >= $mixAmount){
          $shippingCharge = 0;  
        }
        
        
        //Tax Charge
        $cartTax =0;
        
        if(general()->tax_status==1){
          $cartTax =  ($cartTotalPrice*general()->tax)/100;
        }
        
        $grandTotal = $cartTotalPrice + $shippingCharge + $cartTax - $couponDisc;
        
        if(general()->coin_status && $cartTotalPrice > 0){
            $bonusCoin =($cartTotalPrice*general()->coin_bonus/100);
        }
        
    }
    
    return compact('cartsCount','carts','cartTotalPrice','couponDisc','grandTotal','cartTax','shippingCharge','bonusCoin');
}

