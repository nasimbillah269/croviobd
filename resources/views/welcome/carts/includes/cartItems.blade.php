@isset($carts) @if($carts->count() > 0)
@php
    $cartLines = $carts->filter(function ($cart) { return $cart->product; });
    $itemCount = $cartLines->sum('quantity');
@endphp

<div class="row">
	<div class="col-lg-8">
	    <div class="ct-card">
	        <div class="ct-card-head">
	            <h4>Your Items <span>({{ $itemCount }} {{ Str::plural('item', $itemCount) }})</span></h4>
	            <a href="{{ url('/') }}" class="ct-link"><i class="fa fa-angle-left"></i> Continue shopping</a>
	        </div>

	        <div class="ct-cols">
	            <span>Product</span>
	            <span>Price</span>
	            <span>Quantity</span>
	            <span>Subtotal</span>
	        </div>

	        @foreach($cartLines as $cart)
	        @php
	            $productUrl = route('productView', $cart->product->slug ?: Str::slug($cart->product->name));
	        @endphp
	        <div class="ct-item">
	            <a href="{{ $productUrl }}" class="ct-thumb">
	                <img src="{{ asset($cart->product->image()) }}" alt="{{ $cart->product->name }}">
	            </a>

	            <div class="ct-info">
	                <a href="{{ $productUrl }}" class="ct-name">{{ Str::limit($cart->product->name, 60) }}</a>
	                <div class="ct-meta">
	                    @if($cart->product->weight_unit && $cart->product->weight_amount)
	                    <span class="ct-chip">{{ $cart->product->weight_amount }} {{ $cart->product->weight_unit }}</span>
	                    @endif
	                    @if($cart->color)<span class="ct-chip">Color: {{ $cart->color }}</span>@endif
	                    @if($cart->size)<span class="ct-chip">Size: {{ $cart->size }}</span>@endif
	                </div>
	                <div class="ct-unit-mobile">{{ priceFullFormat($cart->itemprice()) }} each</div>
	            </div>

	            <div class="ct-price">{{ priceFullFormat($cart->itemprice()) }}</div>

	            <div class="ct-qty-wrap">
	                <div class="ct-qty">
	                    <button type="button" class="cartUpdate" data-url="{{ route('changeToCart', [$cart, 'decrement']) }}" aria-label="Decrease quantity" {{ $cart->quantity <= 1 ? 'disabled' : '' }}><i class="fa fa-minus"></i></button>
	                    <input type="number" min="1" name="quantity" value="{{ $cart->quantity }}" class="cartQtyChange" data-url="{{ route('changeToCart', [$cart, 'quantity']) }}" aria-label="Quantity">
	                    <button type="button" class="cartUpdate" data-url="{{ route('changeToCart', [$cart, 'increment']) }}" aria-label="Increase quantity"><i class="fa fa-plus"></i></button>
	                </div>
	            </div>

	            <div class="ct-total">{{ priceFullFormat($cart->quantity * $cart->itemprice()) }}</div>

	            <button type="button" class="ct-remove cartUpdate" data-url="{{ route('changeToCart', [$cart, 'delete']) }}" aria-label="Remove {{ $cart->product->name }}" title="Remove">
	                <i class="fa-regular fa-trash-can"></i>
	            </button>
	        </div>
	        @endforeach
	    </div>
	</div>

	<div class="col-lg-4">
	    <div class="ct-summary">
	        <h4>Order Summary</h4>

	        <div class="ct-row">
	            <span>Items ({{ $itemCount }})</span>
	            <span class="cartSummaryTotal">{{ priceFullFormat($cartTotalPrice) }}</span>
	        </div>
	        <div class="ct-row">
	            <span>Delivery charge</span>
	            <span class="ct-muted">Calculated at checkout</span>
	        </div>

	        <div class="ct-row ct-grand">
	            <span>Subtotal</span>
	            <span>{{ priceFullFormat($cartTotalPrice) }}</span>
	        </div>

	        <a class="ct-checkout" href="{{ route('checkout') }}">
	            Proceed to Checkout <i class="fa fa-arrow-right"></i>
	        </a>
	        <a class="ct-continue" href="{{ url('/') }}">Continue shopping</a>

	        <div class="ct-secure">
	            <i class="fa fa-lock"></i> Safe &amp; secure checkout
	        </div>
	    </div>
	</div>
</div>

{{-- sticky checkout bar on phones --}}
<div class="ct-mobile-bar">
    <div>
        <small>Subtotal</small>
        <strong>{{ priceFullFormat($cartTotalPrice) }}</strong>
    </div>
    <a href="{{ route('checkout') }}">Checkout <i class="fa fa-arrow-right"></i></a>
</div>

@else
<div class="ct-empty">
    <div class="ct-empty-icon"><i class="fa fa-shopping-bag"></i></div>
    <h4>Your cart is empty</h4>
    <p>Looks like you haven't added anything yet. Explore our products and find something you love.</p>
    <a href="{{ url('/') }}" class="ct-checkout" style="display:inline-flex;width:auto;padding:0 28px;">
        @if(session()->get('locale')=='bn') আরো শপিং করুন @else Start Shopping @endif
    </a>
</div>
@endif @endisset
