<div class="product-wrapper">
    <div class="product-img">

        <!-- SKELETON LOADER -->
        <div class="skeleton skeleton-loader"></div>

        <!-- LAZY IMAGE2 -->
        <a href="{{ route('productView', $product->slug ?: Str::slug($product->name)) }}">
            <img class="lazy product-image"
                 data-src="{{ asset($product->image()) }}"
                 alt="{{ $product->name }}">
        </a>

        <div class="product-cart-items">
            <a href="javascript:void(0)" class="cart cart-item wishlistCompareUpdate" data-id="{{$product->id}}" data-url="{{route('wishlistCompareUpdate',[$product->id,'wishlist'])}}">
                @if($product->isWl())
                <i class="fa fa-heart"></i>
                @else
                <i class="fa fa-heart-o"></i>
                @endif
            </a>
        </div>
        @if($product->fetured)
        <div class="topSaleBadg">
            <div class="topSaleBadgIcon">
                <i class="fa fa-certificate" aria-hidden="true"></i>
            </div>
            <div class="topSaleBadgText">
                <p>Top</p>
                <small>Sale</small>
            </div>
        </div>
        @endif
    </div>

    @if($product->discountPercent() > 0)
    <div class="badgText">
        <p>-{{priceFullFormat($product->regular_price-$product->offerPrice())}}</p>
    </div>
    @endif

    <div class="product-info">
        <div class="product-description">
            <a href="{{ route('productView', $product->slug ?: Str::slug($product->name)) }}"
               class="product-details">
               {{ Str::limit($product->name,30) }}
            </a>
            @if($product->weight_amount && $product->weight_unit)
            <span style="display:block;text-align:center;">{{ $product->productWeight() }}</span>
            @endif
            <div class="price">
                @if($product->regular_price > $product->offerPrice())
                <span class="price-cut">{{ priceFullFormat($product->regular_price) }}</span>
                @endif
                <span class="new-price">{{ priceFullFormat($product->offerPrice()) }}</span>
            </div>
        </div>
        <div class="ratings">
            <span>
                <i class="fa fa-star" aria-hidden="true"></i>
                {{$product->productRating()}}/5 ({{$product->reviewTotal()}}) • {{$product->salesAll()->count()}} sold
            </span>
        </div>
        <div class="cartButton">
            @if($product->quantity > 0)
            @if($product->variation_status && $product->productAttibutes->count() > 0)
            <a href="{{route('productView',$product->slug?:Str::slug($product->name))}}" class="btn">
                অর্ডার করুন
            </a>
            @else
            <a href="{{route('addToCart',[$product->id,'orderNow'=>true])}}" class="btn">
                অর্ডার করুন
            </a>
            @endif
            @else
            <a href="javascript:void(0)" class="btn" style="background: white;border: 1px solid gray;color: black;">Stock Out</a>
            @endif
        </div>
    </div>
</div>