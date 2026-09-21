<a class="destopmodehide mobile-menus-models viewCartmobile" href="javascript:void(0)">
    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
    @isset ($carts)
    @if($carts->sum('quantity') > 0)
    <span class="counter">{{$carts->sum('quantity')}}</span>
    @endif
    @endisset
</a>

<!--Mobile Menu Side Models Start-->
<div class="mobile-menu-side-modals2 side-modals side-modalsBar2 left">
    <a href="javascript:void(0)" class="overlay side-modals-close2"></a>
    <div class="cart-inner">

        
            
<div class="cart_top">
    <div class="row" style="margin: 0;">
        <div class="col-10" style="padding: 0;">
            <h3 style="margin: 0; font-size: 20px;font-family: sans-serif; font-weight: 600;">My Cart</h3>
        </div>
        <div class="col-2" style="padding: 0; text-align: center;">
            <a href="javascript:void(0)" class="side-modals-close side-modals-close2" style="color: gray; margin-left: 13px;">
                <i class="fa fa-times" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</div>
<div class="cardBodybox2">
    @isset($carts) 
    
    @if($carts->count() > 0)
    
    <div class="productBoxlist">
        <ul class="cartBoxpart">

            @foreach($carts as $cart)
            @if($cart->product)
            <li>
                
                    <div class="row" style="margin:0;">
                        <div class="col-3" style="padding:5px;">
                          <a href="{{route('productView',$cart->product->slug?:Str::slug($cart->product->name))}}">  <img src="{{asset($cart->product->image())}}"></a>
                        </div>
                        <div class="col-7" style="padding:5px;">
                            <a href="{{route('productView',$cart->product->slug?:Str::slug($cart->product->name))}}">{{ $cart->product->name }}</a>
                        </div>
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

    </div>
</div>