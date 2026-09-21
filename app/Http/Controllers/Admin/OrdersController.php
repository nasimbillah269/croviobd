<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Str;
use Hash;
use Mail;
use File;
use Http;
use DB;
use Session;
use Cookie;
use Validator;
use Redirect,Response;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;
use App\Models\Transaction;
use App\Models\PostExtra;
use App\Models\Review;
use App\Models\General;
use App\Models\Country;
use App\Models\Media;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturnItem;
use App\Models\Attribute;
use App\Models\Permission;
use App\Models\PostAttribute;
use GuzzleHttp\Client;
use App\Mail\RegistrationMail;

use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

	//POS Orders Route Start
    public function posOrders(Request $r,$status=null){
        
        if($status==null || $status=='pending-payment' || $status=='unpaid' || $status=='pending' || $status=='confirmed' || $status=='shipped' || $status=='delivered' || $status=='cancelled'){

            $orders =Order::latest()->where('order_type','pos_order')
            ->where(function($qq)  use ($status,$r)  {

                if($status==null){
                    $qq->where('order_status','delivered');
                }else if($status=='pending-payment'){
                    $qq->where('payment_method',null);
                }else if($status=='unpaid'){
                    $qq->whereIn('payment_status',['unpaid','partial']);
                }else{
                    $qq->where('order_status',$status);
                   
                }
                
                if($r->search){
                   $qq->where('invoice','LIKE','%'.$r->search.'%')->orWhere('email','LIKE','%'.$r->search.'%')->orWhere('mobile','LIKE','%'.$r->search.'%'); 
                }
                
                if($r->startDate || $r->endDate)
                {
                    if($r->startDate){
                        $from =$r->startDate;
                    }else{
                        $from=Carbon::now()->format('Y-m-d');
                    }

                    if($r->endDate){
                        $to =$r->endDate;
                    }else{
                        $to=Carbon::now()->format('Y-m-d');
                    }

                    $qq->whereBetween('created_at', [$from, $to]);

                }
                
            })
            ->paginate(25)->appends(['search'=>$r->search,'startDate'=>$r->startDate,'endDate'=>$r->endDate]);

            return view('admin.orders.posOrders',compact('orders','status','r'));
            
            }else{
            Session()->flash('error','Order Status Un-known Type');
            return redirect()->route('admin.posOrders');
        }
        
    }

    public function posOrdersCreate(Request $r){
     
        $order =Order::latest()->where('order_type','pos_order')->where('order_status','temp')->where('pending_by',Auth::id())->first();

        $methods =Attribute::where('type',11)->where('status','active')->where('parent_id',null)->orderBy('view','asc')->get();

        if(!$order){
            $order =new Order();
            $order->order_status='temp';
            $order->pending_by=auth::id();
            $order->pending_at=Carbon::now();
            $order->order_type='pos_order';
            $order->save();
        }
 
        if($order->invoice==null){
           $order->invoice=$order->created_at->format('Ymd').''.$order->id;
           $order->save();
        }
        
        $posMessage=null;
        
        $products =Post::latest()->where('type',2)->where('status','active')
            ->where(function($q) use($r){
                
                if($r->cat){
                    $q->whereHas('productCtgs',function($qq) use($r){
                        $qq->where('reff_id',$r->cat);
                    });
                }
    
                if($r->brand){
                    $q->where('brand_id',$r->brand);
                }
    
                if($r->key){
                    $q->where('name','like','%'.$r->key.'%');
                    $q->orWhere('sku_code','like','%'.$r->key.'%');
                }
    
                if($r->barcode){
                    $q->where('sku_code','like',$r->barcode);
                }
    
            })
            ->select(['id','name','final_price','quantity','sku_code'])
            ->paginate(25)->appends([
              'cat'=>$r->cat,
              'brand'=>$r->brand,
              'key'=>$r->key,
              'barcode'=>$r->barcode,
            ]);
        
        
        if($r->ajax()){
            

                if($r->barcode && $products->count() ==1){

                    $product =$products->first();

                    if($product){
                        
                        $item =OrderItem::where('order_id',$order->id)->where('product_id',$product->id)->first();
                            
                        if(!$item){
                            $item =New OrderItem();
                            $item->order_id =$order->id;
                            $item->invoice =$order->invoice;
                            $item->user_id =$order->user_id;
                            $item->product_id =$product->id;
                            $item->product_name =$product->name;
                        }

                        if($product->quantity > $item->quantity){

                            $item->quantity +=1;
                            $item->price =$product->final_price;
                            $item->total_price=$item->quantity*$item->price;
                            $item->final_price=($item->total_price) - $item->total_deal_discount;
                            $item->order_status=$order->order_status;
                            $item->addedby_id=Auth::id();
                            $item->save();
                        }else{
                            $posMessage ='Product Stock Qty Not Available';
                        }

                    }
                
                }


            if($r->type){

                if($r->type=='addItem' && $r->id){
                    $product =Post::where('type',2)->where('status','active')->find($r->id);

                    if($product){

                        $item =OrderItem::where('order_id',$order->id)->where('product_id',$product->id)->first();
                            
                        if(!$item){
                            $item =New OrderItem();
                            $item->order_id =$order->id;
                            $item->invoice =$order->invoice;
                            $item->user_id =$order->user_id;
                            $item->product_id =$product->id;
                            $item->product_name =$product->name;
                        }

                        if($product->quantity > $item->quantity){

                            $item->quantity +=1;
                            $item->price =$product->final_price;
                            $item->total_price=$item->quantity*$item->price;
                            $item->final_price=($item->total_price) - $item->total_deal_discount;
                            $item->order_status=$order->order_status;
                            $item->addedby_id=Auth::id();
                            $item->save();

                        }else{
                            $posMessage ='Product Stock Qty Not Available';
                        }


                    }

                }


                if($r->type=='itemdelete' && $r->id){
                   $item = OrderItem::find($r->id);
                   if($item){
                      $item->delete();

                   }
                }

                if($r->type=='itemQuantity' && $r->id && $r->qty){
                    
                   $item = OrderItem::find($r->id);
                   
                   if($item){
                    
                    $quantity =$r->qty;
                    if($item->product && $quantity > 0){

                        if($item->product->quantity >= $quantity){
                            $item->quantity=$quantity;
                            $item->price =$item->product->final_price;
                        }else{
                            $posMessage ='Product Stock Qty Not Available';
                        }

                    }

                    $item->total_price=$item->quantity*$item->price;
                    $item->final_price=($item->total_price) - $item->total_deal_discount;
                    $item->order_status=$order->order_status;
                    $item->save();

                   }

                }

                if($r->type=='itemMinus' && $r->id){
                   $item = OrderItem::find($r->id);
                   if($item){
                      
                    if($item->product){

                        if($item->quantity > 1){
                            $item->quantity -=1;
                            $item->price =$item->product->final_price;
                        }else{
                            $posMessage ='Item Quantity Can Not Minus';
                        }

                    }

                    $item->total_price=$item->quantity*$item->price;
                    $item->final_price=($item->total_price) - $item->total_deal_discount;
                    $item->order_status=$order->order_status;
                    $item->save();

                   }
                }

                if($r->type=='itemPlus' && $r->id){
                   $item = OrderItem::find($r->id);
                   if($item){   
                    
                    if($item->product){

                        if($item->product->quantity > $item->quantity){
                            $item->quantity +=1;
                            $item->price =$item->product->final_price;
                        }else{
                            $posMessage ='Product Stock Qty Not Available';
                        }
                    }
                    
                    $item->total_price=$item->quantity*$item->price;
                    $item->final_price=($item->total_price) - $item->total_deal_discount;
                    $item->order_status=$order->order_status;
                    $item->save();
                   }
                }


                if($r->type=='customer' && $r->id || $r->id==0){

                    $order->user_id=$r->id;
                    $order->save();

                    if($order->user){
                     $order->name=$order->user->name;
                     $order->mobile=$order->user->mobile;
                     $order->email=$order->user->email;
                     $order->division=$order->user->division;
                     $order->district=$order->user->district;
                     $order->city=$order->user->city;
                     $order->address=$order->fullAddress();
                    }else{
                     $order->name='Guest';
                     $order->mobile=null;
                     $order->email=null;
                     $order->division=null;
                     $order->district=null;
                     $order->city=null;
                     $order->address=null;
                    }

                }
                
                if($r->type=='discountAmount' && $r->discounttype){
                    $order->discount_type=$r->discounttype?:'Flat';
                    $order->discount=$r->discount?:0;
                }
                
                if($r->type=='shippingAmount'){
                    $order->shipping_charge=$r->discount?:0;
                }
                
                if($r->type=='addPayment' && $r->method && $r->option ){
                    
                   $transection =new Transaction();
                   $transection->src_id =$order->id;
                   $transection->type =3;
                   $transection->user_id =$order->user_id;
                   $transection->status ='success';
                   $transection->method_id=$r->method;
                   $transection->method_option_id=$r->option;
                   $transection->amount =$r->amount?:0;
                   $transection->save();
                    
                }
                
                if($r->type=='deletePayment' && $r->id){
                   $transection = Transaction::find($r->id);
                   if($transection){
                    
                    $transection->delete();
                   }
                    
                }

                


                $order->total_price= $order->items()->sum('final_price');
                if(general()->tax_status){
                    $order->tax= ($order->total_price*general()->tax)/100;
                }else{
                    $order->tax=0;
                }

                
                if($order->discount_type==null){
                    $order->discount_type='Flat';
                }
                
                if($r->type=='discount'){

                    $order->discount_type=$r->discounttype?:'Flat';

                    if($order->discount_type=='Percantage'){
                        $discount =$r->discount?:0;

                        if($discount > 100){
                            $discount=100;
                        }

                        $order->discount=$discount;

                    }else{
                        $discount =$r->discount?:0;

                        if($discount > $order->total_price){
                            $discount=$order->total_price;
                        }
                        $order->discount=$discount;
                    }

                }

                if($order->discount_type=='Percantage'){
                   $order->discount_price=($order->total_price*$order->discount)/100;
                }else{
                   $order->discount_price=$order->discount;
                }
                
                $transections =Transaction::where('src_id',$order->id)->whereIn('type',[0,3])->where('status','success')->get();
                
                $order->paid_amount=$transections->sum('amount');
            
                $order->total_items= $order->items->count();
                $order->total_qty= $order->items->sum('quantity');
                $order->total_purchase= $order->items->sum('purchase_total');
                $order->profit_loss= $order->items->sum('profit_loss');
                $totals =$order->total_price+$order->shipping_charge+$order->tax;
                $order->grand_total= $totals?$totals-$order->discount_price:0;
                if($order->grand_total >= $order->paid_amount){
                    $order->due_amount=$order->grand_total-$order->paid_amount;
                    $order->extra_amount=0;
                }else{
                    $order->due_amount=0; 
                    $order->extra_amount=$order->paid_amount-$order->grand_total; 
                }
                
                if($order->due_amount==0){
                $order->payment_status='paid';
                }elseif($order->due_amount==$order->grand_total){
                $order->payment_status='unpaid';
                }else{
                $order->payment_status='partial';
                }
                $order->save();
                $transections =Transaction::where('src_id',$order->id)->whereIn('type',[0,3])->where('status','success')->get();
                if($order->user_id){
                    foreach($transections as $transection){
                        $transection->user_id =$order->user_id;
                        $transection->save();
                    }
                }
                
                $transections =Transaction::where('src_id',$order->id)->whereIn('type',[0,3])->where('status','success')->get();

                $datas =view('admin.orders.includes.posInvoiceItem',compact('order','posMessage'))->render();
                $payment =view('admin.orders.includes.posInvoicePayments',compact('order','transections','methods','posMessage'))->render();
                
                
                if($r->type=='search'){
                    
                    $search =view('admin.orders.includes.posSearchProduct',compact('products'))->render();

                    return Response()->json([
                        'success' => true,
                        'view' => $search,
                        'viewItems' => $datas,
                        'payment' => $payment,
                        'posMessage' => $posMessage,
                    ]);
                
                }
                
                
                return Response()->json([
                    'success' => true,
                    'view' => $datas,
                    'payment' => $payment,
                    'posMessage' => $posMessage,
                ]);
            }


            
        }
     
        $products =Post::latest()->where('type',2)->where('status','active')
            ->select(['id','name','final_price','quantity','sku_code'])
            ->paginate(100);
        $categories = Attribute::where('type',0)->where('status','<>','temp')->where('parent_id',null)->orderBy('name','asc')->select('id','name')->get();
        $brands = Attribute::where('type',2)->where('status','<>','temp')->where('parent_id',null)->orderBy('name','asc')->select('id','name')->get();

        $users =User::latest()->whereIn('status',[1])->select(['id','name','mobile'])->limit(10)->get();
        
        $transections =Transaction::where('src_id',$order->id)->whereIn('type',[0,3])->where('status','success')->get();
        return view('admin.orders.posOrdersCreate',compact('products','categories','brands','order','transections','methods','posMessage','users'));
    }

    public function posOrdersInvoice(Request $r,$id){

        $order =Order::latest()->where('order_type','pos_order')->find($id);

        if(!$order){
            Session()->flash('error','Order Are Not Found');
            return redirect()->route('admin.posOrders');
        }

        return view('admin.orders.posInvoice',compact('order'));
    }


    public function posOrdersManageUpdate(Request $r,$id){

        $order =Order::latest()->where('order_type','pos_order')->find($id);

        if(!$order){
            Session()->flash('error','Order Are Not Found');
            return redirect()->route('admin.posOrders');
        }

        if($r->ajax() && $r->type){
                
                if($r->type=='clearpos'){
                    $order->transections()->delete();
                    $order->items()->delete();
                    $order->delete();
                }

            return Response()->json([
                'success' => true,
            ]);
        }


        foreach($order->items as $item){
            
            
           
           $product =$item->product;

           if($product){
              if($product->quantity >= $item->quantity){

                    $product->quantity-=$item->quantity;
                    $product->save(); 

              }else{
                 return Response()->json([
                        'success' => false,
                    ]);
              }

           }

        }

        $order->order_status='delivered';
        $order->save();

        return Response()->json([
            'success' => true,
        ]);

    }

    public function posOrdersDelete($id){
        $order =Order::latest()->where('order_type','pos_order')->find($id);

        if(!$order){
            Session()->flash('error','Order Are Not Found');
            return redirect()->route('admin.posOrders');
        }

        $order->transections()->delete();
        
        $order->delete();

        Session()->flash('success','Order Are Successfully delete!');
        return redirect()->back();

    }

    public function posOrdersPaymentsUpdate(Request $r,$id){

        $order =Order::latest()->where('order_type','pos_order')->find($id);
        $posMessage =null;
        $methods =Attribute::where('type',11)->where('status','active')->where('parent_id',null)->orderBy('view','asc')->get();
        if($r->ajax() && $order){

            if($r->type=='method_id' && $r->id){

               $transection = Transaction::find($r->id);
               
               if($transection){
                $transection->method_id=$r->value?:null;
                if($subOption =$transection->methodOptions->first()){
                  $transection->method_option_id=$subOption->id;
                }
                $transection->save();
               }

            }

            if($r->type=='method_option' && $r->id){

               $transection = Transaction::find($r->id);
               
               if($transection){
                $transection->method_option_id=$r->value?:null;
                $transection->save();
               }

            }

            if($r->type=='pay_amount' && $r->id){

               $transection = Transaction::find($r->id);
               
               if($transection){

                $totals = $order->grand_total;
                
                $getSum = $order->transectionsTemp()->whereNotIn('id',[$transection->id])->sum('amount');

                $margeTotal =0;
                $getValue =$r->value?:0;
                $totalSum =$getValue+$getSum;

                if($totals < $totalSum){
                  $margeTotal =$totals - $getSum;
                }else{
                   $margeTotal = $getValue;
                }

                $transection->amount=$margeTotal;
                $transection->save();

               }

            }


            if($r->type=='methodAdd'){

               $transection =new Transaction();
               $transection->src_id =$order->id;
               $transection->type =3;
               $transection->status ='success';
               
               if($option =$methods->first()){
                $transection->method_id=$option->id;
               }

               if($subOption =$transection->methodOptions->first()){
                  $transection->method_option_id=$subOption->id;
               }
               if($order->transectionsSuccess->count()==0){
                $transection->amount =$order->due_amount;
               }
               
               $transection->save();

            }

            if($r->type=='methodDelete' && $r->id){

               $transection = Transaction::find($r->id);
               
               if($transection){
                
                $transection->delete();
               }

            }
            
            if($order->discount_type==null){
                $order->discount_type='Flat';
            }
            


            if($r->type=='discount_amount'){
                
                $order->discount=$r->value?:0;

            }
            
            if($r->type=='discount_type'){
                
                $order->discount_type=$r->value?:'Flat';

            }
            
            if($r->type=='delivery_charge'){
                
                $order->shipping_charge=$r->value?:0;

            }
            
            if($order->discount_type=='Percantage'){
                $discount =$order->discount;

                if($discount > 100){
                    $discount=100;
                }

                $order->discount=$discount;
                $order->discount_price=($order->total_price*$discount)/100;

            }else{
                $discount =$order->discount?:0;

                if($discount > $order->total_price){
                    $discount=$order->total_price;
                }
                $order->discount=$discount;
                $order->discount_price=$discount;
            }
        

            $order->paid_amount=$order->transectionsSuccess->sum('amount');
            
            $order->grand_total= $order->total_price+$order->shipping_charge+$order->tax-$order->discount_price;

            if($order->grand_total >= $order->paid_amount){
                $order->due_amount=$order->grand_total-$order->paid_amount;
                $order->extra_amount=0;
            }else{
                $order->due_amount=0; 
                $order->extra_amount=$order->paid_amount-$order->grand_total; 
            }
            
            if($order->due_amount==0){
            $order->payment_status='paid';
            }elseif($order->due_amount==$order->grand_total){
            $order->payment_status='unpaid';
            }else{
            $order->payment_status='partial';
            }
            $order->save();

           $datas =view('admin.orders.includes.posInvoiceItem',compact('order','posMessage','methods'))->render();
           $payment =view('admin.orders.includes.posInvoicePayments',compact('order','methods','posMessage'))->render();

           return Response()->json([
                    'success' => true,
                    'payment' => $payment,
                    'view' => $datas,
                ]); 

        }

    }


	public function orders(Request $r,$status=null){
	    


        if($status==null || $status=='pending-payment' || $status=='unpaid' || $status=='pending' || $status=='confirmed' || $status=='shipped' || $status=='delivered' || $status=='cancelled'){
            
            if($r->action=='active'){
                if(!$r->status){
                    Session()->flash('error','Action Status Are Not Selected. Please Select Status.');
                }
                
                if(!isset($r->checkid)){
                    Session()->flash('error','Order Check Are Not Selected. Please Check Order any one.');
                }
                
                
                
                if($r->status=='pending' || $r->status=='confirmed' || $r->status=='shipped' || $r->status=='delivered' || $r->status=='cancelled'){
                    
                    for($i=0;$i < count($r->checkid);$i++){
                        $order =$orders =Order::latest()->where('order_type','customer_order')->find($r->checkid[$i]);
                        if($order){
                            if($order->order_status!=$r->status){
                                
                                $order->order_status=$r->status;

                                $orderstatus =$r->status;
                                
                                ////////////Order Status Start
                                if($orderstatus=='pending' || $orderstatus=='confirmed' || $orderstatus=='shipped' || $orderstatus=='delivered' || $orderstatus=='cancelled' ){
                                    $colomnD =$orderstatus.'_at';
                                    $colomnBy =$orderstatus.'_by';
                                    if($order[$colomnD]==null){
                                    $order[$colomnD]=Carbon::now();
                                    }
                                    $order[$colomnBy]=Auth::id();
                                }
                                
                                ////////////Order Item Save Start
                                foreach($order->items as $item){
                                    if($item->order_status=='cancelled'){
                                        if($item->product){
                                                
                                                $product =$item->product;
                                                
                                                if($product->variation_status){
                                                    
                                                }else{
                                                    
                                                    if($product->quantity >=$item->quantity){
                                                        $product->quantity-=$item->quantity;
                                                        $product->sell_count+=1;
                                                        $product->save();
                                                    }
                                                 
                                                }
                                            } 
                                    }else{
                                        
                                        if($order->order_status=='cancelled'){
                                            if($item->product){
                                                $product =$item->product;
                                                if($product->variation_status){
                                                    
                                                }else{
                                                    
                                                    $product->quantity+=$item->quantity;
                                                    if($product->sell_count > 0){
                                                    $product->sell_count-=1;
                                                    }
                                                    $product->save();
                                                }
                                            } 
                                        }
                                        
                                    }
                                    $item->order_status=$order->order_status;
                                    if($order->order_status=='confirmed'){
                                       $item->confirmed_at=$order->confirmed_at;
                                       $item->confirmed_by=$order->confirmed_by;
                                    }elseif($order->order_status=='shipped'){
                                       $item->shipped_at=$order->shipped_at;
                                       $item->shipped_by=$order->shipped_by;
                                    }elseif($order->order_status=='delivered'){
                                       $item->delivered_at=$order->delivered_at;
                                       $item->delivered_by=$order->delivered_by;
                                    }
                                    
                                    $item->save();
                                }
                                
                                ////////////Order Item Save End
                                
                                $order->save();
                                
                            }
                        }

                    }
                    
                }elseif($r->status=='invoice'){
                    
                    return redirect()->route('admin.multiInvoicesView',['invoices'=>$r->checkid]);
                }
                
                Session()->flash('success','Your Action Is Successfully Completed!');
                return redirect()->back();
                
                
            }
            
            
            $orders =Order::latest()->where('order_type','customer_order')
            ->where(function($qq)  use ($status,$r)  {

                if($status==null){
                    $qq->where('order_status','<>','temp');
                }else if($status=='pending-payment'){
                    $qq->where('payment_method',null);
                }else if($status=='unpaid'){
                    $qq->whereIn('payment_status',['unpaid','partial']);
                }else{
                    $qq->where('order_status',$status);
                }
                
                if($r->search){
                   $qq->where('invoice','LIKE','%'.$r->search.'%')->orWhere('email','LIKE','%'.$r->search.'%')->orWhere('mobile','LIKE','%'.$r->search.'%'); 
                }
                
                if($r->startDate || $r->endDate)
                {
                    if($r->startDate){
                        $from =$r->startDate;
                    }else{
                        $from=Carbon::now()->format('Y-m-d');
                    }

                    if($r->endDate){
                        $to =$r->endDate;
                    }else{
                        $to=Carbon::now()->format('Y-m-d');
                    }

                    $qq->whereBetween('created_at', [$from, $to]);

                }

                
            })
            ->paginate(25)->appends(['search'=>$r->search,'startDate'=>$r->startDate,'endDate'=>$r->endDate]);
            
            if(Auth::id()==1){
               // $orders =Order::latest()->where('order_type','customer_order')->where('invoice',null)->paginate(500);
                
                // foreach($orders as $order){
                    
                //     $order->total_price= $order->items()->sum('final_price');
                //     if(general()->tax_status){
                //         $order->tax= ($order->total_price*general()->tax)/100;
                //     }else{
                //         $order->tax=0;
                //     }
                    
                //     $order->total_price=$order->items->sum('final_price');
                //     $order->total_items= $order->items->count();
                //     $order->total_qty= $order->items->sum('quantity');
                //     $order->grand_total =$order->total_price + $order->shipping_charge + $order->tax - $order->coupon_discount;
                //     $order->paid_amount=0;
                //     $order->payment_method='Cash On Delivery';
                //     $order->due_amount=$order->grand_total;
                //     $order->invoice=$order->created_at->format('Ymd').$order->id;
                //     $order->save();

                // }
       
            }
            
            
            return view('admin.orders.ordersAll',compact('orders','status','r'));


        }else{
            Session()->flash('error','Order Status Un-known Type');
            return redirect()->route('admin.orders');
        }

        
    }
    
    
    public function checkCourier(Request $request)
{
    $response = Http::withHeaders([
        'Authorization' => 'Bearer YOUR_API_KEY',
        'Content-Type'  => 'application/json',
    ])->post('https://api.bdcourier.com/courier-check', [
        'phone' => $request->phone, // or '017xxxxxxxx'
    ]);

    if ($response->successful()) {
        $data = $response->json();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'API request failed.',
        'response' => $response->body(),
    ], $response->status());
}
    
    
    
    

    public function ordersManage(Request $r,$id)
    {
        
    
        $order =Order::find($id);
        if(!$order){
            Session()->flash('error','Order Are Not Found');
            return redirect()->route('admin.orders');
        }
        
        if($order->invoice==null || $order->grand_total==0 || $order->payment_method==null){
                
                $order->total_price= $order->items()->sum('final_price');
                if(general()->tax_status){
                    $order->tax= ($order->total_price*general()->tax)/100;
                }else{
                    $order->tax=0;
                }
                
                $order->paid_amount=$order->transectionsSuccess->sum('amount');
                $order->total_items= $order->items->count();
                $order->total_qty= $order->items->sum('quantity');
                $order->total_purchase= $order->items->sum('purchase_total');
                $order->profit_loss= $order->items->sum('profit_loss');
                $totals =$order->total_price+$order->shipping_charge+$order->tax;
                $order->grand_total= $totals?$totals-$order->discount_price:0;
                if($order->grand_total>0){
                    
                    if($order->grand_total >= $order->paid_amount){
                    $order->due_amount=$order->grand_total-$order->paid_amount;
                    $order->extra_amount=0;
                    }else{
                        $order->due_amount=0; 
                        $order->extra_amount=$order->paid_amount-$order->grand_total; 
                    }
                }else{
                    
                    $order->due_amount=0; 
                }
                
                if($order->due_amount==0 && $order->grand_total>0){
                $order->payment_status='paid';
                }elseif($order->due_amount==$order->grand_total){
                $order->payment_status='unpaid';
                }else{
                $order->payment_status='partial';
                }
                if($order->payment_method==null){
                $order->payment_method='Cash On Delivery';
                }
                $order->invoice=$order->id;
                $order->save();
        }
        
        $methods =Attribute::where('type',11)->where('status','active')->where('parent_id',null)->orderBy('view','asc')->get();
        
        $options =null;

        if($method =$methods->first()){
           $options =$method->methodOptions;
        }
        
        if (empty($order->corier_result)) {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer Glyxn7rV0MYTjkEGyXnmnnfKMXQ2zqBTdHGSCKljmCJfJSRB192mJXCy4BXU',
                'Content-Type'  => 'application/json',
            ])->post('https://api.bdcourier.com/courier-check', [
                'phone' => $order->mobile,
            ]);
        
            if ($response->successful()) {
                $order->corier_result = $response->body();
                $order->save();
            }
        }
        
        return view('admin.orders.ordersManage',compact('order','methods','options')); 

    }

    public function ordersManageUpdate(Request $r,$id){
        $order =Order::find($id);
        if(!$order){
            Session()->flash('error','Order Are Not Found');
            return redirect()->route('admin.orders');
        }
        
        
        if($r->actionType=='payment'){
            
            $check = $r->validate([
              'type' => 'required|numeric',
              'amount' => 'required|numeric',
              'method' => 'required|numeric',
              'option' => 'required|numeric',
            ]);
            
            $method =Attribute::where('type',11)->find($r->method);
            $methodOption =Attribute::where('type',11)->find($r->option);
            
            if(!$method || !$methodOption){
                Session()->flash('error','Method Type Are Found');
                return redirect()->back();
            }
            
            if($r->type==2){
            
            if($r->amount > $methodOption->amounts){
                Session()->flash('error','Accounts have Not Avialable Balance!');
                return redirect()->back();
            }
            
                
            }
            
            //New Transection for Order
            $transection =new Transaction();
            $transection->src_id =$order->id;
            if($r->type==2){
                $transection->type =2;
            }else{
               $transection->type =0; 
            }
            
            $transection->status ='success';
            $transection->method_id=$method->id;
            $transection->transection_id=Carbon::now()->format('YmdHis');
            $transection->method_option_id=$methodOption->id;
            $transection->amount=$r->amount;
            $transection->billing_note=$r->note;
            $transection->billing_name=$order->name;
            $transection->billing_mobile=$order->mobile;
            $transection->billing_email=$order->email;
            $transection->billing_address=$order->address;
            $transection->save();
            
            //Method Option Payment Update
            if($transection->type==2){
                $methodOption->amounts -=$transection->amount;
            }else{
                $methodOption->amounts +=$transection->amount;
            }
            
            $methodOption->save();
            
            //Order payment Update
            $order->paid_amount=$order->transectionsSuccess->sum('amount');
            $order->return_amount=$order->transectionsRefund->sum('amount');

            if($order->grand_total >= $order->paid_amount){
                $order->due_amount=$order->grand_total-$order->paid_amount;
                $order->extra_amount=0;
            }else{
                $order->due_amount=0; 
                $order->extra_amount=$order->paid_amount-$order->grand_total; 
            }
            
            if($order->due_amount==0){
            $order->payment_status='paid';
            }elseif($order->due_amount==$order->grand_total){
            $order->payment_status='unpaid';
            }else{
            $order->payment_status='partial';
            }
            $order->save();
            
            Session()->flash('success','Payment Added Successfully Done!');
            return redirect()->back();
            
        }
        
        if($r->actionType=='orderUpdate'){
            
            $check = $r->validate([
              'order_status' => 'required',
              'payment_status' => 'required',
            ]);
            
            $general =General::first();

        
        if($order->order_status==$r->order_status){
             
        }else{
            
            $order->order_status=$r->order_status;
            
            $orderstatus =$r->order_status;
            
            if($orderstatus=='pending' || $orderstatus=='confirmed' || $orderstatus=='shipped' || $orderstatus=='delivered' || $orderstatus=='cancelled' ){
                $colomnD =$orderstatus.'_at';
                $colomnBy =$orderstatus.'_by';
                if($order[$colomnD]==null){
                $order[$colomnD]=Carbon::now();
                }
                $order[$colomnBy]=Auth::id();
            }
            


            foreach($order->items as $item){
                if($item->order_status=='cancelled'){
                    
                    if($item->product){
                                      
                        $product =$item->product;
                        
                        if($product->variation_status){
                            
                        }else{
                            
                            if($product->quantity >=$item->quantity){
                                $product->quantity-=$item->quantity;
                                $product->sell_count+=1;
                                $product->save();
                            }
                         
                        }
                    }
                    
                }else{
                    
                    if($order->order_status=='cancelled'){
                        if($item->product){
                            $product =$item->product;
                            if($product->variation_status){
                                
                            }else{
                                
                                $product->quantity+=$item->quantity;
                                if($product->sell_count > 0){
                                $product->sell_count-=1;
                                }
                                $product->save();
                            }
                        } 
                    }
                    
                }
                $item->order_status=$order->order_status;
                if($order->order_status=='confirmed'){
                   $item->confirmed_at=$order->confirmed_at;
                   $item->confirmed_by=$order->confirmed_by;
                   
                //   if($item->product){
                //         $product =$item->product;
                //         if($product->variation_status){
                            
                //         }else{
                            
                //             if($product->quantity >= $item->quantity){
                                
                //                 $product->quantity-=$item->quantity;
                //                 $product->sell_count+=1;
                //                 $product->save();
                                
                //             }
                            
                //         }
                //     }
                    
                }elseif($order->order_status=='shipped'){
                   $item->shipped_at=$order->shipped_at;
                   $item->shipped_by=$order->shipped_by;
                }elseif($order->order_status=='delivered'){
                   $item->delivered_at=$order->delivered_at;
                   $item->delivered_by=$order->delivered_by;
                }
                
                $item->save();
            }


        }
        
        if($r->payment_status=='paid'){
            $order->payment_status='paid';
            $order->paid_amount=$order->grand_total;
            $order->due_amount=0;
        }else{
            $order->payment_status='unpaid';
            $order->paid_amount=0;
            $order->due_amount=$order->grand_total;
        }
        $order->save();
         
         //Mail Send / SMS Send
        
        //**********Send Mail***************//
        
        if($general->mail_status && $order->email && $r->order_mail){

            Mail::to($order->email)->send(new orderInvoiceMail($order));
            
        }
        
        //**********Send Mail***************//
        
         //**********Send SMS ***************//
            if($general->sms_status && $order->mobile && $r->order_sms){
        
                //Send SMS User
                if($r->order_sms){
                    
                    $m =$order->mobile;
                    
                    $to =bdMobile($m);
                    
                    if(strlen($to) != 13)
                    {
                        return true;
                    }
                    $msg = urlencode("Your order #{$order->invoice} is Successfully {$order->order_status} in {$general->title}. Total Invoice Cost is {$general->currency} {$order->grand_total}."); //150 characters allowed here
        
                    $url = smsUrl($to,$msg);
                
                    $client = new Client();
                    
                    try {
                            $r = $client->request('GET', $url);
                        } catch (\GuzzleHttp\Exception\ConnectException $e) {
                        } catch (\GuzzleHttp\Exception\ClientException $e) {
                        }
                    
                    
                }
                
                //Send SMS Admin
                if($general->order_place_sms_admin && $general->admin_numbers){
                    
                    $m =$general->admin_numbers;
                    $to =bdMobile($m);
                    if(strlen($to) != 13)
                    {
                        return true;
                    }
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
         
            
            
            
        }
        
        if ($r->actionType == 'customerUpdate') {

    $r->validate([
        'name'    => 'required|string|max:255',
        'address' => 'required',
        'area_name' => 'nullable|max:255',
        'order_note' => 'nullable|max:255',
        'admin_note' => 'nullable|max:255',
    ]);

    $order->name = $r->name;
    $order->address = $r->address;
    $order->area_name = $r->area_name;
    $order->note = $r->order_note;
    $order->admin_note = $r->admin_note;

    $order->save();

    Session()->flash('success', 'Customer information updated successfully.');

    return redirect()->back();
}
        
        Session()->flash('error','Unknown Type Action Not Allow!');
        return redirect()->back();
        
    }


    public function invoice($id){
        $order =Order::find($id);
        if(!$order){
            Session()->flash('error','Order Are Not Found');
            return redirect()->route('admin.orders');
        }
        
        
        
        
        return view('admin.orders.invoice',compact('order'));
    }
    
    public function multiInvoicesView(Request $r){
        if($r->invoices){
            $orders =Order::latest()->where('order_type','customer_order')->whereIn('id',$r->invoices)->get();
            return view('admin.orders.multiInvoice',compact('orders'));
        }else{
            Session()->flash('error','Please Check Order any More Invoices');
            return redirect()->route('admin.orders');
        }
    }


    public function returnOrders(Request $r,$status=null){
        
        if($status==null || $status=='pending' || $status=='confirmed' || $status=='delivered' || $status=='refunded' || $status=='cancelled'){
            $returnItems =OrderReturnItem::latest()->where('return_type',true)
            ->where(function($qq)  use ($status,$r)  {

                if($status==null){
                    $qq->where('status','<>','temp');
                }else{
                    $qq->where('status',$status);
                }
            })
            ->paginate(25);

            return view('admin.orders.returnOrders',compact('returnItems','status','r'));
        }else{
            Session()->flash('error','Order Status Un-known Type');
            return redirect()->route('admin.returnOrders');
        }
        
    }

     public function returnOrdersManage($id){
        
        $order =Order::find($id);
        
        if(!$order){
            Session()->flash('error','Order Return Are Not Found');
            return redirect()->route('admin.returnOrders');
        }

        return view('admin.orders.returnOrderManage',compact('order'));
    }


    public function purchaseProducts(Request $r){

        if($r->type){

            if($r->type=='parchase'){

                $order =Order::latest()->where('order_type','purchase_order')->where('pending_by',Auth::id())->where('order_status','temp')->first();

                if(!$order){
                    $order =new Order();
                    $order->order_status='temp';
                    $order->pending_by=auth::id();
                    $order->pending_at=Carbon::now();
                    $order->order_type='purchase_order';
                    $order->save();
                }

                return redirect()->route('admin.purchaseProductsEdit', $order->id);

            }


        }

        $status =null;

      $orders =Order::latest()->where('order_type','purchase_order')
            ->where(function($qq)  use ($r,$status)  {

                if($status==null){
                    $qq->where('order_status','<>','temp');
                }else{
                    $qq->where('order_status',$status);
                }
                
                if($r->search){
                   $qq->where('invoice','LIKE','%'.$r->search.'%')->orWhere('email','LIKE','%'.$r->search.'%')->orWhere('mobile','LIKE','%'.$r->search.'%'); 
                }
                
                if($r->supplier){
                   $qq->where('user_id',$r->supplier); 
                }
                
                if($r->startDate || $r->endDate)
                {
                    if($r->startDate){
                        $from =$r->startDate;
                    }else{
                        $from=Carbon::now()->format('Y-m-d');
                    }

                    if($r->endDate){
                        $to =$r->endDate;
                    }else{
                        $to=Carbon::now()->format('Y-m-d');
                    }

                    $qq->whereBetween('created_at', [$from, $to]);
                }

                
            })
            ->paginate(25)->appends(['search'=>$r->search,'startDate'=>$r->startDate,'endDate'=>$r->endDate]);


        $suppliers = User::latest()->whereIn('status',[0,1])->where('business',true)->select(['id','name','mobile'])->get();
      
      return view('admin.purchase.purchaseList',compact('orders','suppliers','r'));

    }
    

    public function purchaseProductsEdit(Request $r,$id){

        $order =Order::latest()->where('order_type','purchase_order')->find($id);

        if(!$order){
            Session()->flash('error','Order Are Not Found');
            return redirect()->route('admin.purchaseProducts');
        }

        $searchProducts =null;
        $posMessage =null;

        $suppliers = User::latest()->whereIn('status',[0,1])->where('business',true)->select(['id','name','mobile'])->get();
        $methods =Attribute::where('type',11)->where('status','active')->where('parent_id',null)->orderBy('view','asc')->get();

        if($r->ajax()){

            if($r->type=='search' || $r->type=='barcode'){
               
                
                $searchProducts =Post::latest()->where('type',2)->where('status','active')
                ->where(function($q) use($r){
                    
                    if($r->type=='search' && $r->key){
                        $q->where('name','like','%'.$r->key.'%');
                        $q->orWhere('sku_code','like','%'.$r->key.'%');
                    }

                    if($r->type=='barcode' && $r->key){
                        $q->where('bar_code','like',$r->key);
                    }

                })
                ->select(['id','name','final_price','quantity','sku_code'])
                ->paginate(25)->appends([
                  'key'=>$r->key,
                  'barcode'=>$r->barcode,
                ]);
                
                
                
                if($searchProducts->count()==1){
                    
                    if($product =$searchProducts->first()){
    
                        $item =OrderItem::where('order_id',$order->id)->where('product_id',$product->id)->first();
                                
                        if(!$item){
                            $item =New OrderItem();
                            $item->order_id =$order->id;
                            $item->invoice =$order->invoice;
                            $item->user_id =$order->user_id;
                            $item->product_id =$product->id;
                            $item->product_name =$product->name;
                            $item->save();
                        }
                    
                    }
                
                }

                $datasItems =view('admin.purchase.includes.purchaseItems',compact('order','posMessage','methods'))->render();
                $datas =view('admin.purchase.includes.searchResult',compact('searchProducts'))->render();
                
                return Response()->json([
                    'success' => true,
                    'view' => $datas,
                    'count' => $searchProducts->count(),
                    'datasItems' => $datasItems,
                ]);


            }


            if($r->type=='addproduct' && $r->id){

               $product =Post::latest()->where('type',2)->where('status','active')->find($r->id);

               if($product){
                    $item =OrderItem::where('order_id',$order->id)->where('product_id',$product->id)->first();
                            
                    if(!$item){
                        $item =new OrderItem();
                        $item->order_id =$order->id;
                        $item->invoice =$order->invoice;
                        $item->user_id =$order->user_id;
                        $item->product_id =$product->id;
                        $item->product_name =$product->name;
                        $item->price =$item->price?:$product->purchase_price;
                        $item->save();
                    }
                    
               }

            }

            if($r->type=='itemRemove' && $r->id){

                $item = OrderItem::find($r->id);
               if($item){
                $item->delete();
               }

            }

            if($r->type=='itemQty' && $r->id){

              $item = OrderItem::find($r->id);
               if($item){
 
                   //Item Approved and Product Qty
                 
                 if($item->product && $order->order_status=='confirmed'){
                     
                     $product = $item->product;
                     $qty =0;
                     
                     if($item->quantity > $r->key){
                         
                         $qty =$item->quantity -$r->key;
                         
                         if($product->quantity >= $qty){
                            $product->quantity-=$qty;
                            $product->save();
                            $item->quantity=$r->key;
                         }
                     }elseif($item->quantity < $r->key){
                         
                        $qty = $r->key - $item->quantity;

                        $product->quantity += $qty;
                        $product->save();
                        
                        $item->quantity=$r->key;
                        
                     }
                     
                 }else{
                 $item->quantity=$r->key;  
                 }
                
                $item->total_price=$item->quantity*$item->price;
                $item->final_price=$item->total_price;
                $item->save();
               }
            }

            if($r->type=='itemPrice' && $r->id){

              $item = OrderItem::find($r->id);
               if($item){
                $item->price=$r->key;
                $item->total_price=$item->quantity*$item->price;
                $item->final_price=$item->total_price;
                $item->save();
               }
            }

            if($r->type=='addPayment' && $r->method && $r->option ){
                    
               $transection =new Transaction();
               $transection->src_id =$order->id;
               $transection->user_id =$order->user_id;
               $transection->type =8;
               $transection->status ='success';
               $transection->method_id=$r->method;
               $transection->method_option_id=$r->option;
               $transection->amount =$r->amount?:0;
               //$transection->branch_id =$order->branch_id;
               $transection->save();
                
            }
            
            if($r->type=='deletePayment' && $r->id){
               $transection = Transaction::find($r->id);
               if($transection){
                    $transection->delete();
               }
                
            }

            
            $order->total_price= $order->items()->sum('final_price');
            $order->total_items= $order->items()->count();
            $order->total_qty= $order->items()->sum('quantity');

            $order->paid_amount=$order->transections()->sum('amount');

            $order->grand_total= $order->total_price+$order->shipping_charge-$order->discount_price;


            if($order->grand_total >= $order->paid_amount){
                $order->due_amount=$order->grand_total-$order->paid_amount;
                $order->extra_amount=0;
            }else{
                $order->due_amount=0; 
                $order->extra_amount=$order->paid_amount - $order->grand_total; 
            }
            
            if($order->due_amount==0){
            $order->payment_status='paid';
            }elseif($order->due_amount==$order->grand_total){
            $order->payment_status='unpaid';
            }else{
            $order->payment_status='partial';
            }
            
            $order->save();

            $datas =view('admin.purchase.includes.purchaseItems',compact('order','posMessage','methods'))->render();
            
            return Response()->json([
                'success' => true,
                'view' => $datas,
            ]);




        }


        return view('admin.purchase.purchaseEdit',compact('order','suppliers','searchProducts','methods'));

    }

    public function purchaseProductsUpdate(Request $r,$id){

        $order =Order::latest()->where('order_type','purchase_order')->find($id);

        if(!$order){
            Session()->flash('error','Order Are Not Found');
            return redirect()->route('admin.purchaseProducts');
        }

        $check = $r->validate([
            'supplier' => 'required|numeric',
            'invoice' => 'required|max:100',
            'date' => 'required',
            'status' => 'required',
        ]);

        $nowTime =carbon::now()->format('H:i:s');
        
        if($order->items->count() ==0){
            Session()->flash('error','Please Added Product Items..');
            return redirect()->back();
        }
        $status =false;
        if($order->order_status=='temp' && $r->status=='confirmed'){
           $status =true;
           foreach($order->items as $item){
               if($product = $item->product){
                  $product->quantity+=$item->quantity?:0;
                  $product->save();
                  $item->order_status=$r->status;
                  $item->save();
               }
           }
           
        }
        

        $order->user_id =$r->supplier;
        $order->invoice =$r->invoice;
        $order->order_status =$r->status;
        $order->created_at =$r->date?$r->date.' '.$nowTime:Carbon::now();
        $order->note =$r->note;
        $order->save();

        
        
        if($status){
            Session()->flash('success','Order Are Successfully Completed!');
            return redirect()->route('admin.purchaseProductsInvoice',$order->id);
        }else{
            Session()->flash('success','Order Are Successfully Update!');
            return redirect()->back();
        }
        
        

    }

    public function purchaseProductsDelete($id){
        $order =Order::latest()->where('order_type','purchase_order')->find($id);

        if(!$order){
            Session()->flash('error','Order Are Not Found');
            return redirect()->route('admin.purchaseProducts');
        }
        
        return $order;
    }
    
    public function purchaseProductsInvoice($id){

        $order =Order::latest()->where('order_type','purchase_order')->find($id);

        if(!$order){
            Session()->flash('error','Order Are Not Found');
            return redirect()->route('admin.purchaseProducts');
        }

        return view('admin.purchase.invoice',compact('order'));
    }
    
    
    
    
    
    public function coinUsers(Request $r){
        
        $users =User::where('status',1)->where('balance','>',0)->paginate(10);
        
        //Total Count Results
          $totalCoin = User::where('status',1)->sum('balance');
          $totalUsed = Order::sum('used_coin');
          $totalCoinPrice = $totalCoin*general()->coin_convert_rate/100;
          $totalUsedPrice = Order::sum('coin_discount');

        return view('admin.coins.coinUsers',compact('users','totalCoin','totalUsed','totalUsedPrice','totalCoinPrice'));
    }
    
    public function coinSetting(Request $r){
        
        if($r->isMethod('post')){
            
            
            $check = $r->validate([
              'coin_bonus' => 'required|numeric',
              'coin_convert_rate' => 'required|numeric',
              'coin_minimum_order' => 'required|numeric',
              'coin_minimum_blance' => 'required|numeric',
              'coin_status' => 'required',
            ]);
            
            $general =general();
            $general->coin_bonus =$r->coin_bonus?:0;
            $general->coin_convert_rate =$r->coin_convert_rate?:0;
            $general->coin_minimum_order =$r->coin_minimum_order?:0;
            $general->coin_minimum_blance =$r->coin_minimum_blance?:0;
            $general->coin_status =$r->coin_status?1:0;
            $general->save();
            
            Session()->flash('success','Setting Are Successfully Updated!');
            return redirect()->back();
        }
        
        return view('admin.coins.setting');
    }
























}