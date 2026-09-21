<?php

namespace App\Http\Controllers\Customer;

use Mail;
use Auth;
use Hash;
use PDF;
use Session;
use Response;
use Cookie;
use Str;
use File;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Mail\orderInvoiceMail;
use App\Mail\RegistrationMail;
use App\Mail\vendorRegistrationMail;
use App\Mail\deliveryRegistrationMail;
use App\Mail\VerifyCodeMail;
use App\Models\Country;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Media;
use App\Models\General;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturnItem;
use App\Models\Review;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
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
          $this->device =General::first()->theme.'.customer.';
        }
        else
        {
          $this->device =General::first()->theme.'.customer.';
        }

    }

    public function dashboard(Request $request){
        
        
        // if(Auth::id()==1){
        //     return User::where('status',true)->select(['name','email','mobile','address_line1','city_name'])->get();
        // }
        
        return view($this->device.'dashboard');

    }

    public function profileEdit(){
        
      return view($this->device.'profileEdit');
    }


    public function profileUpdate(Request $r){
      
        $myprofile =Auth::user();
        
        $check = $r->validate([
            'name' => 'required|max:50|unique:users,name,'.$myprofile->id,
            'email' => 'required|max:100|unique:users,email,'.$myprofile->id,
            'mobile' => 'nullable|max:20|unique:users,mobile,'.$myprofile->id,
            'company' => 'nullable|max:191',
            'city' => 'nullable|max:191',
            'postal_code' => 'nullable|max:20',
            'address' => 'nullable|max:191',
            'prefecture' => 'nullable|numeric',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);


        $myprofile->name =$r->name;
        $myprofile->mobile =$r->mobile;
        $myprofile->email =$r->email;
        $myprofile->company_name =$r->company;
        $myprofile->district =$r->prefecture;
        $myprofile->city_name =$r->city;
        $myprofile->address_line1 =$r->address;
        $myprofile->postal_code =$r->postal_code;
        
        ///////Image Uploard Start////////////
        if($r->hasFile('image')){
         $file=$r->image;
         $media =Media::latest()->where('src_type',6)->where('use_Of_file',1)->where('src_id',$myprofile->id)->first();
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
          $media->src_id=$myprofile->id;
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
      $myprofile->save();


      Session::flash('success','Your Are Successfully Done');
      return redirect()->back();

    }

    public function profileVerifyUpdate(Request $r,$type){

      if($type=='mobile' || $type=='email'){
        return view($this->device.'profileVerifyUpdate',compact('type'));

      }
      

    }
    
    public function profileUpdateVerifyCode(Request $r,$data){

        if($r->ajax())
        {
            $data =$data;

            if(is_numeric($data)){
                $user =User::where('mobile',$data)->first();
                Session::put('mobile', $data);
            }elseif (filter_var($data, FILTER_VALIDATE_EMAIL)) {
                $user =User::where('email',$data)->first();
                Session::put('email', $data);
            }
            
            $general = General::first();
            if($user){

                $verifycode = mt_rand(100000,999999);
                 Session::put('verifycode', $verifycode);
                $verifycode=Session::get('verifycode');

                $status=true;
                if(is_numeric($data)){
                //********** Send SMS ***************//

                if($general->sms_status && $user->mobile){
                    
                    $m =$user->mobile;
                    
                    $to =bdMobile($m);
                    
                    if(strlen($to) != 13)
                    {
                        //return true;
                    }else{

                    $msg = urlencode("Your Verify OPT Code Is {$verifycode} form  {$general->title}"); //150 characters allowed here
        
                    $url = smsUrl($to,$msg);
                
                    $client = new Client();
                    
                    try {
                            $r = $client->request('GET', $url);
                        } catch (\GuzzleHttp\Exception\ConnectException $e) {
                        } catch (\GuzzleHttp\Exception\ClientException $e) {
                        }
                    
                    }
                }

                //********** Send SMS ***************//

                }elseif (filter_var($data, FILTER_VALIDATE_EMAIL)) {

                //********** Send Mail ***************//

                if($general->mail_status && $user->email){
    
                    Mail::to($user->email)->send(new VerifyCodeMail($verifycode));
                    
                }

                //********** Send Mail ***************//

                }

                return Response()->json([
                          'success' => $status,
                          'verifycode' => $verifycode,
                          'data' => $data,
                        ]);
             
            }else{

            $status=false;
            return Response()->json([
                      'success' => $status,
                    ]);
            
            }

        }
    }

    public function profileVerifyUpdatePost(Request $r){
       

        $check = $r->validate([
            'verytype' => 'required',
            'verifycode' => 'required|numeric|digits:6',
        ]);

        if($r->newmobile){
            $check = $r->validate([
            'newmobile' => 'required|numeric|unique:users,mobile',
            ]);
        }

        if($r->newemail){
            $check = $r->validate([
            'newemail' => 'required|string|unique:users,email',
            ]);
        }
       
        $verifycode=Session::get('verifycode');
        $mobile=Session::get('mobile');
        $email=Session::get('email');
        
        if($verifycode!=$r->verifycode){
           Session::flash('error','Your Verify Code Incorrect.');
           return back(); 
        }
        
        $user =User::where('mobile', $r->verytype)->orWhere('email',$r->verytype)->first();
        
        
        if($user){
            
            
            if($r->newmobile){
               $user->mobile=$r->newmobile;
            }
            
            if($r->newemail){
                $user->email=$r->newemail;
            }
            
            $user->save();
            
            Session::flash('success','You Are Successfully Updated.');
            return back();
           
        }
        
        Session::flash('error','Un-define User Cannot Update Data.');
        return back(); 


    }
    
    

    public function changePassword(){
      return view($this->device.'changePassword');
    }

    public function changePasswordUpdate(Request $r){
       $user = Auth::user();

        $check = $r->validate([
            'current_password' => 'required|string|min:8',
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        if(!$check){
            Session::flash('error','Need To validatation');
            return redirect()->back();
        }
        
        if(Hash::check($r->current_password, $user->password)){
          $user->password_show=$r->password;
          $user->password=Hash::make($r->password);
          $user->save();
          Session()->flash('success','Your Are Successfully Done');
          return redirect()->back();
        }else{
        Session()->flash('error','Carrent Password Are Not Match');
        return redirect()->back();
        }

    }

    public function myOrders(Request $r){

      $q=$r->type;
        
        if($r->type){
           $orders =Auth::user()->orders()->where('order_status',$q)->paginate(20)->appends (['type' => $q] );
        }else{
          $orders =Auth::user()->orders()->paginate(20)->appends (['type' => $q] );  
        }

      return view($this->device.'myOrders',compact('orders'));

    }

    public function orderDetails($id){

        $order =Order::find($id);

        if(!$order){
            Session::flash('error','This Order Invoice Are Not Found');
            return back();
        }
        
        // if(Auth::id()==1){
        //     $general =general();
        //     //**********Send Mail***************//
        //     if(Auth::check()){
                
        //         if($general->mail_status && $order->email){
        
        //             Mail::send('mails.InvoiceMail', ['order' => $order,'general'=>$general], function ($message) use ($order,$general) {
        
        //                 $message->from($general->mail_from_address,$general->mail_from_name);
        
        //                 $message->to($order->email,$order->name)
        //                 ->subject('Order Completed in '.$general->mail_from_name.".");
        //             });
                    
        //         }
        //     }
        //     //**********Send Mail***************//
        //     return 'send mail completed';
        // }

        if($order->invoice==null || $order->grand_total==0 ){
                
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

        
        
        // $general =general();
        
        // $user =Auth::user();
        
        // if($general->mail_status && $user->email){
    
        //         Mail::send('mails.InvoiceMail', ['order' => $order,'general'=>$general], function ($message) use ($user,$general) {
    
        //             $message->from($general->mail_from_address,$general->mail_from_name);
    
        //             $message->to($user->email,$user->name)
        //             ->subject('Registration Successfully Completed in '.$general->mail_from_name.".");
        //         });
                
        //     }
        
        return view($this->device.'invoice',compact('order'));
    }
    
    public function orderDetailsPDf($id){
        $order =Order::find($id);

        if(!$order){
            Session::flash('error','This Order Invoice Are Not Found');
            return back();
        }
        
        

        return $pdf->download('invoice.pdf');
        
    }
    
    
    public function orderCancel(Request $r,$id){
        
        $order =Order::find($id);
        
        if(!$order){
            Session::flash('error','This Order Invoice Are Not Found');
            return back();
        }

        if($order->order_status=='pending' && $order->payment_status=='unpaid'){
            return view($this->device.'orderCancel',compact('order'));

        }else{
            Session::flash('error','You Can Not Cancel Order.this order Monitoring by Author.');
            return redirect()->route('customer.orderDetails',$order->id);
        }



        
    }
    
    public function orderCancelPost(Request $r,$id){
        
        $order =Order::find($id);
        
        if(!$order){
            Session::flash('error','This Order Invoice Are Not Found');
            return back();
        }
        
        $check = $r->validate([
            'reason' => 'required|max:100',
            'message' => 'required|max:500',
        ]);

        if(!$check){
            Session::flash('error','Need To Validation');
            return redirect()->back();
        }

        if($order->order_status=='pending'){
            
        

    foreach($order->items as $item){
        $item->status='cancelled';
        $item->cancelled_at=Carbon::now();
        $item->cancelled_by=Auth::id();
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
          $cancelItem->reasion=$r->reason;
          $cancelItem->description=$r->message;
          $cancelItem->status='confirmed';
          $cancelItem->accepted=true;
          $cancelItem->search_key=$item->search_key;
          $cancelItem->confirmed_at=Carbon::now();
          $cancelItem->confirmed_by=Auth::id();
          $cancelItem->save();



        }
        
        //Cancel Order SMS Send Seller
    }

    $order->order_status='cancelled';
    $order->cancel_at=Carbon::now();
    $order->cancel_by=Auth::id();
    $order->cancel_reason=$r->reason;
    $order->cancel_msg=$r->message;
    $order->save();

    //Cancel Order SMS Send Admin

    //Cancel Order SMS Send User

    $user =Auth::user();
    
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

    Session::flash('success','Your Order Is Successfully  Cancelled.');
    return redirect()->route('customer.orderDetails',$order->id);
    
    }else{
        Session::flash('error','You Can Not Cancel Order.this order Monitoring by Author.');
        return redirect()->back();
    }
    
    
    }
    
    public function orderReturn(Request $r,$id){
        
        $item =OrderItem::find($id);
        
        if(!$item){
            Session::flash('error','This Order Item Are Not Found');
            return back();
        }

        $order =$item->order;
        if($order->order_status=='delivered' && $order->delivered_at > Carbon::now()->subDays(7)){
            return view($this->device.'orderReturn',compact('order','item'));
        }else{
            Session::flash('error','This Order Cannot Returned');
            return back();
        }
        
        
    }
    
    
    public function orderReturnPost(Request $r,$id){
        
        $item =OrderItem::find($id);
        
        if(!$item){
            Session::flash('error','This Order Item Are Not Found');
            return redirect()->route('customer.myOrders');
        }
        
        $check = $r->validate([
            'qty' => 'required|numeric',
            'returntype' => 'required|max:100',
            'message' => 'required|max:500',
        ]);
        
        if($r->qty > $item->quantity){
         $qty =$item->quantity;
        }else{
        $qty =  $r->qty;  
        }

        if(!$check){
            Session::flash('selectError','Please Select item and confirm Return Items');
            return redirect()->back();
        }
        
        $order =$item->order;
        
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
              $returnItem->reasion=$r->returntype;
              $returnItem->description=$r->message;
              $returnItem->status='pending';
              $returnItem->accepted=false;
              $returnItem->return_type=true;
              $returnItem->search_key=$item->search_key;
              $returnItem->pending_at=Carbon::now();
              $returnItem->pending_by=Auth::id();
              $returnItem->save();
              
              $item->total_return+=$qty;
              $item->save();
              
            Session::flash('success','Your return Is Successfully  Done.Wait for Approved.');
            return redirect()->back();

        }else{
            Session::flash('error','This Order Cannot Returned');
            return redirect()->route('customer.myOrders');
        }
        
        
        return $r;
        
    }
    

    public function returnCancellations(Request $r){

      $orderItems = OrderReturnItem::latest()->where('user_id',Auth::id())->paginate(20);

      return view($this->device.'returnCancellations',compact('orderItems'));
    }


    public function myReviews(){
        
        $reviews = Auth::user()->reviews()->has('post')->latest()->paginate(20);
        
       return view($this->device.'myReviews',compact('reviews'));
    }
    
    public function orderReview($id){
        
        $item = OrderItem::find($id);
        
        if(!$item){
            Session::flash('error','This Order Item Are Not Found');
            return back();
        }
        
        $review =Review::where('src_id',$item->id)->first();
        
        return view($this->device.'orderReview',compact('item'));
    }
    
    public function orderReviewPost(Request $r,$id){
        
        $item = OrderItem::find($id);
        
        if(!$item){
            Session::flash('error','This Order Item Are Not Found');
            return back();
        }
        
        $check = $r->validate([
            'star' => 'required|numeric|between:1,5',
            'review' => 'required|max:500',
        ]);

        if(!$check){
            Session::flash('error','Need To validatation');
            return redirect()->back();
        }
        
        $review =Review::where('addedby_id',Auth::id())->where('parent_id',$item->id)->first();
        
        if(!$review){
          $review =new Review();
          $review->src_id=$item->id;
          $review->addedby_id=Auth::id();
          $review->save();
        }
        $review->parent_id=$item->product?$item->product->id:null;
        $review->rating=$r->star;
        $review->content=$r->review;
        $review->save();

        Session()->flash('success','Your Are Successfully Review');
        return redirect()->back();
    }
    

    public function myBalance(){

       return view($this->device.'myBalance');
    }
    
    public function addBalanceToWallet(request $r){
        return 'online payment not active';
    }




    



}
