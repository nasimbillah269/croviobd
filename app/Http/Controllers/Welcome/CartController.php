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
        , $_SERVER["HTTP_USER_AGENT"]); 
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
                      $couponDisc = $cartTotalPrice * ($mc->discount / 100);
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

         if($carts->count() > 0){
            $cartTotalPrice = 0; 
            foreach($carts as $cart)
            {
            $cartTotalPrice = $cartTotalPrice + ($cart->quantity * $cart->product->offerPrice());
            }
            
            

            $coupon = Attribute::where('name', $r->coupon_code)
            // ->where('active', true)
            // ->whereDate('date_from', '<=', date('Y-m-d'))
            // ->whereDate('date_to', '>=', date('Y-m-d'))
            ->where('status', 'published')
            // ->where('discount', '>', 0)
            // ->where('minimum_shopping', '<=', $cartTotalPrice)
            ->first();

            if(!$coupon){
            	$r->session()->forget(['my_coupon_id']);
            	return back()->with('info', 'Sorry, your coupon is invalid. Please, try again with another coupon code');
            }
            $r->session()->put(['my_coupon_id'=>$coupon->id]);
            return back()->with('success', 'Your coupon code is valid and successfully added');
        }

        return back()->with('info', 'Sorry, your Cart Is empty.Can not apply Coupon Code.');

    }


    public function carts(Request $r){
        $user =Auth::user();
        if($user){
            $cookie = $r->cookie('carts');
            $carts = Cart::where('cookie','<>',$cookie)->where('user_id',$user->id)->latest()->delete();
        }
        
    	return view($this->device.'cart');

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
        
        $user =Auth::user();
        if($user && $cookie){
            Cart::where('cookie','<>',$cookie)->where('user_id',$user->id)->latest()->delete();
        }
        
	    $afterDay=1;

        $now = Carbon::now();
        $today = Carbon::parse('today 2pm');
        
	    $dayName =$now->format('l');
	    
	    if($dayName=='Sunday'){
            //Sunday Delivery
            if($now->gte($today)){
                $afterDay=3;
            }else{  
                $afterDay=1; 
            }
        }elseif($dayName=='Monday'){
            //Monday Delivery
            $afterDay=2;
        }else{
           if($now->gte($today)){
                $afterDay=2;
            }else{
                $afterDay=1;
            }
        }
        
        $areaId=null;
        
        if($r->ajax() && $r->areaId){
            $areaId =PostExtra::where('type',3)->where('parent_id','<>',null)->where('src_id',$r->areaId)->first();
        }elseif($user){
            $areaId =PostExtra::where('type',3)->where('parent_id','<>',null)->where('src_id',$user->district)->first();
        }
	    
	    if($areaId){
	        
                $day =$areaId->parentId?$areaId->parentId->shipping_charge:0;

    	        if($dayName=='Sunday'){
                    //Sunday Delivery
                    if($now->gte($today)){
    	                if($day < 2){
    	                    $afterDay=3;
    	                }else{
    	                    $afterDay=4;
    	                }
    	            }else{  
    	                if($day < 2){
    	                    $afterDay=1;
    	                }else{
    	                    $afterDay=2;
    	                }
    	            }
                }elseif($dayName=='Monday'){
                    //Monday Delivery
                    if($now->gte($today)){
    	                if($day < 2){
    	                    $afterDay=2;
    	                }else{
    	                    $afterDay=3;
    	                }
    	            }else{
    	                if($day < 2){
    	                    $afterDay=2;
    	                }else{
    	                    $afterDay=3;
    	                }
    	            }
                }else{
                   if($now->gte($today)){
    	                if($day < 2){
    	                    $afterDay=2;
    	                }else{
    	                    $afterDay=3;
    	                }
    	            }else{
    	                if($day < 2){
    	                    $afterDay=1;
    	                }else{
    	                    $afterDay=2;
    	                }
    	            }
            }
	      
	    }
	    
	    $dateArray = ["31/12/2024","01/01/2025", "02/01/2025"];
        if (in_array($now->format('d/m/Y'), $dateArray)) {
            $afterDay +=1;
        }
	    
	    $cities =Country::where('type',2)->where('parent_id',629)->orderBy('name','asc')->get();
	    
	    if($r->ajax() && $r->areaId){

	        $view  =View($this->device.'includes.shippinigAddress',compact('afterDay'))->render();
	        return Response()->json([
              'success' => true,
              'view' => $view,
              'Day' => $day,
              'afterDay' => $afterDay,
              'areaId' => $areaId?:'',
              'parent' => $areaId?$areaId->parentId:'',
            ]);
	    }

    	return view($this->device.'checkout',compact('user','carts','cities','afterDay'));
    }

    public function checkoutPost(Request $r){
        
    	$check = $r->validate([
            'name' => 'required|max:100',
            'mobile' => 'required|max:100',
            'address' => 'required|max:500',
            'delivery_area' => 'nullable|max:50',

        ]);
        

        if(is_numeric($r->name) || filter_var($r->name, FILTER_VALIDATE_EMAIL)){
            return back()->with('nameError', 'This field is required your Name. Not Allow Email or Mobile.');
        }
            
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
        
        

    
        $cookie = $r->cookie('carts');
        
        $cartCount =0;
        $myCart = myCart($cookie);
        $carts = $myCart['carts'];
        if($carts){
            $cartCount =$carts->count();
        }
        
        
        if(1 > $cartCount){
            return redirect()->route('carts')->with('info', 'Sorry, Your Cart Is empty.');
        }
        
       $order =new Order();
       $order->user_id=$user?$user->id:null;
       $order->name=$r->name;
       $order->mobile=$r->mobile;
       $order->area_name=$r->delivery_area;
       $order->address=$r->address;
       $order->note=$r->note;
       $order->order_status='pending';
       $order->pending_at=Carbon::now();
       $order->pending_by=$user?$user->id:null;
       $order->save();

       $cartTotalPrice = 0;
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

        
        $order->total_price= $order->items()->sum('final_price');
        if(general()->tax_status){
            
            $taxT =0;
            $OTotalT =0;
            
            if(is_numeric(general()->tax)){
                $taxT=general()->tax;
            }
            
            if(is_numeric($order->total_price)){
                $OTotalT=$order->total_price;
            }
            
            $order->tax= ($OTotalT*$taxT)/100;
        }else{
            $order->tax=0;
        }
        //Shipping Charge
        
        if($order->area_name=='Inside Dhaka'){
            $shippingCharge=general()->inside_dhaka_shipping_charge;
        }else{
            $shippingCharge=general()->outside_dhaka_shipping_charge;
        }
        
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
        
        $order->shipping_charge =$shippingCharge;
        

        $order->total_price=$order->items->sum('final_price');
        $order->total_items= $order->items->count();
        $order->total_qty= $order->items->sum('quantity');
        $order->grand_total =($order->total_price + $order->shipping_charge + $order->tax) - ($order->coupon_discount+$order->coin_discount);
        $order->paid_amount=0;
        $order->payment_method='Cash On Delivery';
        $order->due_amount=$order->grand_total;
        $order->invoice=$order->created_at->format('dmY').$order->id;
        $order->save();

        
        $general =general();
        //**********Send Mail***************//
            
        if($general->mail_status && $order->email){
            
            try {
                Mail::send('mails.InvoiceMail', ['order' => $order,'general'=>$general], function ($message) use ($order,$general) {
    
                    $message->from($general->mail_from_address,$general->mail_from_name);
    
                    $message->to($order->email,$order->name)
                    ->subject('Order Completed in '.$general->mail_from_name.".");
                });
            } catch (\Exception $e) {
                
            }
            

        }

        //**********Send Mail***************//
        if(Auth::check()){
        return redirect()->route('customer.orderDetails',$order->id)->with('success', 'Order successfully submited');
        }else{
        return redirect()->route('invoiceView',$order->invoice)->with('success', 'Order successfully submited');
        }

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
                      $couponDisc = $cartTotalPrice * ($mc->discount / 100);
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
			    WishList::where('cookie','<>',$cookie)->where('type',0)->delete();
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
			    WishList::where('cookie','<>',$cookie)->where('type',0)->delete();
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
