<?php

namespace App\Http\Controllers\Welcome;

use Mail;
use Auth;
use Cookie;
use Hash;
use Str;
use Validator;
use Session;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Cart;
use App\Models\General;
use App\Models\Country;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\User;
use App\Models\Post;
use App\Models\PostExtra;
use App\Models\PostAttribute;
use App\Models\Coupon;
use App\Models\CouponItem;
use App\Models\Transaction;
use App\Models\WishList;
use App\Models\Attribute;
use App\Models\OrderItem;
use App\Mail\orderInvoiceMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{

    public function __construct(){
        
      	$this->middleware('cart');
        function isMobileDevice() { 
          return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo 
        |fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i" 
        , $_SERVER["HTTP_USER_AGENT"] ?? ""); 
        }

        if(isMobileDevice())
        {
          $this->device =General::first()->theme.'.carts.';
        }
        else
        {
          $this->device =General::first()->theme.'.carts.';
        }

    }

    public function addToCart(Request $r,$id){
    	
    	$product = Post::where('type',2)
    	->where('status','active')
        ->where('stock_status',true)
        ->where('quantity','>',0)->find($id);
    	
    	$qty = $r->quantity ?: 1;    
		
		$cookie = $r->cookie('carts');

        if($cookie && $product)
    	{

    	$skuId = null;
    	$skuRows = null;
    	$colorLabel = null;
    	$sizeLabel = null;

    	if($product->variation_status && $product->productAttibutes->count() > 0){

    	    if(!$r->sku_id){
    	        if($r->ajax()){
    	            return Response()->json(['success' => false, 'message' => 'Please select product options']);
    	        }
    	        return back()->with('error','Please select product options');
    	    }

    	    $skuRows = PostAttribute::where('type',4)->where('src_id',$product->id)->where('sku_id',$r->sku_id)->get();

    	    if($skuRows->count()==0){
    	        if($r->ajax()){
    	            return Response()->json(['success' => false, 'message' => 'Selected combination not available']);
    	        }
    	        return back()->with('error','Selected combination not available');
    	    }

    	    $skuId = $r->sku_id;

    	    foreach($skuRows as $row){
    	        if($row->attributeItem && $row->attribute){
    	            $groupName = strtolower($row->attribute->name);

    	            if(str_contains($groupName,'colo')){
    	                $colorLabel = $row->attributeItem->name;
    	            }elseif(str_contains($groupName,'siz')){
    	                $sizeLabel = $row->attributeItem->name;
    	            }
    	        }
    	    }
    	}

    	if($skuRows){
    	    $comboQty = (int)($skuRows->first()->duration ?? 0);
    	    if($comboQty > 0 && $qty > $comboQty){
    	        $qty = $comboQty;
    	    }
    	}

    	    if($r->orderNow){
    	        Cart::where('cookie', $cookie)->where('cart_type',1)->delete();
    	    }

    		$oldCartQuery = Cart::where('cookie', $cookie)->where('product_id', $product->id);
    		$oldCartQuery = $skuId ? $oldCartQuery->where('sku_id',$skuId) : $oldCartQuery->whereNull('sku_id');
    		$oldCart = $oldCartQuery->first();


    		if($oldCart)
    		{
                $oldCart->trans_date = date("Y-m-d");
                $oldCart->user_id = Auth::id();
                $oldCart->quantity =  $qty >= $product->min_order_quantity ? $qty : $product->min_order_quantity;;
                $oldCart->save();
    		}
    		else
    		{

    			$cart = new Cart;
                $cart->addedby_id = Auth::id();
                $cart->trans_date = date("Y-m-d");
                $cart->user_id = Auth::id();
                $cart->product_id = $product->id;
                $cart->color = $colorLabel;
                $cart->size = $sizeLabel;

                $cart->sku_id = $skuId;
                if($product->emi_status==true && $r->statusEmi==true){
                $cart->emi = 1;
                }else{
                $cart->emi = 0;
                }
                $cart->quantity = $qty >= $product->min_order_quantity ? $qty : $product->min_order_quantity;
                $cart->cart_type = $r->orderNow?true:false;
                $cart->cookie = $cookie;
                $cart->save();
    		}
    		
            
    		$carts = Cart::where('cookie', $cookie)->select(['id','product_id', 'quantity','color','size'])->latest()->paginate(500);

    		$cartsCount = Cart::where('cookie',$cookie)->sum('quantity');

	    	$cartTotalPrice = 0;
	    	$couponDisc = 0;

	    	foreach ($carts as $cart) 
            {
                if($cart->product){
                        $cartTotalPrice += $cart->subtotal();
                        $cart->product_type =$cart->product->itemType();
                        $cart->save();
                    }else{
                       $cart->delete(); 
                    }

            }
    		
    		$HeadercartItems =view($this->device.'.includes.headerCartBox',compact('carts','cartTotalPrice'))->render();
    		$HeadercartItems2 =view($this->device.'.includes.headerCartBox2',compact('carts','cartTotalPrice'))->render();
    		$singleAddToCard =view($this->device.'.includes.singleAddToCart',compact('product'))->render();

		    if($r->ajax())
	        {	

		        return Response()->json([
		            'success' => true,
		            'HeadercartItems' => $HeadercartItems,
		            'HeadercartItems2' => $HeadercartItems2,
		            'singleAddToCard' => $singleAddToCard,
		            'add_type' => $r->orderNow?true:false,
		          ]);
	    	}
        }else{
            
           if($r->ajax())
	        {
            
                return Response()->json([
		            'success' => false,
		          ]);  
		          
	        }else{
	            return redirect()->route('index');
	        }
        }

        if($r->orderNow)
        {
        	 return redirect()->route('checkout');
        }
        else
        {
        	return back()->with('success', 'Product successfully added to cart');
        }

        // return back();
    }


    public function changeToCart(Request $r,$id,$type){

    	if($r->ajax())
        {

        	$cart =Cart::find($id);

	    	$cookie = $r->cookie('carts');

	    	if($cookie && $cart)
	    	{
	    	    $product =$cart->product;
	    	    $pro_id =$cart->product_id;
	    	    
	    		if($type == 'increment')
		    	{
		    		

		    		$qty = $cart->quantity + 1;

		    		

		    		$maxLimit = $cart->product->max_order_quantity ?: $cart->product->quantity;
		    		if($qty <= $maxLimit){

		    			$cart->update(['quantity'=> $qty]);
		    			$s = true; 

		    		}else{
		    			$s = false;
		    		}



		    	}elseif($type == 'decrement'){
		    		
		    		$qty = $cart->quantity - 1;

		    		$minLimit = $cart->product->min_order_quantity ?: 1;

		    		if($qty >= 1 && $qty >= $minLimit)
		    		{
		    			$cart->update(['quantity'=> $qty]);
		    			$s = true;
		    		}else{
		    			$s = false;
		    		}

		    	}elseif($type == 'quantity'){
		    	    
		    	    $qty =$r->qty?:1;
		    	    
		    	    $maxLimit = $cart->product->max_order_quantity ?: $cart->product->quantity;
		    	    $minLimit = $cart->product->min_order_quantity ?: 1;
		    		if($qty <= $maxLimit && $qty >= $minLimit && $qty >= 1){

		    			$cart->update(['quantity'=> $qty]);
		    			$s = true; 

		    		}else{
		    			$s = false;
		    		}
		    	    
		    	}elseif($type == 'delete'){
		    		$cart->delete();
		    		$s = true;
		    	}

		    	$carts = Cart::where('cookie', $cookie)->select(['id','product_id', 'quantity','color','size'])->latest()->paginate(500);

		    	$cartTotalPrice = 0;
		    	$couponDisc = 0;

		    	foreach ($carts as $cart) 
                {
                    if($cart->product){
                        $cartTotalPrice += $cart->subtotal();
                        $cart->product_type =$cart->product->itemType();
                        $cart->save();
                    }else{
                       $cart->delete(); 
                    }
                    
                    

                }

                if ($mci = Session::get('my_coupon_id')) 
                {
                    $mc = Coupon::where('id',$mci)->first();
                    if($mc)
                    {
                      $couponDisc = $mc->discountFor($cartTotalPrice);
                    }
                }
                
                
                // Shipping Charge
                $shippingCharge=general()->defult_shipping_charge?:0;
                $frozen = Cart::where('cookie', $cookie)->where('product_type',1)->count();
                $Dry = Cart::where('cookie', $cookie)->where('product_type',2)->count();
                
                $frozenAmount =general()->frozen_amount?:0;
                $dyeAmount =general()->dye_amount?:0;
                $mixAmount =general()->mix_amount?:0;
                
                if($frozen > 0 && $Dry==0 && $cartTotalPrice >=$frozenAmount){
                  $shippingCharge = 0;  
                }elseif($Dry > 0 && $frozen==0 && $cartTotalPrice >=$dyeAmount){
                  $shippingCharge = 0;
                }elseif($Dry > 0 && $frozen > 0 && $cartTotalPrice >=$mixAmount){
                  $shippingCharge = 0;
                }
                
                
                
                $cartTax =0;
                if(general()->tax_status==1){
                  $cartTax =  ($cartTotalPrice*general()->tax)/100;
                }

                $grandTotal = $cartTotalPrice+$shippingCharge+$cartTax - $couponDisc;
                
                $myCart = myCart($cookie);
                $bonusCoin=$myCart['bonusCoin'];
                

                $HeadercartItems =view($this->device.'.includes.headerCartBox',compact('carts','cartTax','shippingCharge','cartTotalPrice'))->render();
                $HeadercartItems2 =view($this->device.'.includes.headerCartBox2',compact('carts','cartTax','shippingCharge','cartTotalPrice'))->render();
                
                $singleAddToCard=null;
                if($product){
                    $singleAddToCard =view($this->device.'.includes.singleAddToCart',compact('product'))->render();
                }
                
		    	$cartItems =view($this->device.'.includes.cartItems',compact('carts','cartTax','cartTotalPrice','shippingCharge','grandTotal','couponDisc','bonusCoin'))->render();

		    	return Response()->json([
			            'success' => $s,
			            'pro_id' => $pro_id,
			            'cartItems' => $cartItems,
			            'HeadercartItems' => $HeadercartItems,
			            'HeadercartItems2' => $HeadercartItems2,
			            'singleAddToCard' => $singleAddToCard,
			            'cartsCount' => $carts->sum('quantity'),
			            'cartTotalPrice' => $cartTotalPrice,
			            'grandTotal' => $grandTotal,
			            'couponDisc' => $couponDisc,
			          ]);


		    }else{

		    	return Response()->json([
			            'success' => false,
			          ]);

		    }

        }

    }

    public function couponApply(Request $r){

        $check = $r->validate([
            'coupon_code' => 'required|max:100'
        ]);

        $cookie = $r->cookie('carts');
        $carts = Cart::where('cookie', $cookie)->latest()->paginate(500);

        if($carts->count() < 1){
            $message = 'আপনার কার্ট খালি, কুপন ব্যবহার করা যাবে না।';
            if($r->ajax()){
                return Response()->json(['success' => false, 'message' => $message]);
            }
            return back()->with('info', $message);
        }

        $cartTotalPrice = 0;
        foreach($carts as $cart){
            $cartTotalPrice += $cart->subtotal();
        }

        $coupon = Coupon::findValid(trim($r->coupon_code));
        $reason = $coupon ? $coupon->invalidReason($cartTotalPrice) : 'কুপন কোডটি সঠিক নয়।';

        if($reason){
            $r->session()->forget(['my_coupon_id']);
            if($r->ajax()){
                return Response()->json(['success' => false, 'message' => $reason]);
            }
            return back()->with('info', $reason);
        }

        $r->session()->put(['my_coupon_id' => $coupon->id]);
        $discount = $coupon->discountFor($cartTotalPrice);

        if($r->ajax()){
            return Response()->json([
                'success' => true,
                'message' => 'কুপন সফলভাবে যোগ হয়েছে। ছাড়: ৳'.en2bnNumber(number_format($discount)),
                'code' => $coupon->code,
                'discount' => $discount,
            ]);
        }
        return back()->with('success', 'Your coupon code is valid and successfully added');

    }

    public function couponRemove(Request $r){

        $r->session()->forget(['my_coupon_id']);

        if($r->ajax()){
            return Response()->json(['success' => true, 'discount' => 0]);
        }
        return back();
    }


    public function carts(Request $r){
        $user =Auth::user();
        if($user){
            $cookie = $r->cookie('carts');
            $carts = Cart::where('cookie','<>',$cookie)->where('user_id',$user->id)->latest()->delete();
        }

    	return view($this->device.'cart');

    }

    // Single source of truth for checkout money; the checkout page mirrors this logic in JS for the live summary
    private function checkoutTotals($carts, $zone = null, $paymentMethod = null, $districtId = null, $withFbDiscount = false, $fbPostType = 'post'){

        $general = general();

        $subtotal = 0;
        foreach($carts as $cart){
            if($cart->product){
                $subtotal += $cart->subtotal();
            }
        }

        $coupon = null;
        $couponDisc = 0;
        if($mci = Session::get('my_coupon_id')){
            $coupon = Coupon::find($mci);
            if($coupon && !$coupon->invalidReason($subtotal)){
                $couponDisc = $coupon->discountFor($subtotal);
            }else{
                $coupon = null;
            }
        }

        $tax = $general->tax_status == 1 ? round(($subtotal * $general->tax) / 100) : 0;

        $deliveryCharge = $zone ? $zone->chargeFor($districtId) : 0;
        $freeDelivery = false;
        if($paymentMethod == 'full_advance' && $general->full_advance_free_delivery){
            $freeDelivery = $deliveryCharge > 0;
            $deliveryCharge = 0;
        }

        // Taken off straight away; admin adds it back if the post is rejected
        $fbDiscount = $withFbDiscount ? min(fbDiscountFor($subtotal, $general, $fbPostType), max($subtotal - $couponDisc, 0)) : 0;

        $grandTotal = $subtotal - $couponDisc - $fbDiscount + $tax + $deliveryCharge;

        if($paymentMethod == 'full_advance'){
            $advance = $grandTotal;
        }elseif($paymentMethod == 'cod'){
            $advance = ceil($deliveryCharge * ($general->cod_advance_percent / 100));
        }else{
            $advance = 0;
        }

        return [
            'subtotal' => $subtotal,
            'coupon' => $coupon,
            'couponDisc' => $couponDisc,
            'fbDiscount' => $fbDiscount,
            'tax' => $tax,
            'deliveryCharge' => $deliveryCharge,
            'freeDelivery' => $freeDelivery,
            'grandTotal' => $grandTotal,
            'advance' => $advance,
            'due' => $grandTotal - $advance,
        ];
    }

    public function checkout(Request $r){

    	$user =Auth::user();

    	//Check Cart Is Valid
    	$cookie = $r->cookie('carts');
        $carts = Cart::where('cookie', $cookie)->latest()->paginate(500);
	    if(1 > $carts->count())
        {
            return redirect()->route('carts')->with('info', 'Sorry, Your Cart Is empty.');
        }

        if($user && $cookie){
            Cart::where('cookie','<>',$cookie)->where('user_id',$user->id)->latest()->delete();
        }

        $general = general();
        $totals = $this->checkoutTotals($carts);

        // Every district covered by an active zone; the zone itself is resolved from the chosen thana
        $districts = collect();
        foreach($this->activeZones() as $zone){
            foreach($zone->districts() as $district){
                $districts->put($district->id, ['id' => $district->id, 'name' => $this->locationName($district), 'en' => strtolower(trim($district->name))]);
            }
        }
        // Dhaka first since most orders come from there, the rest alphabetical
        $districts = $districts->sortBy(function($district){
            return [in_array($district['en'], ['dhaka', 'dhaka district']) ? 0 : 1, $district['name']];
        })->map(function($district){
            unset($district['en']);
            return $district;
        })->values();

    	return view($this->device.'checkout',compact('user','carts','districts','totals','general'));
    }

    private function activeZones(){
        return DeliveryZone::with('districtCharges')->where('status','active')->orderBy('drag')->get();
    }

    private function locationName($item){
        $bnName = trim(preg_replace('/[\x{200B}-\x{200F}\x{FEFF}]/u', '', (string)$item->bn_name));
        return $bnName ?: $item->name;
    }

    // A district can be split across zones (e.g. Dhaka City / outside Dhaka), so the thana decides the zone
    private function zoneForUpazila($districtId, $upazilaId){
        $district = Country::where('type', 3)->find($districtId);
        if(!$district){
            return null;
        }
        return $this->activeZones()->first(function($zone) use ($district, $upazilaId){
            return $zone->division_id == $district->parent_id && $zone->hasUpazila($district->id, $upazilaId);
        });
    }

    public function checkoutLocations(Request $r){

        $district = Country::where('type', 3)->find($r->district);
        if(!$district){
            return Response()->json(['success' => false, 'items' => []]);
        }

        $items = collect();
        foreach($this->activeZones() as $zone){
            if($zone->division_id != $district->parent_id){
                continue;
            }
            $charge = $zone->chargeFor($district->id);
            foreach($zone->upazilas($district->id) as $upazila){
                if(!$items->has($upazila->id)){
                    $items->put($upazila->id, ['id' => $upazila->id, 'name' => $this->locationName($upazila), 'charge' => $charge]);
                }
            }
        }

        return Response()->json([
            'success' => true,
            'items' => $items->sortBy('name')->values(),
        ]);
    }

    public function checkoutPost(Request $r){

        // Customers often type Bangla digits in phone/TrxID fields
        $r->merge([
            'mobile' => bn2enNumber(trim((string)$r->mobile)),
            'sender_number' => bn2enNumber(trim((string)$r->sender_number)),
            'trx_id' => strtoupper(bn2enNumber(trim((string)$r->trx_id))),
        ]);

        $check = $r->validate([
            'name' => 'required|max:100',
            'mobile' => ['required','regex:/^(?:\+?88)?01[3-9]\d{8}$/'],
            'email' => 'nullable|email|max:150',
            'district_id' => 'required|numeric',
            'upazila_id' => 'required|numeric',
            'address' => 'required|max:500',
            'payment_method' => 'required|in:cod,full_advance',
            'payment_screenshot' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ],[
            'name.required' => 'আপনার নাম লিখুন।',
            'mobile.required' => 'মোবাইল নম্বর লিখুন।',
            'mobile.regex' => 'সঠিক মোবাইল নম্বর লিখুন (যেমন: 01XXXXXXXXX)।',
            'email.email' => 'সঠিক ইমেইল ঠিকানা লিখুন।',
            'district_id.required' => 'জেলা সিলেক্ট করুন।',
            'upazila_id.required' => 'উপজেলা / থানা সিলেক্ট করুন।',
            'address.required' => 'আপনার ঠিকানা (বাসা/রোড/এলাকা) লিখুন।',
            'payment_method.required' => 'পেমেন্ট মেথড সিলেক্ট করুন।',
            'payment_screenshot.image' => 'স্ক্রিনশট অবশ্যই ছবি হতে হবে।',
            'payment_screenshot.max' => 'স্ক্রিনশট ৪ MB এর বেশি হতে পারবে না।',
        ]);

        if(is_numeric($r->name) || filter_var($r->name, FILTER_VALIDATE_EMAIL)){
            return back()->withInput()->with('nameError', 'This field is required your Name. Not Allow Email or Mobile.');
        }

        $zone = $this->zoneForUpazila($r->district_id, $r->upazila_id);
        if(!$zone){
            return back()->withInput()->withErrors(['upazila_id' => 'সঠিক জেলা ও উপজেলা / থানা সিলেক্ট করুন।']);
        }

        $cookie = $r->cookie('carts');
        $carts = Cart::where('cookie', $cookie)->latest()->paginate(500);
        if(1 > $carts->count()){
            return redirect()->route('carts')->with('info', 'Sorry, Your Cart Is empty.');
        }

        // Facebook post discount: link is optional, discount is applied now and the post is reviewed by admin later
        $fbPostKey = null;
        $fbPostType = $r->fb_post_type == 'reels' ? 'reels' : 'post';
        $general = general();
        $fbAllowed = $general->fb_discount_status && !($r->payment_method=='full_advance' && $general->fb_full_advance_rule=='not_allowed');
        if($fbAllowed && trim((string)$r->fb_post_link) !== ''){
            $r->validate(['fb_post_link' => 'max:500'],['fb_post_link.max' => 'লিংকটি অনেক বড়।']);
            $fbPostKey = fbPostKey($r->fb_post_link);
            if(!$fbPostKey){
                return back()->withInput()->withErrors(['fb_post_link' => 'সঠিক Facebook পোস্টের লিংক দিন।']);
            }
            if($this->fbPostUsed($fbPostKey)){
                return back()->withInput()->withErrors(['fb_post_link' => 'এই পোস্টের লিংক আগেই একটি অর্ডারে ব্যবহার করা হয়েছে। একটি পোস্ট একবারই ব্যবহার করা যায়।']);
            }
        }

        $totals = $this->checkoutTotals($carts, $zone, $r->payment_method, $r->district_id, (bool)$fbPostKey, $fbPostType);

        // Payment proof is needed whenever something must be paid in advance
        if($totals['advance'] > 0){
            $r->validate([
                'trx_id' => 'required|max:50',
            ],[
                'trx_id.required' => 'পেমেন্টের TrxID লিখুন।',
            ]);
        }

        $district = Country::find($r->district_id);
        $upazila = Country::find($r->upazila_id);

        //User Create
        if(Auth::check()){
            $user=Auth::user();
        }else{
            $user =User::where('mobile',$r->mobile)->first();
            if(!$user){
                $user =new User();
                $user->name=$r->name;
                $user->mobile=$r->mobile;
                $password =Str::random(8);
                $user->password=Hash::make($password);
                $user->password_show=$password;
            }
            $user->address_line1=$r->address?:null;
            $user->country=1;
            $user->save();
        }

        $order =new Order();
        $order->user_id=$user?$user->id:null;
        $order->name=$r->name;
        $order->mobile=$r->mobile;
        $order->email=$r->email;
        $order->delivery_zone_id=$zone->id;
        $order->division=$zone->division_id;
        $order->district=$district->id;
        $order->city=$upazila->id;
        $order->area_name=$zone->name;
        $order->city_name=$upazila->name;
        $order->address=$r->address;
        $order->full_address=$r->address.', '.$upazila->name.', '.$district->name.' ('.$zone->name.')';
        $order->note=$r->note;
        $order->order_status='pending';
        $order->pending_at=Carbon::now();
        $order->pending_by=$user?$user->id:null;
        $order->save();

      	foreach($carts as $cart){

      		if($cart->product){

      		$item = new OrderItem;
      		$item->order_id = $order->id;
            $item->user_id = $user?$user->id:null;
            $item->invoice = $order->invoice;
            $item->product_id = $cart->product_id;
            $item->product_name = $cart->product?$cart->product->name:null;
            $item->sku_id = $cart->sku_id;
            $item->color = $cart->color;
            $item->size = $cart->size;
            $item->quantity = $cart->quantity;
            if($cart->product){
                $product =$cart->product;
                if($product->variation_status){

                }else{
                    if($product->quantity > $item->quantity){
                        $product->quantity-=$item->quantity;
                        $product->sell_count+=1;
                        $product->save();
                    }
                }

            $cartProddutWight =0;
            $ItemQty =0;
            if(is_numeric($product->weight_amount)){
                $cartProddutWight=$product->weight_amount;
            }
            if(is_numeric($item->quantity)){
                $ItemQty=$item->quantity;
            }
            $item->total_weight = $ItemQty*$cartProddutWight;

            $item->product_type = $product->itemType();
            }
            $item->price = $cart->itemprice();
            $item->total_price = $cart->subtotal();
            $item->final_price = $cart->subtotal()-$item->total_deal_discount;

            $item->pending_at = Carbon::now();
            $item->pending_by = $user?$user->id:null;
            $item->addedby_id = $user?$user->id:null;
            $item->status=$order->order_status;
            $item->order_status=$order->order_status;
            $item->seller_paid=$item->final_price;
            $item->save();

      		}

      	}

    	Cart::where('cookie', $cookie)->latest()->delete();

        $frozen = $order->items->where('product_type',1)->count();
        $Dry = $order->items->where('product_type',2)->count();

        $order->product_type =0;

        if($frozen > 0 && $Dry==0){
        $order->product_type =1;
        }elseif($Dry > 0 && $frozen==0){
        $order->product_type =2;
        }elseif($Dry > 0 && $frozen > 0){
        $order->product_type =0;
        }

        if($totals['coupon']){
            $order->coupon_id = $totals['coupon']->id;
            $order->coupon_code = $totals['coupon']->code;
            $totals['coupon']->increment('used_count');
        }
        $r->session()->forget(['my_coupon_id']);

        $order->total_price=$order->items->sum('final_price');
        $order->total_items= $order->items->count();
        $order->total_qty= $order->items->sum('quantity');
        $order->shipping_charge =$totals['deliveryCharge'];
        $order->tax =$totals['tax'];
        $order->coupon_discount =$totals['couponDisc'];
        $order->fb_discount =$totals['fbDiscount'];
        $order->grand_total =($order->total_price + $order->shipping_charge + $order->tax) - ($order->coupon_discount+$order->coin_discount+$order->fb_discount);

        // Advance stays unverified (paid_amount = 0) until admin checks the TrxID
        $order->payment_method = $r->payment_method=='full_advance' ? 'Full Advance (Bangla QR)' : 'Cash On Delivery';
        $order->advance_amount = $r->payment_method=='full_advance' ? $order->grand_total : $totals['advance'];
        $order->transection = $order->advance_amount > 0 ? $r->trx_id : null;
        $order->sender_number = $order->advance_amount > 0 ? $r->sender_number : null;
        $order->advance_status = $order->advance_amount > 0 ? 'pending' : null;
        if($fbPostKey){
            $order->fb_post_link = preg_match('#^https?://#i', trim($r->fb_post_link)) ? trim($r->fb_post_link) : 'https://'.trim($r->fb_post_link);
            $order->fb_post_key = $fbPostKey;
            $order->fb_post_type = $fbPostType;
            $order->fb_post_status = 'pending';
        }
        $order->paid_amount=0;
        $order->due_amount=$order->grand_total;

        if($order->advance_amount > 0 && $r->hasFile('payment_screenshot')){
            $file = $r->payment_screenshot;
            $folder = 'medies/payments/'.Carbon::now()->format('M_Y');
            $img = time().'.'.Str::random(20).'.'.$file->getClientOriginalExtension();
            $file->move(public_path($folder), $img);
            $order->payment_screenshot = $folder.'/'.$img;
        }

        $order->invoice=$order->created_at->format('dmY').$order->id;
        $order->save();

        $this->sendOrderConfirmation($order);

        $r->session()->put('last_order_invoice', $order->invoice);

        return redirect()->route('orderSuccess',$order->invoice);

    }

    // A post counts as used once it is waiting for review or approved; a rejected link may be tried again
    private function fbPostUsed($key){
        return Order::where('fb_post_key',$key)->whereIn('fb_post_status',['pending','approved'])->exists();
    }

    public function fbPostCheck(Request $r){

        if(!general()->fb_discount_status){
            return Response()->json(['success' => false, 'message' => '']);
        }

        $key = fbPostKey($r->link);
        if(!$key){
            return Response()->json(['success' => false, 'message' => 'এটি সঠিক Facebook পোস্টের লিংক মনে হচ্ছে না।']);
        }
        if($this->fbPostUsed($key)){
            return Response()->json(['success' => false, 'message' => 'সতর্কতা: এই পোস্টের লিংক আগেই ব্যবহার করা হয়েছে। একটি পোস্ট একবারই ব্যবহার করা যায়।']);
        }

        return Response()->json(['success' => true, 'type' => fbPostTypeFromLink($r->link), 'message' => 'লিংক গ্রহণযোগ্য, ডিসকাউন্ট যোগ হয়েছে। অর্ডারের পর আমরা পোস্টটি যাচাই করব।']);
    }

    private function sendOrderConfirmation($order){

        $general =general();

        //**********Send Mail***************//
        if($general->mail_status && $order->email){
            try {
                Mail::send('mails.InvoiceMail', ['order' => $order,'general'=>$general], function ($message) use ($order,$general) {
                    $message->from($general->mail_from_address,$general->mail_from_name);
                    $message->to($order->email,$order->name)
                    ->subject('Order #'.$order->invoice.' received - '.$general->title);
                });
            } catch (\Exception $e) {

            }
        }

        //**********Send SMS ***************//
        if($general->sms_status){

            if($general->order_place_sms_customer && $order->mobile){
                $msg = "প্রিয় {$order->name}, আপনার অর্ডার #{$order->invoice} গ্রহণ করা হয়েছে। মোট: {$general->currency} ".number_format($order->grand_total).". শীঘ্রই কল করে কনফার্ম করা হবে। - {$general->title}";
                sendSms($order->mobile, $msg);
            }

            if($general->order_place_sms_admin && $general->admin_numbers){
                $msg = "New Order in {$general->title}. Invoice: {$order->invoice}, Total: {$order->grand_total}, Advance: {$order->advance_amount}, TrxID: ".($order->transection?:'-');
                foreach(explode(',', $general->admin_numbers) as $number){
                    sendSms(trim($number), $msg);
                }
            }
        }
    }

    public function orderSuccess(Request $r,$invoice){

        // Only the browser that placed the order sees this page; everyone else gets the public invoice view
        if($r->session()->get('last_order_invoice') != $invoice){
            return redirect()->route('invoiceView',$invoice);
        }

        $order = Order::where('order_type','customer_order')->where('invoice',$invoice)->first();
        if(!$order){
            return abort(404);
        }

        return view($this->device.'orderSuccess',compact('order'));
    }

    public function invoiceView($invoice){
        
        $order = Order::where('order_type','customer_order')->where('invoice',$invoice)->first();
        if(!$order){
            return abort(404);
        }

       return view($this->device.'cartInvoice',compact('order'));
    }
    

    public function orderPayment($id){

        $order =Order::find($id);

        if(!$order){
            Session::flash('error','This Order Invoic Are Not Found');
            return redirect()->route('customer.myOrders');
        }
        $active='';
        $general =General::first();

        if($general->online_payment){
            $active ='handcash_payment';
        }elseif($general->wallet_payment){
            $active ='wallet_payment';
        }else{
            $active ='online_payment';
        }
        
        //Mail Send / SMS Send
        
        //**********Send Mail***************//
        
        if($general->mail_status && $order->email){

            Mail::to($order->email)->send(new orderInvoiceMail($order));
            
        }
        
        //**********Send Mail***************//
        
         //**********Send SMS ***************//
            if($general->sms_status){
        
                //Send SMS User
                if($general->order_place_sms_customer && $order->mobile){
                    
                    $m =$order->mobile;
                    
                    $to =bdMobile($m);
                    
                    if(strlen($to) != 13)
                    {
                        return true;
                    }
                    $msg = urlencode("Your order #{$order->invoice} is Successfully Place in {$general->title}. Total Invoice Cost is {$general->currency} {$order->grand_total}."); //150 characters allowed here
        
                    $url = smsUrl($to,$msg);
                
                    $client = new Client();
                    
                    try {
                            $r = $client->request('GET', $url);
                        } catch (\GuzzleHttp\Exception\ConnectException $e) {
                        } catch (\GuzzleHttp\Exception\ClientException $e) {
                        }
                    
                    
                }
                
                //Send SMS Vendor User
                if($general->order_place_sms_vendor){
                    
                    foreach($order->items as $item){
                        
                        if($item->seller){
                            
                            if($item->seller->user){
                                
                                if($item->seller->user->mobile){
                                    
                                    $m =$item->seller->user->mobile;
                    
                                    $to =bdMobile($m);
                                    
                                    if(strlen($to) != 13)
                                    {
                                        return true;
                                    }
                                    $msg = urlencode("Your Product New order #{$order->invoice} is Successfully Place in {$general->title}. Total Cost is {$general->currency} {$item->seller_paid}."); //150 characters allowed here
                        
                                    $url = smsUrl($to,$msg);
                                
                                    $client = new Client();
                                    
                                    try {
                                            $r = $client->request('GET', $url);
                                        } catch (\GuzzleHttp\Exception\ConnectException $e) {
                                        } catch (\GuzzleHttp\Exception\ClientException $e) {
                                        }
                                    
                                }
                                
                                
                            }
                            
                        }
                        
                    }
                    
                }
                
                //Send SMS Admin
                if($general->order_place_sms_admin && $general->admin_numbers){
                    
                    $to =$general->admin_numbers;

                    $msg = urlencode("New Order in {$general->title}. Invoice: {$order->invoice}, Total Cost: {$order->grand_total}."); //150 characters allowed here
        
                    $url = smsUrl($to,$msg);
                
                    $client = new Client();
                    
                    try {
                            $r = $client->request('GET', $url);
                        } catch (\GuzzleHttp\Exception\ConnectException $e) {
                        } catch (\GuzzleHttp\Exception\ClientException $e) {
                        }
                }
                
                
                
            }
            
        //**********Send SMS ***************//
        

        return view($this->device.'orderPayment',compact('order','active'));
    }
    
    public function orderPaymentSend($type,$id){
         $order =Order::find($id);

        if(!$order){
            Session::flash('error','This Order Invoic Are Not Found');
            return redirect()->route('customer.myOrders');
        }
        $general =General::first();
        $user =Auth::user();
        
        if($type=='wallet'){
            
            if($order->due_amount > $user->balance){
                Session::flash('error','Your Wallet Balance Are Not available.Please Re-charge.');
                return redirect()->route('customer.myOrders');
            }
            
            $balance =new Transaction();
            $balance->type=0;
            $balance->order_id=$order->id;
            $balance->user_id=$user->id;
            $balance->billing_name=$order->name;
            $balance->billing_mobile=$order->mobile;
            $balance->billing_email=$order->email;
            $balance->billing_address=$order->address;
            $balance->billing_note='Customer pay bill by wallet method.';
            $balance->transection_id=mt_rand(100000,999999).'TSBD'.$order->id;
            $balance->payment_method='wallet';
            $balance->amount=$order->due_amount;
            $balance->currency=$general->currency;
            $balance->status='success';
            $balance->addedby_id=Auth::id();
            $balance->save();

            $general->balance+=$balance->amount;
            $general->save();

            $user->balance -=$balance->amount;
            $user->save();

            $order->paid_amount +=$balance->amount;

            if($order->paid_amount >=$order->grand_total){
            $order->extra_amount=$order->paid_amount - $order->grand_total;
            $order->due_amount=0;
            
            }else{
            $order->extra_amount=0;
            $order->due_amount=$order->grand_total-$order->paid_amount;
            }
            
            if($order->due_amount==0){
            $order->payment_status='paid';
            }elseif($order->due_amount==$order->grand_total){
            $order->payment_status='unpaid';
            }else{
            $order->payment_status='partial';
            }

            $order->payment_method='wallet';
            $order->save();
            
            foreach($order->items as $item){
                $item->payment_status=$order->payment_status;
                $item->save();
            }
            
            //send SMS
            
            //Send Mail
            
            Session::flash('success','You Order Is Successfully Place. Thank You for Shopping!');
            return redirect()->route('customer.orderDetails',$order->id);
             
        }else if($type=='handcash'){
            
            $order->payment_method='Cash On Delivery';
            $order->save();
            
            Session::flash('success','You Order Is Successfully Place. Thank You for Shopping!');
            return redirect()->route('customer.orderDetails',$order->id);
            
        }else{
            
             Session::flash('error','Worng Paydment Method is not Allow');
            return redirect()->route('customer.myOrders');
        }
    }
    
    


    public function selectDeliveryArea(Request $r,$id){

    	if($r->ajax())
        {

	    	$cookie = $r->cookie('carts');

	    	if($cookie)
	    	{
    			$general =General::first();
    			$carts = Cart::where('cookie', $cookie)->select(['id','product_id', 'quantity','color','size'])->latest()->paginate(500);
    			
    			$deliveryCharge =0;
        	    $deliveryChargeIn =(int)($general->indhaka_charge?:0);
        	    $deliveryChargeOut =(int)($general->outofdhaka_charge?:0);
        	    $dhaka=0;
        	    
        	    foreach($carts as $cart){
        	        $productShippingIn =$cart->product?$cart->product->shipping_cost:0;
                    $deliveryChargeIn += $cart->quantity*$productShippingIn;
                    
                    $productShippingOut =$cart->product?$cart->product->shipping_cost2:0;
                    $deliveryChargeOut += $cart->quantity*$productShippingOut;
                    
        	    }
        	    
        	    if($id==15){
            		$dhaka =1;
            		$deliveryCharge =$deliveryChargeIn;
            	}elseif($id==0 || $id==null){
            	   $dhaka=0;
            	}else{
            	    $dhaka =2;
            	    $deliveryCharge =$deliveryChargeOut;
            	}
    			
	    		
	    		$datas=Country::where('parent_id',$id)->get();
	    		$geoData = View('geofilter',compact('datas'))->render();


		    	$cartTotalPrice = 0;
		    	$couponDisc = 0;

		    	foreach ($carts as $cart) 
                {
                    
                    $cartTotalPrice += $cart->subtotal();

                }

                if ($mci = Session::get('my_coupon_id')) 
                {
                    $mc = Coupon::where('id',$mci)->first();

                    if($mc)
                    {
                      $couponDisc = $mc->discountFor($cartTotalPrice);
                    }
                }

                $grandTotal = $cartTotalPrice - $couponDisc;

		    	$cartSummery =view($this->device.'.includes.orderSummery',compact('carts','cartTotalPrice','grandTotal','couponDisc','dhaka','deliveryCharge','deliveryChargeIn','deliveryChargeOut'))->render();

    			return Response()->json([
			            'success' => true,
			            'geoData' =>$geoData,
			            'cartSummery' => $cartSummery,
			            'grandTotal' => $grandTotal+$deliveryCharge,
			          ]);

    		}



    	}


    }




    public function wishlistCompareUpdate(Request $r,$id,$type)
	{
		if($r->ajax())
        {
			
			$product =Post::find($id);
			$cookie = $r->cookie('carts');
			if($cookie && $product && $type=='compare' || $type=='wishlist')
			{
					if($type=='wishlist'){
						$statusType =0;
						$overCount =48;
					}else{
						$statusType =1;
						$overCount =20;
					}
					
					$oldData = WishList::where('cookie', $cookie)->where('type',$statusType)->where('product_id', $product->id)->first();
			 		if($oldData)
			 		{
			 			$oldData->delete();

			 			$status =false;
			 			$alert=false;
			 			
			 		}else{

			 			$totalCount =WishList::where('cookie',$cookie)->where('type',$statusType)->count();

			 			if($overCount > $totalCount){
			 				$data = new WishList;
				 			$data->user_id = Auth::id();
				 			$data->product_id = $product->id;
				 			$data->cookie = $cookie;
				 			$data->type =$statusType;
				 			$data->save();

			 				$status =true;
			 				$alert=false;

			 			}else{
			 				$status =false;
			 				$alert=true;
			 			}
			 			
			 		}

			 		if($type=='wishlist'){
						$wlCount =WishList::where('cookie',$cookie)->where('type',$statusType)->count();
						
						$products = Post::whereHas('wishlists',function($qq)use($cookie){
					 		$qq->where('cookie', $cookie);
					 	})->paginate(24);

						$itemsView = view($this->device.'.includes.wishlistItems',compact('products','wlCount'))->render();

					}else{
					    
						$cpCount =WishList::where('cookie',$cookie)->where('type',$statusType)->count();
						
						$products = Post::whereHas('comparelists',function($qq)use($cookie){
					 		$qq->where('cookie',$cookie);
					 	})->paginate(24);

						$itemsView = view($this->device.'.includes.compareItems',compact('products','cpCount'))->render();
					}


			 		return Response()->json([
						        'success' => true,	
						        'status' => $status,
						        'alert' => $alert,
						        'statusType' => $statusType,
						        'count' => WishList::where('cookie',$cookie)->where('type',$statusType)->count(),		        
						        'itemsView' => $itemsView,	        
						    ]);

			}
		}
		
	}


	public function myWishlist(Request $r){
            
            $user =Auth::user();
            $cookie =Cookie::get('carts');
			if($user && $cookie){
			    WishList::where('cookie','<>',$cookie)->where('user_id',$user->id)->where('type',0)->delete();
			}
			
			$products = Post::whereHas('wishlists',function($qq){
			 		$qq->where('cookie', Cookie::get('carts'));
			 	})->paginate(24);
            
			return view($this->device.'myWishlist',compact('products'));
	}


	public function myCompare(Request $r){
            
            
            $user =Auth::user();
            $cookie =Cookie::get('carts');
			if($user && $cookie){
			    WishList::where('cookie','<>',$cookie)->where('user_id',$user->id)->where('type',1)->delete();
			}
            
			$products = Post::whereHas('comparelists',function($qq){
					 		$qq->where('cookie', Cookie::get('carts'));
					 	})->paginate(12);

			return view($this->device.'myCompare',compact('products'));
	}
	
	public function OrderTrack(Request $r){
	   // return $r;
	   $order =Order::latest()->where('invoice',$r->invoice)->first();
	   
	    return view($this->device.'orderTrack',compact('r','order'));
	}


    public function orderNow(Request $r,$id){
        $product =Post::find($id);
        if(!$product){
            Session::flash('error','Product Not Found');
            return redirect()->route('index');
        }
        $check = $r->validate([
            'name' => 'required|max:100',
            'email' => 'nullable|max:100',
            'transection' => 'nullable|max:100',
            'payment_method' => 'required|max:100',
            'mobile' => 'required|numeric',
            'address' => 'required|max:500',
        ]);

        if(!$check){
            return back();
        }
        
        $user =Auth::user();
        
        $order =new Order();
       $order->save();
       $order->invoice=$order->created_at->format('ymd').$order->id;
       $order->user_id=$user?$user->id:null;
       $order->name=$r->name;
       $order->mobile=$r->mobile;
       $order->email=$r->email;
       $order->address=$r->address;
      
       $addr =$order->address;

       $order->full_address=$addr;
       
       $order->order_status='pending';
       $order->pending_at=Carbon::now();
       $order->pending_by=$user?$user->id:null;
       $order->save();

            $item = new OrderItem;
      		$item->order_id = $order->id;
            $item->user_id = $user?$user->id:null;
            $item->invoice = $order->invoice;
            $item->seller_id = $product->seller_id;
            $item->product_id = $product->id;
            $item->product_name = $product->title;
            $item->quantity = 1;

            if($product->price_variation){
                
            }else{
                if($product->quantity > $item->quantity){
                    $product->quantity-=$item->quantity;
                    $product->sell_count+=1;
                    $product->save();
                }
            }

            $item->price = $product->final_price;
            $item->total_price = $product->final_price;
        
            $item->final_price = $product->final_price;
            $item->pending_at = Carbon::now();
            $item->pending_by =null;
            $item->addedby_id =null;
            $item->status='pending';
            $item->order_status='pending';
            $item->seller_paid=$item->final_price;
            
            $key =$order->invoice;
            if($order->name){
            $key.=' '.$order->name;
            }
            if($order->mobile){
            $key.=' '.$order->mobile;
      	    }
      	    if($order->email){
            $key.=' '.$order->email;
            }
            $item->seller_paid=$item->final_price;
            $item->search_key=$key;
            $item->save();

        $general =General::first();

        $order->total_price=$item->final_price;
        $order->grand_total =$item->final_price;
        $order->paid_amount=0;
        $order->payment_method=$r->payment_method;
        $order->transection=$r->transection;
        $order->due_amount=$order->grand_total;
        $order->save();
        
        // $order =new ProductSize();
        // $order->product_id=$product->id;
        // $order->title=$r->name;
        // $order->email=$r->email;
        // $order->mobile=$r->mobile;
        // $order->address=$r->address;
        // $order->transection=$r->transection;
        // $order->addedby_id=0;
        // $order->save();
        Session::flash('success','Your Order is Success. We are contact as soon as possible.');
        return redirect()->back();
    }













}
