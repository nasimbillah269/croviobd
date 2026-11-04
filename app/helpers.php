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

function priceFullFormat($amount=0,$decimal=null)
{
  $formatAmount ='';

  $amountFormet = number_format($amount,$decimal ?? general()->currency_decimal);

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
              $couponDisc = $mc->discountFor($cartTotalPrice);
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



function bn2enNumber($number){
    $search_array= array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
    $replace_array= array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
    return str_replace($search_array, $replace_array, $number);
}

// Normalises a Bangladeshi mobile to 8801XXXXXXXXX (13 digits)
function bdMobile($mobile){
    $mobile = preg_replace('/\D/', '', bn2enNumber((string)$mobile));
    if(strlen($mobile) == 11 && substr($mobile, 0, 2) == '01'){
        $mobile = '88'.$mobile;
    }
    return $mobile;
}

// The SMS URL in settings may use {number} {message} {username} {password} {senderid} placeholders;
// without placeholders those values are appended as query parameters.
function smsUrl($to, $msg){
    $general = general();
    $url = $general->sms_type == 'Masking' && $general->sms_url_masking
        ? $general->sms_url_masking
        : ($general->sms_url_nonmasking ?: $general->sms_url_masking);

    if(!$url){
        return null;
    }

    $values = [
        '{number}' => $to,
        '{message}' => $msg,
        '{username}' => urlencode((string)$general->sms_username),
        '{password}' => urlencode((string)$general->sms_password),
        '{senderid}' => urlencode((string)$general->sms_senderid),
    ];

    if(strpos($url, '{number}') !== false){
        return strtr($url, $values);
    }

    return $url.(strpos($url, '?') === false ? '?' : '&').http_build_query([
        'username' => $general->sms_username,
        'password' => $general->sms_password,
        'senderid' => $general->sms_senderid,
        'number' => $to,
    ]).'&message='.$msg;
}

function sendSms($mobile, $message){
    $to = bdMobile($mobile);
    if(strlen($to) != 13){
        return false;
    }

    $url = smsUrl($to, urlencode($message));
    if(!$url){
        return false;
    }

    try {
        (new \GuzzleHttp\Client(['timeout' => 10]))->request('GET', $url);
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

// Normalised Facebook post link used to detect re-use; null when it is not a Facebook post link
function fbPostKey($url){
    $url = trim((string)$url);
    if($url === ''){
        return null;
    }
    if(!preg_match('#^https?://#i', $url)){
        $url = 'https://'.$url;
    }

    $parts = parse_url($url);
    if(!$parts || empty($parts['host'])){
        return null;
    }

    $host = strtolower($parts['host']);
    $host = preg_replace('/^(www|m|web|mbasic|mobile|touch)\./', '', $host);
    if($host == 'fb.com'){
        $host = 'facebook.com';
    }
    if(!in_array($host, ['facebook.com', 'fb.watch'])){
        return null;
    }

    $path = strtolower(rtrim($parts['path'] ?? '', '/'));
    if($path === ''){
        return null;
    }

    // Tracking params change per share; only these identify the post
    $query = [];
    if(!empty($parts['query'])){
        parse_str($parts['query'], $params);
        foreach(['story_fbid', 'fbid', 'id', 'v'] as $keep){
            if(isset($params[$keep]) && is_scalar($params[$keep])){
                $query[$keep] = strtolower($params[$keep]);
            }
        }
    }

    return substr($host.$path.($query ? '?'.http_build_query($query) : ''), 0, 191);
}

// Flat discount by post type: 'post' (photo post) or 'reels'
function fbDiscountFor($subtotal, $general = null, $postType = 'post'){
    $general = $general ?: general();
    $discount = $postType == 'reels' ? $general->fb_reels_discount_amount : $general->fb_discount_amount;
    if(!$general->fb_discount_status || $discount <= 0){
        return 0;
    }

    return round(min($discount, $subtotal));
}

// Facebook page from settings as HTML: a link opening in a new tab when a URL is saved, otherwise the plain name
function fbPageHtml($general = null){
    $general = $general ?: general();
    $page = trim((string)$general->fb_page_name);
    if($page === ''){
        return e($general->title);
    }
    if(preg_match('#^(https?://)?(www\.|m\.)?(facebook|fb)\.com/#i', $page)){
        $url = preg_match('#^https?://#i', $page) ? $page : 'https://'.$page;
        return '<a href="'.e($url).'" target="_blank" rel="noopener noreferrer" style="color: #1877f2;font-weight: 600;">'.e($general->title).'</a>';
    }
    return e($page);
}

function fbDiscountTermsDefault(){
    return "সঠিক লিংক দিলেই এই অর্ডারে ফটো পোস্টে {post} আর রিলসে {reels} ছাড় সাথে সাথে যোগ হবে (যাচাইয়ে বাতিল হলে ছাড়টি বাদ যাবে)।\nশর্ত: আগে আমাদের কাছ থেকে কেনা ও ডেলিভারি পাওয়া পণ্য নিয়ে Facebook পোস্ট, পোস্টে {page} পেজ ট্যাগ বা কোলাবরেশন থাকতে হবে, আর পোস্টটি কমপক্ষে {days} দিন পুরনো হতে হবে। একটি পোস্ট একবারই ব্যবহার করা যাবে।";
}

// Checkout terms as HTML: admin text is escaped, then {post} {reels} {page} {days} are filled in
function fbDiscountTermsHtml($subtotal, $general = null){
    $general = $general ?: general();
    $text = trim((string)$general->fb_discount_terms) !== '' ? $general->fb_discount_terms : fbDiscountTermsDefault();
    $amount = function($n){
        return '<b style="color: #ff005e;">৳'.en2bnNumber(number_format($n)).'</b>';
    };

    return strtr(nl2br(e($text)), [
        '{post}' => $amount(fbDiscountFor($subtotal, $general, 'post')),
        '{reels}' => $amount(fbDiscountFor($subtotal, $general, 'reels')),
        '{page}' => fbPageHtml($general),
        '{days}' => en2bnNumber($general->fb_post_min_days),
    ]);
}

// Guesses the post type from the link; reel links look like /reel/..., /share/r/... or fb.watch
function fbPostTypeFromLink($url){
    return preg_match('#(/reels?/|/share/r/|fb\.watch)#i', (string)$url) ? 'reels' : 'post';
}
