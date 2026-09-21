<?php

namespace App\Http\Controllers\Api;


use Auth;
use Str;
use Hash;
use url;
use File;
use Session;
use Cookie;
use Carbon\Carbon;
use Redirect,Response;
use Validator;
use App\Models\User;
use App\Models\Cart;
use App\Models\Post;
use App\Models\Media;
use App\Models\WishList;
use App\Models\PostExtra;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturnItem;
use App\Models\Review;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiUserController extends Controller
{
    
    public function __construct(Request $request)
    {
    	//$token = $request->header('authorization');
    	$token = $request->bearerToken();
    	
        if($token){
            $user = User::where('api_token','<>',null)->where('api_token',$token)->first();
            
            if($user) {
                $this->myUser =$user;
            }else{
                $this->myUser ='unAuthorize';
            }
            
        }else{
            $this->myUser ='tokenExprice';
        }
        
    }
    
    public function profile(Request $request){
        $user = $this->myUser;
        
        if($request->isMethod('post')){
            
            $rules = [
                'name' => 'required|max:50|unique:users,name,'.$user->id,
                'email' => 'required|max:100|unique:users,email,'.$user->id,
                'mobile' => 'nullable|max:20|unique:users,mobile,'.$user->id,
                'company' => 'nullable|max:191',
                'city_name' => 'nullable|max:191',
                'postal_code' => 'nullable|max:20',
                'address' => 'nullable|max:191',
                'prefecture' => 'required|numeric',
                //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return response()->json(['message'=>'The given data was invalid.','errors'=>$validator->messages()], 422);
            }
            
            
            $user->name =$request->name;
            $user->mobile =$request->mobile;
            $user->email =$request->email;
            $user->company_name =$request->company;
            $user->district =$request->prefecture;
            $user->city_name =$request->city_name;
            $user->address_line1 =$request->address;
            $user->postal_code =$request->postal_code;
            
            ///////Image Uploard Start////////////
            if($request->hasFile('image')){
             $file=$request->image;
             
             $media =Media::latest()->where('src_type',6)->where('use_Of_file',1)->where('src_id',$user->id)->first();
              if(!$media){
              $media =new Media();
              }else{
    
                if(File::exists(public_path($media->file_url))){
                      File::delete(public_path($media->file_url));
                  }
              }
              $name = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
              $fullname = basename($file->getClientOriginalName());
              $ext =$file->getClientOriginalExtension();
              $size =$file->getSize();
    
              $year =carbon::now()->format('Y');
              $month =carbon::now()->format('M');
              $folder = $month.'_'.$year;
    
              $img =time().'.'.uniqid().'.'.$file->getClientOriginalExtension();
              $path ="medies/".$folder;
              $fullpath ="medies/".$folder.'/'.$img;
              $media->src_type=6;
              $media->use_Of_file=1;
              $media->src_id=$user->id;
              $media->file_name=Str::limit($fullname,250);
              $media->alt_text=Str::limit($name,250);
              $media->file_size=$size;
              $media->file_type=1;
              $file->move(public_path($path), $img);
              $media->file_url =$fullpath;
              $media->addedby_id=auth::id();
              $media->save();
    
          }
          
          ///////Image Uploard End////////////
          $user->save();
          
          return response()->json(['message'=>'Your Profile Are Successfully Updated']);

        }
        
        
        $myUser=array(
                    'id'=>$user->id,
                    'name'=>$user->name,
                    'id'=>$user->id,
                    'email'=>$user->email,
                    'mobile'=>$user->mobile,
                    'image'=>$user->image(),
                    'prefecture'=>$user->districtN?$user->districtN->name:null,
                    'city_name'=>$user->city_name,
                    'company_name'=>$user->company_name,
                    'address'=>$user->address_line1,
                    'postal_code'=>$user->postal_code,
                    'fulladdress'=>$user->fullAddress(),
                );
       return response()->json($myUser);
    }
    
    public function changePassword(Request $request){
     
        $user =$this->myUser;
        
        $rules = [
          'current_password' => 'required|string|min:5',
          'password' => 'required|string|min:8|different:current_password',
        ];
        
        $userPassw =User::find($user['id']);
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
         return response()->json(['message'=>'The given data was invalid.','errors'=>$validator->messages()], 422);
        } else {
            
          if(Hash::check($request->current_password, $userPassw->password)){
              $userPassw->password=Hash::make($request->password);
              $userPassw->password_show=$request->password;
              $userPassw->save();
              return response()->json(['message'=>'Password Update Successfully Done!']);
            }else{
            return response()->json(['message'=>'Carrent Password Are Not Match'], 422);
            }  
           
        }
        
    }
    
    
    public function orders(Request $request){
        
        $user = $this->myUser;
        
        $orders = $user->orders()->where(function($q)use($request){
               if($request->status){
                  $q->where('order_status',$request->status);
               }
           })
           ->select(['id','invoice','user_id','name','mobile','email','district','city_name','address','note','delivery_date','delivery_time','product_type','total_items','total_qty','total_price','shipping_charge','tax','grand_total','paid_amount','due_amount','order_status','payment_status','created_at','pending_at','confirmed_at','shipped_at','delivered_at','cancelled_at'])
           ->paginate(20)->appends (['status' => $request->status] );
        
        
        
        collect($orders->items())->map(function($item){
            $item->totalLiterWeight=$item->TotalLiterUnit()>0?$item->TotalLiterWeight():null;
            $item->totalGramWeight=$item->TotalGramUnit()>0?$item->TotalGramWeight():null;
            $item->fulladdress=$item->fullAddress();
            $item->total_items=(string)$item->total_items;
            
            $Ptext ='MIX PRODUCTS';
            if($item->product_type==1){
            $Ptext ='FROZEN PRODUCTS';
            }elseif($item->product_type==2){
            $Ptext ='DRY PRODUCTS';
            }
            $item->product_type_text=(string)$Ptext;
            $item->total_qty=(string)$item->total_qty;
            $item->total_price=priceFormat($item->total_price);
            $item->shipping_charge=priceFormat($item->shipping_charge);
            $item->tax=priceFormat($item->tax);
            $item->grand_total=priceFormat($item->grand_total);
            $item->paid_amount=priceFormat($item->paid_amount);
            $item->due_amount=priceFormat($item->due_amount);
            
            if($item->shipping_name==null){
                $item->shipping_name=$item->name;
            }
            if($item->shipping_last_name==null){
                $item->shipping_last_name=$item->last_name;
            }
            if($item->shipping_company==null){
                $item->shipping_company=$item->company_name;
            }
            if($item->shipping_mobile==null){
                $item->shipping_mobile=$item->mobile;
            }
            
            $item->shipping_email=$item->email;
    
            
            if($item->shipping_address==null){
                $item->shipping_address=$item->fullAddress();
            }
            
            $items =$item->items()->select(['id','product_id','product_name','quantity','price','total_deal_discount','total_coupon_discount','total_price','final_price','shipping_cost','total_weight'])->get();
        
            collect($items)->map(function($item){
                $item->weight =$item->product?$item->product->productWeight():null;
                $item->product_image =$item->product?$item->product->image():null;
                $item->product_slug =$item->product?$item->product->slug:null;
                $item->price =(string) priceFormat($item->price);
                $item->total_deal_discount =(string) priceFormat($item->total_deal_discount);
                $item->total_price =(string) priceFormat($item->total_price);
                $item->final_price =(string) priceFormat($item->final_price);
                $item->shipping_cost =(string) priceFormat($item->shipping_cost);
                unset($item->product);
                return $item;
            });
            
            $item->itemsList=$items;
            
            unset($item->districtN);
            unset($item->items);
            return $item;
        });
        
        
        
        
        return response()->json($orders);
    }
    
    public function orderDetails($id){
        $user = $this->myUser;
        $order =Order::where('user_id',$user->id)->select(['id','invoice','user_id','name','product_type','mobile','email','district','city_name','address','note','delivery_date','delivery_time','total_items','total_qty','total_price','shipping_charge','tax','grand_total','paid_amount','due_amount','order_status','payment_status','created_at','pending_at','confirmed_at','shipped_at','delivered_at','cancelled_at'])
                    ->find($id);

        if(!$order){
            return response()->json(['message'=>'This Order Are Not Found'], 404);
        }
        
        $order->full_address=$order->fullAddress();
        $order->prefecture=$order->districtN?$order->districtN->name:null;
        $order->totalLiterWeight=$order->TotalLiterUnit()>0?$order->TotalLiterWeight():null;
        $order->totalGramWeight=$order->TotalGramUnit()>0?$order->TotalGramWeight():null;
        $Ptext ='MIX PRODUCTS';
        if($order->product_type==1){
        $Ptext ='FROZEN PRODUCTS';
        }elseif($order->product_type==2){
        $Ptext ='DRY PRODUCTS';
        }
        $order->product_type_text=(string)$Ptext;
        $order->total_items=(string)$order->total_items;
        $order->total_qty=(string)$order->total_qty;
        $order->total_price=(string) priceFormat($order->total_price);
        $order->shipping_charge=(string) priceFormat($order->shipping_charge);
        $order->tax=(string) priceFormat($order->tax);
        $order->grand_total=(string) priceFormat($order->grand_total);
        $order->paid_amount=(string) priceFormat($order->paid_amount);
        $order->due_amount=(string) priceFormat($order->due_amount);
        
        
        if($order->shipping_name==null){
            $order->shipping_name=$order->name;
        }
        if($order->shipping_last_name==null){
            $order->shipping_last_name=$order->last_name;
        }
        if($order->shipping_company==null){
            $order->shipping_company=$order->company_name;
        }
        if($order->shipping_mobile==null){
            $order->shipping_mobile=$order->mobile;
        }
        
        $order->shipping_email=$order->email;

        
        if($order->shipping_address==null){
            $order->shipping_address=$order->fullAddress();
        }
        
        
        $items =$order->items()->select(['id','product_id','product_name','quantity','price','total_deal_discount','total_coupon_discount','total_price','final_price','shipping_cost','total_weight'])->get();
        
        collect($items)->map(function($item){
            $item->weight =$item->product?$item->product->productWeight():null;
            $item->product_image =$item->product?$item->product->image():null;
            $item->product_slug =$item->product?$item->product->slug:null;
            $item->price =(string) priceFormat($item->price);
            $item->total_deal_discount =(string) priceFormat($item->total_deal_discount);
            $item->total_price =(string) priceFormat($item->total_price);
            $item->final_price =(string) priceFormat($item->final_price);
            $item->shipping_cost =(string) priceFormat($item->shipping_cost);
            unset($item->product);
            return $item;
        });
        
        $order->itemsList=$items;
        
        unset($order->items);
        unset($order->districtN);
        
        return response()->json($order);
    }
    
    public function orderCancel(Request $request,$id){
        $order =Order::find($id);
        $user = $this->myUser;
        if(!$order){
            return response()->json(['message'=>'This Order Are Not Found'], 404);
        }
        
        $rules = [
          'reason' => 'required|max:100',
          'message' => 'required|max:500',
        ];
        
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
         return response()->json(['message'=>'The given data was invalid.','errors'=>$validator->messages()], 422);
        }
        

        if($order->order_status=='pending'){

        foreach($order->items as $item){
        $item->status='cancelled';
        $item->cancelled_at=Carbon::now();
        $item->cancelled_by=$user->id;
        $item->total_return=$item->quantity;
        
        if($item->product){
                $product =$item->product;
                if($product->price_variation){
                    
                }else{
                    
                    $product->quantity+=$item->quantity;
                    if($product->sell_count > 0){
                    $product->sell_count-=1;
                    }
                    $product->save();
                }
            }
        
        $item->save();

        $cancelItem =OrderReturnItem::where('order_item_id',$item->id)->first();
        if(!$cancelItem){
          $cancelItem =new OrderReturnItem();
          $cancelItem->user_id=$item->user_id;
          $cancelItem->order_id=$item->order_id;
          $cancelItem->order_item_id=$item->id;
          $cancelItem->product_id=$item->product_id;
          $cancelItem->seller_id=$item->seller_id;
          $cancelItem->return_quantity=$item->quantity;
          $cancelItem->sold_quantity=$item->sold_quantity;
          $cancelItem->sold_price=$item->final_price;
          $cancelItem->return_price=$item->final_price;
          $cancelItem->reasion=$request->reason;
          $cancelItem->description=$request->message;
          $cancelItem->status='confirmed';
          $cancelItem->accepted=true;
          $cancelItem->search_key=$item->search_key;
          $cancelItem->confirmed_at=Carbon::now();
          $cancelItem->confirmed_by=$user->id;
          $cancelItem->save();



        }
        
        //Cancel Order SMS Send Seller
    }

    $order->order_status='cancelled';
    $order->cancelled_at=Carbon::now();
    $order->cancelled_by=$user->id;
    $order->cancelled_reason=$request->reason;
    $order->cancelled_msg=$request->message;
    $order->save();

    //Cancel Order SMS Send Admin

    //Cancel Order SMS Send User

    
    if($order->payment_status=='paid'){
        $transection =new Transaction();
        $transection->order_id=$order->id;
        $transection->user_id=$order->user_id;
        $transection->type=2;
        $transection->payment_method='wallet';
        $transection->amount= $order->due_amount;
        $transection->currency='BDT';
        $transection->status='success';
        $transection->save();
            
        $user->balance +=$transection->amount;
        $user->save();

        $general =General::first();
        $general->balacne-=$transection->amount;
        $general->save();
        //Cancel Order Refund Balance To Wallet SMS Send Users

    }

    return response()->json(['message'=>'Your Order Is Successfully  Cancelled']);
    
            
    }else{
        return response()->json(['message'=>'You Can Not Cancel. This order Monitoring by Author.'], 500);
        
    }
     
      
        
    }
    
    public function orderReturn(Request $request,$id){
        $item =OrderItem::find($id);
        $user = $this->myUser;
        if(!$item){
            return response()->json(['message'=>'This Order Item Are Not Found'], 404);
        }
        
        $rules = [
          'qty' => 'required|numeric',
          'returntype' => 'required|max:100',
          'message' => 'required|max:500',
        ];
        
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
         return response()->json($validator->messages(), 500);
        }

        
        if($request->qty > $item->quantity){
         $qty =$item->quantity;
        }else{
        $qty =  $request->qty;  
        }

        
        $order =$item->order;
        
        if(!$order){
           return response()->json(['message'=>'This Item Order Are Not Found'], 404); 
        }
        

        
        if($order->order_status=='delivered' && $order->delivered_at > Carbon::now()->subDays(7)){

              $returnItem = new OrderReturnItem();
              $returnItem->user_id=$item->user_id;
              $returnItem->order_id=$item->order_id;
              $returnItem->order_item_id=$item->id;
              $returnItem->product_id=$item->product_id;
              $returnItem->seller_id=$item->seller_id;
              $returnItem->return_quantity=$qty;
              $returnItem->sold_quantity=$item->quantity;
              $returnItem->sold_price=$item->total_price;
              $returnItem->return_price=$qty*$item->price;
              $returnItem->reasion=$request->returntype;
              $returnItem->description=$request->message;
              $returnItem->status='pending';
              $returnItem->accepted=false;
              $returnItem->return_type=true;
              $returnItem->search_key=$item->search_key;
              $returnItem->pending_at=Carbon::now();
              $returnItem->pending_by=$user->id;
              $returnItem->save();
              
              $item->total_return+=$qty;
              $item->save();
              
            return response()->json(['message'=>'Your return Is Successfully  Done.Wait for Approved.']);

        }else{
           return response()->json(['message'=>'This Order Cannot Returned'], 500); 
        }
        
    }
    
    public function myReviews(){
        
        $user = $this->myUser;
        
        $reviews =$user->reviews()->has('post')->latest()->select(['id','src_id','parent_id','content','rating','created_at'])->paginate(20);
        
        collect($reviews->items())->map(function($item){
            $item->product_name=$item->post?$item->post->name:'No Product';
            $item->product_slug=$item->post?$item->post->name:'No Product';
            $item->product_image=$item->post?$item->post->image():'No Image';
            unset($item->post);
            return $item;
        });
        
        
        return response()->json($reviews);
    }
    
    public function orderReview(Request $request,$id){
        $item = OrderItem::find($id);
        $user = $this->myUser;
        if(!$item){
           return response()->json(['message'=>'This Order Item Are Not Found'], 404);
        }

        $rules = [
          'star' => 'required|numeric|between:1,5',
          'review' => 'required|max:500',
        ];
        
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
         return response()->json(['message'=>'The given data was invalid.','errors'=>$validator->messages()], 422);
        }
        
        $review =Review::where('addedby_id',$user->id)->where('parent_id',$item->id)->first();
    
        if(!$review){
          $review =new Review();
          $review->parent_id=$item->id;
          $review->addedby_id=$user->id;
          $review->save();
        }
        $review->src_id=$item->product?$item->product->id:null;
        $review->rating=$request->star;
        $review->content=$request->review;
        $review->save();
        
        return response()->json(['message'=>'Your Are Send Successfully Review']);
        
    }
    
    
    
    public function addToCart(Request $reqeust,$id){
     
        $product = Post::where('type',2)
                	->where('status','active')
                    ->where('stock_status',true)
                    ->find($id);
        $user =$this->myUser;
        if(!$product){
            return response()->json(['message'=>'This Product Are Not Found'], 404);
        }
        $qty =1;
        if($reqeust->qty){
           $qty =$reqeust->qty;
        }
        
       $cart = Cart::where('user_id', $user->id)->where('product_id', $product->id)->first();
    		
		if($cart)
		{
            $cart->user_id = $user->id;
            $cart->quantity += $qty;
            $cart->save(); 
		}
		else
		{
		    $cart = new Cart;
            $cart->addedby_id = $user->id;
            $cart->trans_date = date("Y-m-d");
            $cart->user_id = $user->id;
            $cart->product_id = $product->id;
            $cart->quantity = $qty >= $product->min_order_quantity ? $qty : $product->min_order_quantity;
            $cart->save();
		}
        
        return response()->json(['message'=>'Product successfully added to cart']);
        
    }
    
    public function cartUpdate(Request $reqeust,$id,$type){
        $user =$this->myUser;
        $cart = Cart::where('user_id',$user->id)->find($id);
        if(!$cart){
           return response()->json(['message'=>'This Cart Item Are Not Found'], 404);
        }
        
        if($type == 'increment')
    	{
    	    $qty = $cart->quantity + 1;
    		$maxLimit = $cart->product->max_order_quantity ?: $cart->product->quantity;
    		if($qty <= $maxLimit){
    			$cart->update(['quantity'=> $qty]);
    			return response()->json(['message'=>'Cart Quantity Update Success']);

    		}else{
    			return response()->json(['message'=>'Product Quantity Are Not Available'], 404);
    		}

    	}elseif($type == 'decrement'){
    	    $qty = $cart->quantity - 1;
    		$minLimit = $cart->product->min_order_quantity ?: 1;
    		if($qty >= 1 && $qty >= $minLimit){
    			$cart->update(['quantity'=> $qty]);
    			return response()->json(['message'=>'Cart Quantity Update Success']);

    		}else{
    			return response()->json(['message'=>'Product Quantity Are Not Change'], 404);
    		}
    		
    	}elseif($type == 'quantity'){
    	    
    	    $qty =$reqeust->qty?:1;
    	    $maxLimit = $cart->product->max_order_quantity ?: $cart->product->quantity;
    	    $minLimit = $cart->product->min_order_quantity ?: 1;
    		if($qty <= $maxLimit && $qty >= $minLimit && $qty >= 1){
    			$cart->update(['quantity'=> $qty]);
    			return response()->json(['message'=>'Cart Quantity Update Success']);

    		}else{
    			return response()->json(['message'=>'Product Quantity Are Not Change'], 404);
    		}
    	    
    	    
    	}elseif($type == 'delete'){
    	    $cart->delete();
    	    return response()->json(['message'=>'Cart Item Deleted Success']);
    	}else{
    	    return response()->json(['message'=>'Unknown Type Action Not Allow'], 404);
    	}
        
    }
    
    
    
    public function carts(Request $request){
        
        $user =$this->myUser;
        $carts = Cart::where('user_id', $user->id)->latest()->select(['id','product_id','quantity','coupon_id'])->get();
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
        
        $shippingCharge=general()->defult_shipping_charge?:0;
        $frozen = Cart::where('user_id', $user->id)->where('product_type',1)->count();
        $Dry = Cart::where('user_id', $user->id)->where('product_type',2)->count();
        
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
        
        collect($carts)->map(function($item){
    	    $item->product_name=$item->product?$item->product->name:'No Product';
    	    $item->product_slug=$item->product?$item->product->slug:'No Product';
    	    $item->product_image=$item->product?$item->product->image():'No Image';
    	    $item->product_price=priceFullFormat($item->product?$item->product->offerPrice():0);
    	    $item->total_price=priceFullFormat(($item->product?$item->product->offerPrice():0)*$item->quantity);
    	    $item->weight=$item->product?$item->product->productWeight():null;
            unset($item->product);
    	    return $item;
    	});
        
        
        return response()->json(['subTotal' => priceFullFormat($cartTotalPrice),'shipping'=>priceFullFormat($shippingCharge),'tax'=>priceFullFormat($cartTax),'grandTotal'=>priceFullFormat($grandTotal),'carts'=>$carts]);
        
    }
    
    public function cartsCharge(Request $request){
        $shippingCharge =0;
        $tax =0;
        
        $cartTotalPrice = 0;
    	$couponDisc = 0;
    	$frozen =0;
    	$Dry=0;
    	
    	$data = json_decode($request->getContent());
        $carts=array();
        if(isset($data->cart)){
            $carts = $data->cart;
        }
        
        if(count($carts)>0)
        {
            foreach ($carts as $cart) 
            {
                
                $id =isset($cart->product_id)?$cart->product_id:0;
                $product =Post::where('type',2)->find($id);
                if($product){
                    $cartTotalPrice +=$product->offerPrice()*$cart->quantity;
                    
                    if($product->itemType()==1){
                        $frozen +=1;
                    }
                    
                    if($product->itemType()==2){
                        $Dry +=1;
                    }
                    
                }
            }
        }
        
        $shippingCharge=general()->defult_shipping_charge?:0;
        
        
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
        $tax =0;
        
        if(general()->tax_status){
            $taxT =0;
            $OTotalT =0;
            
            if(is_numeric(general()->tax)){
                $taxT=general()->tax;
            }
            
            if(is_numeric($cartTotalPrice)){
                $OTotalT=$cartTotalPrice;
            }
            
            $tax=($OTotalT*$taxT)/100;
        }

        return response()->json(['shippingCharge' =>(int)$shippingCharge,'tax'=>(int)$tax]);
    }
    
    public function checkOut(Request $request){
        $user =$this->myUser;
        
        //$carts = Cart::where('user_id', $user->id)->get();
        
        if($request->isMethod('post')){
            
            $data = json_decode($request->getContent());
            $carts=array();
            if(isset($data->cart)){
                $carts = $data->cart;
            }
            

            if(1 > count($carts))
            {
                return response()->json(['message'=>'Sorry, Your Cart Is empty.'], 422);
            }

            $billing=array(
                        'name'=>isset($data->name)?$data->name:'',
                        'company_name'=>isset($data->company_name)?$data->company_name:'',
                        'city_name'=>isset($data->city_name)?$data->city_name:'',
                        'postal_code'=>isset($data->postal_code)?$data->postal_code:'',
                        'prefecture'=>isset($data->prefecture)?$data->prefecture:'',
                        'address'=>isset($data->address)?$data->address:'',
                        'mobile'=>isset($data->mobile)?$data->mobile:'',
                        'email'=>isset($data->email)?$data->email:'',
                        'delivery_date'=>isset($data->delivery_date)?$data->delivery_date:'',
                        'time_slot'=>isset($data->time_slot)?$data->time_slot:'',
                        'agree'=>isset($data->agree)?$data->agree:'',
                        'note'=>isset($data->note)?$data->note:'',
                    );
            
            $rules = [
                'name' => 'required|max:10',
                'company_name' => 'nullable|max:200',
                'postal_code' => 'required|max:20',
                'email' => 'required|max:100',
                'mobile' => 'required|max:100',
                'prefecture' => 'required|numeric',
                'city_name' => 'required|max:200',
                'address' => 'required|max:500',
                'delivery_date' => 'required|date',
                'time_slot' => 'required',
                'agree' => 'required',
            ];
            
            $validator = Validator::make($billing, $rules);
            //$validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return response()->json(['message'=>'The given data was invalid.','errors'=>$validator->messages()], 422);
            }
            
           $order =new Order();
           $order->user_id=$user->id;
           $order->name=$billing['name'];
           $order->mobile=$billing['mobile'];
           $order->email=$billing['email'];
           $order->district=$billing['prefecture'];
           $order->city_name=$billing['city_name'];
           $order->address=$billing['address'];
           $order->postal_code=$billing['postal_code'];
           $order->delivery_date=$billing['delivery_date'];
           $order->delivery_time=$billing['time_slot'];
           $order->note=$billing['note'];
           $order->order_status='pending';
           $order->pending_at=Carbon::now();
           $order->pending_by=$user->id;
           $order->save();
           
           foreach($carts as $cart){
                $id =isset($cart->product_id)?$cart->product_id:0;
                $product =Post::where('type',2)->find($id);
                if($product){
                    $item = new OrderItem;
              		$item->order_id = $order->id;
                    $item->user_id = $user->id;
                    $item->invoice = $order->invoice;
                    $item->product_id = $product->id;
                    $item->product_name = $product->name;
                    $item->quantity = isset($cart->quantity)?$cart->quantity:1;
                    
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
                    $item->price = $product->offerPrice();
                    $item->total_price = $item->price*$item->quantity;
                    $item->final_price = $item->total_price-$item->total_deal_discount;
                
                    $item->pending_at = Carbon::now();
                    $item->pending_by = $user->id;
                    $item->addedby_id = $user->id;
                    $item->status=$order->order_status;
                    $item->order_status=$order->order_status;
                    $item->seller_paid=$item->final_price;
                    $item->save();
                }
            }
    
            
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

            $shippingCharge=general()->defult_shipping_charge?:0;
            $frozen = $order->items->where('product_type',1)->count();
            $Dry = $order->items->where('product_type',2)->count();
            
            $frozenAmount =general()->frozen_amount?:0;
            $dyeAmount =general()->dye_amount?:0;
            $mixAmount =general()->mix_amount?:0;
            
            $order->product_type =0;
            
            if($frozen > 0 && $Dry==0 && $order->items->sum('final_price') >=$frozenAmount || $Dry > 0 && $frozen==0 && $order->items->sum('final_price') >=$dyeAmount || $Dry > 0 && $frozen > 0 && $order->items->sum('final_price') >=$mixAmount){
              $shippingCharge = 0;
            }
            
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
    
            $order->grand_total =($order->total_price + $order->shipping_charge + $order->tax) - $order->coupon_discount;
            $order->paid_amount=0;
            $order->payment_method='Cash On Delivery';
            $order->due_amount=$order->grand_total;
            $order->invoice=$order->id;
            $order->save();
            
            return response()->json(['message'=>'Your Order Successfully Placed. Please Check you Email']);
        }
        
        
        $address =array(
                'name'=>$user->name,
                'company'=>$user->company_name,
                'city_name'=>$user->city_name,
                'postal_code'=>$user->postal_code,
                'prefecture'=>$user->district,
                'address'=>$user->address,
                'mobile'=>$user->mobile,
                'email'=>$user->email,
            );
            
        $timeslut = array(
                '09:00 - 12:00',
                '09:00 - 21:00',
                '14:00 - 16:00',
                '16:00 - 18:00',
                '19:00 - 21:00',
            );
        
        $offDay=(int) general()->weekend_holyday;
        $limitDays=30;
        $cities =Country::where('type',2)->where('parent_id',629)->orderBy('name','asc')->select(['id','name'])->get();
        $afterDay=1;
        // today at 8pm
        $today = Carbon::parse('today 2pm');
        // Now
        $now = Carbon::now();
        
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
        
        $districtID =$request->areaId?:$user->district;
        
        $areaId =PostExtra::where('type',3)->where('parent_id','<>',null)->where('src_id',$districtID)->select(['id','src_id'])->first();
        $areaIDs=null;
        if($areaId){
            $areaIDs=$areaId->src_id;
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
        //$afterDay=1;
        return response()->json(['id'=>$user->id,'address'=>$address,'areaId'=>$areaIDs,'prefecture'=>$cities,'timeslut'=>$timeslut,'offDay'=>$offDay,'limitDay'=>$limitDays,'afterDay'=>$afterDay]);
    }
    
    
    public function myWishlist(){
        $user =$this->myUser;
        $products = Post::whereHas('wishlists',function($qq) use($user){
			 		$qq->where('user_id', $user->id);
			 	})->select(['id','name','slug','short_description','regular_price','sku_code','quantity'])->paginate(24);
        
        collect($products->items())->map(function($item){
            $item->offerPrice=$item->offerPrice();
            $item->image=$item->image();
            unset($item->imageFile);
            return $item;
        });
        
        return response()->json($products);
    }
    
    public function myCompare(){
        $user =$this->myUser;
        $products = Post::whereHas('comparelists',function($qq) use($user){
			 		$qq->where('user_id', $user->id);
			 	})->select(['id','name','slug','short_description','regular_price','sku_code','quantity'])->paginate(24);
        
        collect($products->items())->map(function($item){
            $item->offerPrice=$item->offerPrice();
            $item->image=$item->image();
            unset($item->imageFile);
            return $item;
        });
        
        return response()->json($products);
    }
    
    
    public function wishlistCompareUpdate($id,$type){
     
    $user =$this->myUser;

    if($type=='wishlist'){
		$statusType =0;
		$overCount =20;
		$text ='wishlist';
	}else{
		$statusType =1;
		$overCount =20;
		$text ='Compare';
	}
    $product =Post::latest()->where('type',2)->where('status','active')->find($id);
    if($product){
        $data = WishList::where('user_id',$user->id)->where('type',$statusType)->where('product_id',$product->id)->first();
         if(!$data){
             
            $totalCount =WishList::where('user_id',$user->id)->where('type',$statusType)->count();

			if($overCount > $totalCount){
             $data =new WishList();
             $data->user_id=$user->id;
             $data->type=$statusType;
             $data->product_id=$product->id;
             $data->save();
             return response()->json(['message'=> $text.' Product Are Added']);
			}else{
			 return response()->json(['message'=> $text.' Can not over Add '.$overCount.'+ Products']);
			}
         }else{
            $data->delete();
            return response()->json(['message'=> $text.' Product Are Deleted']);
         }
    }else{
        WishList::where('user_id',$user->id)->where('type',$statusType)->where('product_id',$id)->delete();
        return response()->json(['message'=>'This Product Are Not Found'], 404);
    }
    

     
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function logOut(Request $request){
        
        if ($request->isMethod('post'))
        {
            $user = $this->myUser;
            
            if($user=='tokenExprice'){
                return response()->json(['message'=>'Your Login Token Are Expire/Not Found'],401);
            }elseif($user=='unAuthorize'){
                return response()->json(['message'=>'Your Login Access Are Not Allow'],401);
            }else{
            $user->api_token=null;
            $user->save();
            return response()->json(['message'=>'Your Are Log-out Successfully Done']);
            }
        }
        
        return response()->json(['message'=>'This Method Request Are Not Allow'],405);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}











