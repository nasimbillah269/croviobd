@php
    $mcLines = isset($carts) ? $carts->filter(fn($cart) => $cart->product) : collect();
    $mcQty = $mcLines->sum('quantity');
@endphp
<a class="destopmodehide mobile-menus-models viewCartmobile" href="javascript:void(0)" aria-label="Open cart">
    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
    @if($mcQty > 0)
    <span class="counter">{{ $mcQty }}</span>
    @endif
</a>

<!--Mobile Cart Side Models Start-->
<div class="mobile-menu-side-modals2 side-modals side-modalsBar2 left mc-drawer">
    <a href="javascript:void(0)" class="overlay side-modals-close2" aria-label="Close cart"></a>
    <div class="cart-inner">
        <div class="mc-head">
            <div>
                <h3>My Cart</h3>
                <span>{{ $mcQty }} {{ Str::plural('item', $mcQty) }}</span>
            </div>
            <a href="javascript:void(0)" class="side-modals-close2 mc-close" aria-label="Close cart"><i class="fa-solid fa-xmark"></i></a>
        </div>

        @if($mcLines->count() > 0)
        <div class="mc-body cardBodybox2">
            @foreach($mcLines as $cart)
            @php $mcUrl = route('productView', $cart->product->slug ?: Str::slug($cart->product->name)); @endphp
            <div class="mc-item">
                <a href="{{ $mcUrl }}" class="mc-thumb"><img src="{{ asset($cart->product->image()) }}" alt="{{ $cart->product->name }}"></a>
                <div class="mc-info">
                    <a href="{{ $mcUrl }}" class="mc-name">{{ $cart->product->name }}</a>
                    @if($cart->color || $cart->size)
                    <div class="mc-variant">
                        @if($cart->color)<span>{{ $cart->color }}</span>@endif
                        @if($cart->size)<span>{{ $cart->size }}</span>@endif
                    </div>
                    @endif
                    <div class="mc-row">
                        <div class="mc-qty">
                            <button type="button" class="cartUpdate" data-url="{{ route('changeToCart', [$cart, 'decrement']) }}" aria-label="Decrease quantity" {{ $cart->quantity <= 1 ? 'disabled' : '' }}><i class="fa-solid fa-minus"></i></button>
                            <span>{{ $cart->quantity }}</span>
                            <button type="button" class="cartUpdate" data-url="{{ route('changeToCart', [$cart, 'increment']) }}" aria-label="Increase quantity"><i class="fa-solid fa-plus"></i></button>
                        </div>
                        <b class="mc-price">{{ priceFullFormat($cart->quantity * $cart->itemprice()) }}</b>
                    </div>
                </div>
                <button type="button" class="mc-remove cartUpdate" data-url="{{ route('changeToCart', [$cart, 'delete']) }}" aria-label="Remove {{ $cart->product->name }}"><i class="fa-regular fa-trash-can"></i></button>
            </div>
            @endforeach
        </div>

        <div class="mc-foot">
            <div class="mc-subtotal">
                <span>Subtotal</span>
                <b>@isset($cartTotalPrice) {{ priceFullFormat($cartTotalPrice) }} @endisset</b>
            </div>
            <p class="mc-note"><i class="fa-solid fa-truck"></i> Delivery charge is calculated at checkout</p>
            <a class="mc-checkout" href="{{ route('checkout') }}">Checkout <i class="fa-solid fa-arrow-right"></i></a>
            <a class="mc-view" href="{{ route('carts') }}">View Cart</a>
        </div>
        @else
        <div class="mc-empty">
            <div class="mc-empty-icon"><i class="fa-solid fa-bag-shopping"></i></div>
            <h4>Your cart is empty</h4>
            <p>Add something you love and it will show up here.</p>
            <a href="{{ route('index') }}" class="mc-checkout" style="width:auto;padding:0 26px;">Start Shopping</a>
        </div>
        @endif
    </div>
</div>
