<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Response;
use Cookie;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\General;
use App\Models\WishList;
use Illuminate\Support\Str;

class CartMiddleware
{

    public function handle($request, Closure $next)
    {
        
        $cookie = $request->cookie('carts');
        
        $locale=session()->get('locale')?:'en';
        session()->put('locale', $locale); 
        app()->setlocale($locale);
    
        if($cookie)
        {
    
            if(!$request->ajax())
            {
                $cartsCount = Cart::where('cookie',$cookie)->sum('quantity');
                $carts = Cart::where('cookie', $cookie)->latest()->paginate(500);
                
                $cartsid = Cart::where('cookie', $cookie)->where('address','!=',null)->latest()->first();
                $locationid=null;
                if($cartsid){
                $locationid=$cartsid->address;
                }

                $cartTotalPrice = 0;
                $DShipingCharge = 0;
                $ODhakaShippingcharge = 0;
                $deliveryCharge = 0;
                $couponDisc = 0;
                
                $wp =General::first();
                
                foreach ($carts as $cart) 
                {
                    if($cart->product){
                        $cartTotalPrice +=  $cart->subtotal();
                        //$DShipingCharge += $cart->InDhakaDeliveryCharge();
                        //$ODhakaShippingcharge += $cart->OurOfDhakaDeliveryCharge();
                        $cart->product_type =$cart->product->itemType();
                        $cart->save();
                    }else{
                        $cart->delete();
                    }
                    
                }

                if ($mci = Session::get('my_coupon_id')) 
                {
                    $mc =Coupon::where('id',$mci)->first();

                    if($mc)
                    {
                      $couponDisc = $cartTotalPrice * ($mc->discount / 100);
                    }
                }

                
                
                $gtD=0;
                $gtOD=0;
                $gtODShipping=0;


                $wlCount = WishList::where('cookie',$cookie)->where('type',0)->count();
                
                $cpCount = WishList::where('cookie',$cookie)->where('type',2)->count();
                
                // Shipping Charge
                $shippingCharge=general()->defult_shipping_charge?:0;
                $frozen = Cart::where('cookie', $cookie)->where('product_type',1)->count();
                $Dry = Cart::where('cookie', $cookie)->where('product_type',2)->count();
                
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
                
                $bonusCoin =0;
                if(general()->coin_status && $cartTotalPrice > 0){
                    $bonusCoin =($cartTotalPrice*general()->coin_bonus/100);
                }
                
                view()->share('cartsCount',  $cartsCount);
                view()->share('carts',  $carts);
                view()->share('cartTotalPrice',$cartTotalPrice);
                view()->share('couponDisc', number_format($couponDisc,0));
                view()->share('grandTotal', $grandTotal);
                view()->share('cartTax', $cartTax);
                view()->share('gtD', $gtD);
                view()->share('shippingCharge', $shippingCharge);
                view()->share('gtOD', $gtOD);
                view()->share('gtODShipping', $gtODShipping);
                view()->share('wlCount', $wlCount);
                view()->share('cpCount', $cpCount);
                view()->share('locationid', $locationid);
                view()->share('bonusCoin', $bonusCoin);
                
            }            

            return $next($request);  
        }
        else
        {
            view()->share('cartsCount',  0);
            view()->share('carts', null);
            view()->share('cartTotalPrice', 0);
            view()->share('couponDisc', 0);
            view()->share('grandTotal', 0);
            view()->share('cartTax', 0);
            view()->share('shippingCharge', 0);
            view()->share('wlCount', 0);
            view()->share('cpCount', 0);
            view()->share('bonusCoin', 0);
            view()->share('locationid', null);
            
            

            $response = $next($request);
            
           
           //Cookie::queue(Cookie::make('rabiul', time().'-'.Str::random(15).' browser: '.$_SERVER['HTTP_USER_AGENT'].' ip: '.$request->ip() , 43200));
            
           //Cookie::queue('carts', time().'-'.Str::random(15).' browser: '.$_SERVER['HTTP_USER_AGENT'].' ip: '.$request->ip(), 43200);
          return $response->withCookie(cookie('carts', time().'-'.Str::random(15).' browser: '.$_SERVER['HTTP_USER_AGENT'].' ip: '.$request->ip(), 43200));
          //return dd($response);
            return $next($request);
            
            //return $response->withCookie($cokki);
            //return $response->withCookie(cookie('cart', time().'-'.Str::random(15).' browser: '.$_SERVER['HTTP_USER_AGENT'].' ip: '.$request->ip(), 43200));
            
        }

        

    }
         
}
