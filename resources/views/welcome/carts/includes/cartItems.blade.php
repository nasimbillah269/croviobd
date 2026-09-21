@isset($carts) @if($carts->count() > 0)
<!--<p class="customer"><i class="fa mr-2 fa-check" aria-hidden="true"></i>Customer matched zone "1 Day Delivery"</p>-->
<!--<hr style="border: 2px solid #ccc; margin: 5px 0 15px;">-->

<div class="row">
	<div class="col-lg-8 col-md-7" >
	    <div class="cartProductBox">

	    <div class="mobileCartItems">
	        <table class="table table-bordered">
	            <tr>
	                <th style="padding: 2px 5px;">Produuct Items</th>
	                <th style="width: 140px;text-align: center;padding: 2px 5px;">Total</th>
	            </tr>
	            @foreach($carts as $cart)
    		    @if($cart->product)
	            <tr>
	                <td style="padding: 2px;">
	                     @if($cart->product)
	                    <div class="items" style="display:flex;">
	                        <div>
	                            <a style="color: #444; font-size:18px;margin-right: 3px;" href="javascript:void(0)" class="cartUpdate" data-url="{{ route('changeToCart', [$cart, 'delete']) }}">
            		            <i class="fa fa-times-circle-o" ></i>
            		            </a>
                		        <a href="{{route('productView',$cart->product->slug?:Str::slug($cart->product->name))}}">
                		          <img src="{{asset($cart->product->image())}}" style="width: 40px;margin-right: 5px;">
                		        </a>
            		        </div>
	                        <div>
                		      <a href="{{route('productView',$cart->product->slug?:Str::slug($cart->product->name))}}" style="color: #000000;font-weight: bold;">
                		          {{ Str::limit($cart->product->name,35) }}
                		      </a>
                		      <br>
                		      @if($cart->product->weight_unit && $cart->product->weight_amount)
                		        {{$cart->product->weight_amount}} {{$cart->product->weight_unit}}
                		      @endif
                		      @if($cart->color || $cart->size)
                		      <br>
                		      <small style="color:#777;">
                		      @if($cart->color)Color: {{$cart->color}}@endif
                		      @if($cart->color && $cart->size) &nbsp; @endif
                		      @if($cart->size)Size: {{$cart->size}}@endif
                		      </small>
                		      @endif
                		      <br>
                		      X {{priceFullFormat($cart->itemprice())}}
            		        </div>
	                    </div>
            		    @else
            		    <span>Not Found</span>
            		    @endif 
	                </td>
	                <td style="padding: 2px;vertical-align: middle;text-align: center;">
	                   <form  class="quantity">
                        <input type="button" value="-" class="qtyminus cartUpdate" data-url="{{ route('changeToCart', [$cart, 'decrement']) }}" field="quantity" />
                        <input type="number"  name="quantity" value="{{ $cart->quantity }}" class="qty cartQtyChange" data-url="{{ route('changeToCart', [$cart, 'quantity']) }}" />
                        <input type="button" value="+" class="qtyplus cartUpdate" data-url="{{ route('changeToCart', [$cart, 'increment']) }}" field="quantity" />
                        </form>
                        @if($cart->product)
        		          {{priceFullFormat($cart->quantity * $cart->itemprice())}}
        		          @else
        		           {{priceFullFormat($cart->quantity*0)}}
    		            @endif
	                </td>
	            </tr>
	            @endif
    		   @endforeach
	        </table>
	    </div>

	    
	    <div class="table-responsive lx-hidden">
    		<table class="table carttable">
    		  <thead>
    		    <tr>
    		      <th scope="col" colspan="2" style="width: 250px;min-width:250px;padding:5px;">Product</th>
    		      <th scope="col" style="width: 120px; text-align: center;min-width:120px;padding:5px;">Price</th>
    		      <th scope="col" style="width: 140px; text-align: center;min-width:140px;padding:5px;">Quantity</th>
    		      <th scope="col" style="width: 120px; text-align: end;min-width:120px;padding:5px;">Subtotal</th>
    		    </tr>
    		  </thead>
    		  <tbody>
    		  
    		  @foreach($carts as $cart)
    		  @if($cart->product)
    		    <tr>
    		        <td style="padding:3px;width: 75px;min-width: 75px;">
    		            <a style="color: #444; font-size:18px;margin-right: 3px;" href="javascript:void(0)" class="cartUpdate" data-url="{{ route('changeToCart', [$cart, 'delete']) }}">
        		          <i class="fa fa-times-circle-o" ></i>
        		          @if($cart->product)
            		      <a href="{{route('productView',$cart->product->slug?:Str::slug($cart->product->name))}}">
            		          <img src="{{asset($cart->product->image())}}" style="width: 45px;">
            		      </a>
            		      @else
            		      <span>Not Found</span>
            		      @endif
        		      </a>
    		        </td>
    		      <td style="padding:3px;">
    		      
    		      @if($cart->product)
    		      <a href="{{route('productView',$cart->product->slug?:Str::slug($cart->product->name))}}">
    		          {{ Str::limit($cart->product->name,35) }}
    		      </a>
    		      <br>
    		      @if($cart->product->weight_unit && $cart->product->weight_amount)
    		        {{$cart->product->weight_amount}} {{$cart->product->weight_unit}}
    		      @endif
    		      @if($cart->color || $cart->size)
    		      <br>
    		      <small style="color:#777;">
    		      @if($cart->color)Color: {{$cart->color}}@endif
    		      @if($cart->color && $cart->size) &nbsp; @endif
    		      @if($cart->size)Size: {{$cart->size}}@endif
    		      </small>
    		      @endif

    		      @else
    		      <span>Not Found</span>
    		      @endif
    		      </td>
    		      <td style="text-align: center;padding:3px;">{{priceFullFormat($cart->product?$cart->itemprice():0)}}</td>
    		      <td style="text-align: center;padding:3px;">
    		        <form  class="quantity">
                        <input type="button" value="-" class="qtyminus cartUpdate" data-url="{{ route('changeToCart', [$cart, 'decrement']) }}" field="quantity" />
                        <input type="number"  name="quantity" value="{{ $cart->quantity }}" class="qty cartQtyChange" data-url="{{ route('changeToCart', [$cart, 'quantity']) }}" />
                        <input type="button" value="+" class="qtyplus cartUpdate" data-url="{{ route('changeToCart', [$cart, 'increment']) }}" field="quantity" />
                    </form>
    		      </td>
    		      <td style="text-align: end;padding:3px;">
    		          @if($cart->product)
    		          {{priceFullFormat($cart->quantity * $cart->itemprice())}}
    		          @else
    		           {{priceFullFormat($cart->quantity*0)}}
    		          @endif
    		      </td>
    		    </tr>
    		   @endif
    		   @endforeach
    		    
    		  </tbody>
    		</table>
		</div>

		{{--<hr style="border: 1px solid #ccc;">
		<form action="{{route('couponApply')}}" method="post">
		    @csrf
    		<label style="font-weight: bold;color: #F44336;">Have any coupon code apply to enjoy your discount offer!</label>

    		<div class="input-group">
    		    <input type="text" name="coupon_code" class="form-control" value="{{old('coupon_code')}}" placeholder="Enter Coupon Code" required="" />
    		    <button type="submit" class="btn btn-success rounded-0" >Apply Counpon</button>
    		</div>
    		@if ($errors->has('coupon_code'))
            <p style="color: red; margin: 0;">{{ $errors->first('coupon_code') }}</p>
            @endif
		</form>--}}

	    </div>
	</div>

	<div class="col-lg-4 col-md-5">
	    <div class="checkoutInfo cartSummaryBox">
	        <h4>CART TOTALS</h4>
	        <table class="ordertable table">
	          <tbody>
	            <tr>
	              <th style="width: 50%;">Subtotal</th>
	              <td style="width: 50%; text-align: end;" class="cartSummaryTotal">{{priceFullFormat($cartTotalPrice)}}</td>
	            </tr>
	          </tbody>
	        </table>

	        <a class="checkoutbtn" href="{{route('checkout')}}">PROCEED TO CHECKOUT</a>
	    </div>
	</div>

</div>

@else
<div class="emptycarts">
    <center>
        <i class="fa fa-shopping-bag"></i>
        <p>Empty is Cart</p>
        <a href="{{ url('/') }}" class="btn continueshopping">
            @if(session()->get('locale')=='bn') আরো শপিং করুন @else CONTINUE SHOPPING @endif
        </a>
    </center>
</div>
@endif @endisset
