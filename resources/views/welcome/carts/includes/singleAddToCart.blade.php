@if($hCart =hasCart(request()->cookie('carts'),$product->id))
    <div class="quantity">
        <input type="button" value="-" class="qtyminus cartUpdate" data-url="{{ route('changeToCart', [$hCart, 'decrement']) }}" data-min="{{$product->productMinQty()}}" data-id="{{$product->id}}" field="quantity" style="height: 33px;" />
        <div   name="quantity" class="qty qty_{{$product->id}}" style="height: 33px;"><span>{{$hCart->quantity}}</span> in cart</div>
        <input type="button" value="+" class="qtyplus cartUpdate" data-url="{{ route('changeToCart', [$hCart, 'increment']) }}" data-max="{{$product->productMaxQty()}}" data-id="{{$product->id}}" field="quantity" style="height: 33px;" />
    </div>
@else
    <a href="javascript:void(0)" class="addcartbutton product-btn addCartShow_{{$product->id}} singleaddCart" data-id="{{$product->id}}" data-url="{{route('addToCart',$product->id)}}">

        <span class="btn-text">অর্ডার করুন</span>
    </a>
@endif