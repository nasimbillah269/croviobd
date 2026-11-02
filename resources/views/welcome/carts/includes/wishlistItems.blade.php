@isset($wlCount)
    <div class="wl-head">
        <h1><i class="fa-solid fa-heart"></i> My Wishlist <span>({{ $wlCount }} {{ Str::plural('item', $wlCount) }})</span></h1>
        @if($wlCount > 0)
        <a href="{{ route('index') }}">Continue shopping <i class="fa fa-arrow-right"></i></a>
        @endif
    </div>

    @if($wlCount > 0)
    <div class="row productRow">
        @foreach($products as $product)
        <div class="col-lg-3 col-md-4 col-6">
            @include(general()->theme.'.products.includes.productCard1')
        </div>
        @endforeach
    </div>

    @if($products->hasPages())
    <div class="wl-pagination">
        {{ $products->links('pagination::bootstrap-4') }}
    </div>
    @endif
    @else
    <div class="wl-empty">
        <div class="wl-empty-icon"><i class="fa-regular fa-heart"></i></div>
        <h4>Your wishlist is empty</h4>
        <p>Tap the heart on any product to save it here for later.</p>
        <a href="{{ route('index') }}">Explore products <i class="fa fa-arrow-right"></i></a>
    </div>
    @endif
@endisset
