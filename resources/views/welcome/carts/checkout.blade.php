@extends(general()->theme.'.layouts.app') @section('title')
<title>{{general()->title}} | {{general()->subtitle}}</title>
@endsection @section('SEO')
<meta name="description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta property="og:title" content="{{general()->meta_title}}" />
<meta property="og:description" content="{!!general()->meta_description!!}" />
<meta property="og:image" content="{!!general()->meta_description!!}" />
<meta property="og:url" content="{{route('index')}}" />
@endsection 
@push('css')
<style>

 .cashonDelivery {
    margin: 15px 0px;
}
.overViewTable tr th {
    background: #eeeeee;
}
.overViewTable tr th, .overViewTable tr td{
    padding: 5px 8px;
}
.ordertable td, .ordertable th{
    padding: 5px 12px;
    font-size: 16px;
    font-weight: 400;
    border-top: 0;
}
</style>
@endpush 

@section('contents')


    
    <script>
        window.dataLayer = window.dataLayer || [];
        dataLayer.push({
            event: "begin_checkout",
            ecommerce: {
                currency: "{{ general()->currency }}",
                value: {{round($cartTotalPrice)}},
                shipping: {{ round($shippingCharge ?? 0)}},
                discount: {{ round($couponDisc ?? 0) }},
                items: [
                    @foreach($carts as $item)
                    {
                        @if($product =$item->product)
                        item_id: "{{ $item->id }}",
                        item_name: "{{ $product->name }}",
                        item_category: "{!! implode(' - ', $product->productCategories->pluck('name')->toArray()) !!}",
                        item_brand: "{{ $product->brand ? $product->brand->name : '' }}",
                        price: "{{ $product->offerPrice() }}",
                        quantity: "{{ $item->quantity }}",
                    
                        @endif
                    }@if(!$loop->last),@endif
                    @endforeach
                ]
            }
        });
        console.log('check out event');
    </script>


<div class="checkoutPage">
    <div class="container">
        <div>
            @foreach ($errors->all() as $error)
                <p style="color: red; margin: 0;">{{ $error }}</p>
            @endforeach
        </div>
        <div>
            @if($errors->has('username'))
                <span style="color:red;display: block;">{{ $errors->first('username') }}</span>
            @endif
            @if($errors->has('password'))
                <span style="color:red;display: block;">{{ $errors->first('password') }}</span>
            @endif
            @if (session('error'))
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <strong>Oops! </strong> {{Session::get('error') }}.
            </div>
            @endif
            @if (session('loginfail'))
            <span style="color:red;display: block;">{{ session('loginfail') }}</span>
            @endif
            @if (session('loginfailP'))
            <span style="color:red;display: block;">{{ session('loginfailP') }}</span>
            @endif
        </div>
        <form action="{{route('checkoutPost')}}" method="post">
            @csrf
        <div class="row">
            <div class="col-md-7">
                <div class="checkoutForm">
                    <h5 style="text-align: center;color: #ff005e;">
                        নিচের তথ্যগুলো সঠিকভাবে পূরণ করে
                        <b>
                        কনফার্ম অর্ডার
                        </b>
                        বাটনে ক্লিক করুন।
                    </h5>
                    <hr>
                    <div class="row mb-3">
                        <label class="col-md-3" for="name" style="font-weight: bold;">
                            আপনার নাম: 
                            <span class="text-danger" style="font-size: 16px;">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="text" value="{{Auth::user()?$user->name:old('name')}}" name="name" class="form-control" placeholder="Enter Name" required="">
                            @if ($errors->has('name'))
                            <p style="color: red; margin: 0;">{{ $errors->first('name') }}</p>
                            @endif
                            @if(Session::has('nameError'))
                            <p style="color: red; margin: 0;">{{Session::get('nameError') }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-md-3" for="mobile" style="font-weight: bold;" >
                            মোবাইল নম্বর:
                            <span class="text-danger" style="font-size: 16px;">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="mobile" class="form-control" value="{{Auth::user()?$user->mobile:old('mobile')}}" placeholder="Enter Phone Number" required="">
                            @if ($errors->has('mobile'))
                            <p style="color: red; margin: 0;">{{ $errors->first('mobile') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-3" for="delivery_area" style="font-weight: bold;" >
                            ডেলিভারি এরিয়া:
                        </label>
                        <div class="col-md-9">
                            <div class="form-control  h-auto">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" wire:model="shipping" class="custom-control-input shippingCheck" id="inside-dhaka" name="delivery_area" value="Inside Dhaka" data-shipping="{{priceFullFormat(general()->inside_dhaka_shipping_charge)}}" data-total="{{priceFullFormat($grandTotal+general()->inside_dhaka_shipping_charge)}}"  >
                                    <label class="custom-control-label" for="inside-dhaka">ঢাকা শহর ({{en2bnNumber(number_format(general()->inside_dhaka_shipping_charge))}} টাকা)</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" wire:model="shipping" class="custom-control-input shippingCheck" id="outside-dhaka" name="delivery_area" value="Outside Dhaka"  data-shipping="{{priceFullFormat(general()->outside_dhaka_shipping_charge)}}" data-total="{{priceFullFormat($grandTotal+general()->outside_dhaka_shipping_charge)}}">
                                    <label class="custom-control-label" for="outside-dhaka">ঢাকার বাইরে ({{en2bnNumber(number_format(general()->outside_dhaka_shipping_charge))}} টাকা)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-md-3" for="address" style="font-weight: bold;" >
                            ডেলিভারি এরিয়া:
                            <span class="text-danger" style="font-size: 16px;">*</span>
                        </label>
                        <div class="col-md-9">
                            <textarea type="text" row="" name="address" col="" placeholder="Type your address here." required="" >{{Auth::user()?$user->address_line1:old('address')}}</textarea>
                            @if ($errors->has('address'))
                            <p style="color: red; margin: 0;">{{ $errors->first('address') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-3" style="font-weight: bold;" >
                            নোট (অপশনাল):
                        </label>
                        <div class="col-md-9">
                            <textarea type="text" row="" name="note" col="" placeholder="আপনি চাইলে কোন নোট লিখতে পারেন।"></textarea>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div>
                        <h4>Product Overview</h4>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered overViewTable">
                                <tr>
                                    <th style="width: 60px;min-width: 60px;">Image</th>
                                    <th style="min-width: 200px;">Product</th>
                                    <th style="width: 100px;min-width: 100px;">Price</th>
                                    <th style="width: 60px;min-width: 60px;">Quantity</th>
                                    <th style="width: 110px;min-width: 110px;">Total</th>
                                </tr>
                                @foreach($carts as $cart)
                                <tr>
                                    <td>
                                        @if($cart->product)
                                        <img src="{{asset($cart->product->image())}}" style="width: 40px;margin-right: 5px;">
                                        @endif
                                    </td>
                                    <td>
                                        @if($cart->product)
                                        <a href="{{route('productView',$cart->product->slug?:Str::slug($cart->product->name))}}" style="color: #000000;font-weight: bold;">
                            		      {{$cart->product->name}}
                            		    </a>
                                        @if($cart->color || $cart->size)
                                        <br>
                                        <small style="color:#777;">
                                        @if($cart->color)Color: {{$cart->color}}@endif
                                        @if($cart->color && $cart->size) &nbsp; @endif
                                        @if($cart->size)Size: {{$cart->size}}@endif
                                        </small>
                                        @endif
                                        @else
                                        Not Found
                                        @endif
                                    </td>
                                    <td>{{$cart->product?$cart->itemprice():0}}</td>
                                    <td>{{$cart->quantity}}</td>
                                    <td>
                                        @if($cart->product)
                        		          {{priceFullFormat($cart->quantity * $cart->itemprice())}}
                        		        @else
                        		           {{priceFullFormat($cart->quantity*0)}}
                        		        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-5">
                <div class="checkoutInfo">
                    <h4>YOUR ORDER</h4>
                    <table class="ordertable table">
                      <thead>
                        <tr>
                          <th style="width: 65%;border-bottom: 0;">PRODUCT</th>
                          <th style="width: 35%; text-align: end;border-bottom: 0;">SUBTOTAL</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th>Subtotal</th>
                          <td style="text-align: end;">{{priceFullFormat($cartTotalPrice)}}</td>
                        </tr>
                        <tr>
                          <th>Shipping</th>
                          <td style="text-align: end;" class="shippingAmount">
                              @if($shippingCharge>0)
        			      {{priceFullFormat($shippingCharge)}}
        			      @else
        			      Free Shipping
        			      @endif
        			     </td>
                        </tr>
                        @if($cartTax > 0)
                        <tr>
                          <th>Tax</th>
                          <td style="text-align: end;">{{priceFullFormat($cartTax)}}</td>
                        </tr>
                        @endif
                        <tr>
                          <th style="font-size: 18px">Total</th>
                          <td style="text-align: end; font-size: 18px" class="grandTotalAmount" data-shipping="{{priceFullFormat(100)}}" data-total="{{priceFullFormat($grandTotal+100)}}">{{priceFullFormat($grandTotal)}}</td>
                        </tr>

                      </tbody>
                    </table>
                   <!--<label>-->
                   <!--     <input class="mr-2" type="checkbox" name="agree" required=""> I have read and agree to the website terms and conditions *-->
                   <!-- </label>-->
                    
                    <button type="submit" class="placeOrderbtn" >Place Order</button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>


@endsection 

@push('js') 

<script>
    $(document).ready(function(){
        $('.shippingCheck').change(function () {
            shippingCharg();
        });
        
        function shippingCharg(){
            var shipping = $('.shippingCheck:checked').data('shipping') || $('.grandTotalAmount').data('shipping');
            
            var total = $('.shippingCheck:checked').data('total') || $('.grandTotalAmount').data('total');
            
            $('.shippingAmount').empty().append(shipping);
            $('.grandTotalAmount').empty().append(total);
            
        }
        
        shippingCharg();
        
    });
</script>

@endpush