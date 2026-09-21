<a  href="{{route('carts')}}" style="">
    <div class="headCart">
        <div class="headCartInfo">
            <span style="">কার্ট দেখুন</span>
            <i class="fa ml-1 fa-shopping-cart" style="color: white;"></i>
            <span class="cartCounts">@isset ($carts) {{$carts->count()}} @endisset</span>
        </div>
    </div>
<!--<span class="cartCount">@isset ($carts) {{$carts->count()}} @endisset</span>-->
</a>

<div class="cardBodybox">
    @isset($carts) 
    
    @if($carts->count() > 0)
    
    <div class="productBoxlist">
        <ul class="cartBoxpart">

            @foreach($carts as $cart)
            @if($cart->product)
            <li>
                
                    <div class="row" style="margin:0;">
                        @if($cart->product)
                        <div class="col-3" style="padding:5px;">
                          <a href="{{route('productView',$cart->product->slug?:Str::slug($cart->product->name))}}">  <img src="{{asset($cart->product->image())}}"></a>
                        </div>
                        <div class="col-7" style="padding:5px;">
                            <a href="{{route('productView',$cart->product->slug?:Str::slug($cart->product->name))}}">{{ $cart->product->name }}</a>
                        </div>
                        @else
                        <div class="col-10" style="padding:5px;"><span>Not Found</span></div>
                        @endif
                        <div class="col-2" style="padding:5px;">
                            <i class="fa fa-times cartUpdate" style="cursor: pointer;" data-url="{{ route('changeToCart', [$cart, 'delete']) }}"></i>
                        </div>
                    </div>
            </li>
            @endif
            @endforeach
        </ul>
    </div>
    
    <div class="subtotalBox">
        <span>Subtotlal: @isset ($cartTotalPrice) {{priceFullFormat($cartTotalPrice)}} @endisset</span>
    </div>
    
    <div class="cartBoxbtn">
        <a class="viewcartBox" href="{{route('carts')}}">View Cart</a>
        <a class="checkoutBox" href="{{route('checkout')}}">Checkout</a>
    </div>
    
    @else
    
    <h4>Empty Cart</h4>
    
    @endif 
    @endisset

</div>