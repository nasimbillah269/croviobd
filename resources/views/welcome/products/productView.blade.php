@extends(general()->theme.'.layouts.app') @section('title')
@php
    $seoUrl = route('productView', $product->slug ?: Str::slug($product->name));
    $seoTitle = $product->seo_title ?: $product->name;
    $seoDesc = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode(
        $product->seo_desc ?: ($product->short_description ?: ($product->description ?: $product->name.' - '.general()->meta_description))
    )))), 158, '…');
    $seoCategory = $product->productCategories->first();
    $seoBrand = $product->brand;
    $seoReviews = $product->productReviews()->where('status', 'active');
    $seoReviewCount = $seoReviews->count();
    $seoImages = collect([asset($product->image())])->merge($product->galleryFiles->map(fn($g) => asset($g->image())))->unique()->values()->all();
    $seoProduct = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
        'image' => $seoImages,
        'description' => $seoDesc,
        'sku' => $product->sku_code ?: (string) $product->id,
        'brand' => $seoBrand ? ['@type' => 'Brand', 'name' => $seoBrand->name] : null,
        'category' => $seoCategory ? $seoCategory->name : null,
        'offers' => [
            '@type' => 'Offer',
            'url' => $seoUrl,
            'priceCurrency' => 'BDT',
            'price' => number_format((float) $product->offerPrice(), 2, '.', ''),
            'availability' => $product->stockStatus() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'itemCondition' => 'https://schema.org/NewCondition',
            'seller' => ['@type' => 'Organization', 'name' => general()->title],
        ],
        'aggregateRating' => $seoReviewCount > 0 ? [
            '@type' => 'AggregateRating',
            'ratingValue' => round($seoReviews->avg('rating'), 1),
            'reviewCount' => $seoReviewCount,
        ] : null,
    ]);
    $seoCrumbs = [['Home', route('index')]];
    if ($seoCategory) $seoCrumbs[] = [$seoCategory->name, route('productCategory', $seoCategory->slug)];
    $seoCrumbs[] = [$product->name, $seoUrl];
@endphp
<title>{{$seoTitle}} - {{general()->title}}</title>
@endsection @section('SEO')
        <meta name="description" content="{{$seoDesc}}" />
        <meta name="keywords" content="{{$product->seo_keyword?:$product->name.' '.general()->meta_keyword}}" />
        <meta property="og:title" content="{{$seoTitle}}" />
        <meta property="og:description" content="{{$seoDesc}}" />
        <meta property="og:image" content="{{asset($product->image())}}" />
        <meta property="og:url" content="{{$seoUrl}}" />
        <meta property="product:price:amount" content="{{number_format((float) $product->offerPrice(), 2, '.', '')}}" />
        <meta property="product:price:currency" content="BDT" />
        <script type="application/ld+json">{!! json_encode($seoProduct, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($seoCrumbs)->values()->map(fn($c, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $c[0], 'item' => $c[1]])->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endsection
@section('ogType', 'product')

@push('css')


<link rel="stylesheet" type="text/css" href="{{asset('magnificjs/xzoom.css')}}" media="all" /> 
<link type="text/css" rel="stylesheet" media="all" href="{{asset('magnificjs/fancybox/source/jquery.fancybox.css')}}" />
<link type="text/css" rel="stylesheet" media="all" href="{{asset('magnificjs/magnific-popup/css/magnific-popup.css')}}" />


<style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    textarea:focus, input:focus{
    outline: none;
    }
    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
    .singleBuyCart {
        background: #ff005e;
        display: block;
        color: white;
        padding: 8px 0px;
    }
    
    .singleBuyCart:hover {
        color: white;
        box-shadow: 5px 7px 5px #ccc;
    }
    a.singleaddCart:hover{
        color: white;
    }
    .productVariationBox{
        margin-top: 8px;
        margin-bottom: 20px;
    }
    .variationGroup{
        margin-bottom: 18px;
    }
    .variationGroup label{
        display:block;
        font-weight: 600;
        font-size: 13px;
        letter-spacing: .3px;
        text-transform: uppercase;
        color: #555;
        margin-bottom: 10px;
    }
    .variationOptions{
        display:flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
    }
    .variationOption{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        position: relative;
        min-width: 42px;
        height: 42px;
        padding: 0 14px;
        border: 1px solid #e2e2e2;
        border-radius: 6px;
        cursor: pointer;
        background: #fff;
        color: #333;
        font-size: 14px;
        font-weight: 500;
        user-select: none;
        transition: all .15s ease-in-out;
        box-shadow: 0 1px 2px rgba(0,0,0,.04);
    }
    .variationOption:hover{
        border-color: #ff005e;
        color: #ff005e;
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0,0,0,.08);
    }
    .variationOption.active{
        border-color: #ff005e;
        background: #fff0f5;
        color: #ff005e;
        box-shadow: 0 0 0 1px #ff005e inset;
    }
    .variationOption.variationColor{
        width: 36px;
        height: 36px;
        min-width: 36px;
        padding: 0;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 0 0 1px #e2e2e2, 0 1px 3px rgba(0,0,0,.08);
    }
    .variationOption.variationColor:hover{
        transform: translateY(-1px);
        box-shadow: 0 0 0 1px #ff005e, 0 3px 6px rgba(0,0,0,.12);
    }
    .variationOption.variationColor.active{
        box-shadow: 0 0 0 2px #ff005e, 0 2px 5px rgba(0,0,0,.12);
    }
    .variationOption.variationColor.active::after{
        content: "\2713";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        color: #fff;
        mix-blend-mode: difference;
        font-size: 15px;
        font-weight: 700;
    }
    .variationOption.variationImage{
        width: 54px;
        height: 54px;
        padding: 3px;
        border-radius: 8px;
    }
    .variationOption.variationImage img{
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 5px;
    }
    .variationMessage{
        font-size: 13px;
        padding: 8px 12px;
        margin-bottom: 12px;
        border-radius: 6px;
        background: #fdecec;
        border: 1px solid #f5c2c2;
    }
    .variationOption.variationDisabled{
        opacity: 0.35;
        text-decoration: line-through;
    }
    .variationSelectedName{
        font-size: 20px;
        font-weight: 700;
        color: #222;
        margin-left: 4px;
    }
    .addcartDisabled{
        opacity: 0.5;
        pointer-events: none;
    }

    /* ---------- premium product page ---------- */
    .singleProductPage { padding-top: 22px; background: #f7f7f9; }
    .singleProductPage .products-section { background: transparent !important; padding-bottom: 32px; }
    .singleProductPage .products-section > .container { padding: 0; }

    .pd-breadcrumb { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 18px; font-size: 13.5px; color: #6b7280; }
    .pd-breadcrumb a { color: #6b7280; text-decoration: none; transition: color .15s; }
    .pd-breadcrumb a:hover { color: #ff005e; }
    .pd-breadcrumb > i { font-size: 10px; color: #c4c7cc; }
    .pd-breadcrumb span { color: #111827; font-weight: 500; }

    /* gallery */
    .pd-gallery {
        background: #fff; border: 1px solid #eceef1; border-radius: 16px; padding: 14px;
        box-shadow: 0 1px 3px rgba(16,24,40,.05);
    }
    .pd-gallery #xzoom-fancy { width: 100%; aspect-ratio: 1 / 1; object-fit: contain; border: 0 !important; box-shadow: none !important; border-radius: 12px; background: #f6f7f9; cursor: zoom-in; }
    .pd-gallery .xzoom-thumbs { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; }
    .pd-gallery .xzoom-thumbs a { display: block; }
    .pd-gallery .xzoom-gallery4 {
        width: 72px; height: 72px; object-fit: cover; margin: 0 !important;
        border: 2px solid transparent !important; border-radius: 10px; background: #f6f7f9;
        box-shadow: none !important; transition: border-color .15s, opacity .15s; opacity: .75;
    }
    .pd-gallery .xzoom-gallery4:hover { opacity: 1; }
    .pd-gallery .xzoom-gallery4.xactive { border-color: #ff005e !important; opacity: 1; }

    /* info card */
    .pd-info {
        background: #fff; border: 1px solid #eceef1; border-radius: 16px; padding: 26px 28px;
        box-shadow: 0 1px 3px rgba(16,24,40,.05); height: 100%;
    }
    .pd-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 10px; }
    .pd-cat {
        display: inline-block; padding: 4px 12px; border-radius: 999px;
        background: #fff0f5; color: #ff005e !important; font-size: 12.5px; font-weight: 600; text-decoration: none !important;
    }
    .pd-admin-edit { font-size: 12.5px; font-weight: 600; color: #374151 !important; background: #f3f4f6; padding: 4px 12px; border-radius: 999px; text-decoration: none !important; }
    .pd-info h1.singleProducttitle { font-size: 28px; font-weight: 700; line-height: 1.25; color: #111827; margin: 0 0 10px; }
    .pd-meta { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; font-size: 13.5px; color: #6b7280; margin-bottom: 18px; }
    .pd-stars i { color: #f59e0b; font-size: 13px; }
    .pd-stars i.off { color: #e5e7eb; }
    .pd-dot-sep { width: 4px; height: 4px; border-radius: 50%; background: #d1d5db; }

    .pd-price-box {
        display: flex; align-items: center; flex-wrap: wrap; gap: 12px;
        padding: 16px 18px; margin-bottom: 14px; border-radius: 12px;
        background: linear-gradient(120deg, #fff5f9 0%, #fff 100%); border: 1px solid #ffe0ec;
    }
    .pd-price-box .singlPrice { margin: 0; padding: 0; font-size: 30px; font-weight: 800; color: #ff005e; display: flex; align-items: baseline; gap: 12px; }
    .pd-price-box .singlPrice del { font-size: 17px; font-weight: 500; color: #9ca3af; }
    .pd-save { padding: 5px 12px; border-radius: 999px; background: #16a34a; color: #fff; font-size: 13px; font-weight: 700; }

    .pd-stock { display: inline-flex !important; align-items: center; gap: 8px; margin: 0 0 16px !important; padding: 0 !important; border: 0 !important; font-size: 14px; font-weight: 600; color: #15803d; }
    .pd-stock-dot { width: 9px; height: 9px; border-radius: 50%; background: #16a34a; box-shadow: 0 0 0 4px rgba(22,163,74,.15); }
    .pd-stock-out { color: #b91c1c; }
    .pd-stock-out .pd-stock-dot { background: #dc2626; box-shadow: 0 0 0 4px rgba(220,38,38,.15); }
    .pd-excerpt { font-size: 14.5px; line-height: 1.7; color: #4b5563; margin-bottom: 18px; padding-bottom: 18px; border-bottom: 1px dashed #e5e7eb; }
    .pd-excerpt p:last-child { margin-bottom: 0; }

    /* quantity + buttons */
    .pd-actions { display: flex; align-items: stretch; gap: 12px; flex-wrap: wrap; margin-top: 6px; }
    .pd-qty.quantity {
        display: inline-flex !important; align-items: center; width: auto !important; height: 52px; margin: 0 !important;
        border: 1px solid #e5e7eb; border-radius: 12px; padding: 4px; background: #fff; float: none !important;
    }
    .pd-qty input.qtyminus, .pd-qty input.qtyplus {
        width: 42px !important; height: 42px !important; border: 0 !important; border-radius: 9px !important;
        background: #f3f4f6 !important; color: #111827 !important; font-size: 18px; font-weight: 700; cursor: pointer; transition: background .15s, color .15s;
    }
    .pd-qty input.qtyminus:hover, .pd-qty input.qtyplus:hover { background: #ff005e !important; color: #fff !important; }
    .pd-qty input.qty { width: 54px !important; height: 42px !important; border: 0 !important; text-align: center; font-size: 17px; font-weight: 700; background: transparent !important; }
    .pd-btn {
        position: relative; flex: 1 1 160px; height: 52px; margin: 0 !important;
        display: inline-flex !important; align-items: center; justify-content: center; gap: 10px;
        border-radius: 12px !important; font-size: 16px; font-weight: 700; text-transform: none !important; letter-spacing: 0 !important; padding: 0 18px !important;
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
    }
    .pd-btn-cart { background: #111827 !important; color: #fff !important; }
    .pd-btn-cart:hover { background: #000 !important; transform: translateY(-2px); box-shadow: 0 10px 22px rgba(17,24,39,.25); }
    .pd-btn-buy { background: linear-gradient(120deg, #ff005e 0%, #ff3d84 100%) !important; color: #fff !important; box-shadow: 0 10px 22px rgba(255,0,94,.28); }
    .pd-btn-buy:hover { transform: translateY(-2px); box-shadow: 0 14px 28px rgba(255,0,94,.38); }
    .pd-btn-out { flex: 0 0 auto; background: #f3f4f6; color: #9ca3af; cursor: not-allowed; }
    .pd-btn-loader { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 22px; height: 22px; }
    .pd-msg > div { margin-top: 12px; border: 0 !important; border-radius: 10px; background: #ecfdf5; color: #047857; font-weight: 600; padding: 10px 14px !important; }

    /* trust row */
    .pd-trust { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 22px; padding-top: 20px; border-top: 1px solid #f1f2f4; }
    .pd-trust > * { display: flex; align-items: center; gap: 10px; padding: 12px; border-radius: 12px; background: #f9fafb; color: #374151 !important; text-decoration: none !important; }
    .pd-trust i { width: 36px; height: 36px; flex-shrink: 0; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; background: #fff0f5; color: #ff005e; font-size: 15px; }
    .pd-trust span { font-size: 12.5px; line-height: 1.35; color: #6b7280; }
    .pd-trust b { display: block; font-size: 13.5px; color: #111827; }
    a.pd-trust-link:hover, .pd-trust a:hover { background: #fff0f5; }

    /* description */
    .pd-desc-card { margin-top: 24px; background: #fff; border: 1px solid #eceef1; border-radius: 16px; padding: 24px 28px; box-shadow: 0 1px 3px rgba(16,24,40,.05); }
    .pd-desc-card h2 { display: flex; align-items: center; gap: 10px; font-size: 19px; font-weight: 700; color: #111827; margin: 0 0 16px; padding-bottom: 14px; border-bottom: 1px solid #f1f2f4; }
    .pd-desc-card h2 i { color: #ff005e; }
    .pd-desc { font-size: 15px; line-height: 1.8; color: #374151; }
    .pd-desc img { max-width: 100%; height: auto; border-radius: 10px; }
    .pd-desc h6 { display: none; }

    .pd-related-title h3 { display: inline-flex; align-items: center; gap: 12px; font-size: 22px; font-weight: 700; }
    .pd-related-title h3:before { content: ''; width: 5px; height: 24px; border-radius: 4px; background: linear-gradient(180deg, #ff005e, #ff3d84); }

    @media (max-width: 991.98px) {
        .pd-gallery { margin-bottom: 18px; }
    }
    @media (max-width: 767.98px) {
        .singleProductPage { padding-top: 12px; }
        .pd-info { padding: 18px 16px; }
        .pd-info h1.singleProducttitle { font-size: 21px; }
        .pd-price-box .singlPrice { font-size: 24px; }
        .pd-qty.quantity { width: 100% !important; justify-content: space-between; }
        .pd-btn { flex: 1 1 calc(50% - 6px); font-size: 15px; }
        .pd-trust { grid-template-columns: 1fr; }
        .pd-desc-card { padding: 18px 16px; }
        .pd-gallery .xzoom-gallery4 { width: 60px; height: 60px; }
    }
</style>


@endpush 

@section('contents')

@php $mainProduct = $product; @endphp

<div class="singleProductPage">
    <div class="container">
        <div class="products-section">
            <nav class="pd-breadcrumb" aria-label="Breadcrumb">
                <a href="{{route('index')}}"><i class="fa-solid fa-house"></i> Home</a>
                @foreach($product->productCategories as $ctg)
                <i class="fa-solid fa-angle-right"></i>
                <a href="{{route('productCategory',$ctg->slug)}}">{{$ctg->name}}</a>
                @endforeach
                <i class="fa-solid fa-angle-right"></i>
                <span>{{ Str::limit($product->name, 50) }}</span>
            </nav>
            <div class="container" >
                <div class="row">
                    <div class="col-lg-6">
                        <div class="xzoom-container pd-gallery">
                           
                          
                          <img class="xzoom4" id="xzoom-fancy" src="{{asset($product->image())}}" xoriginal="{{asset($product->image())}}" alt="{{$product->name}}" />

                            <div class="xzoom-thumbs">
                                <a href="{{asset($product->image())}}"><img class="xzoom-gallery4"  src="{{asset($product->image())}}"  xpreview="{{asset($product->image())}}" alt="{{$product->name}}" title="{{$product->name}}" loading="lazy"></a>
                                @foreach($product->galleryFiles as $gall)
                                    <a href="{{asset($gall->image())}}"><img class="xzoom-gallery4"  src="{{asset($gall->image())}}" alt="{{$product->name}} - image {{$loop->iteration + 1}}" title="{{$product->name}}" loading="lazy"></a>
                                @endforeach
                                @if($product->variation_status && $product->productAttibutes->count() > 0)
                                @foreach(collect($product->variationSkuMap())->pluck('image')->filter()->unique() as $variantImage)
                                    <a href="{{$variantImage}}"><img class="xzoom-gallery4"  src="{{$variantImage}}" alt="{{$product->name}}" title="{{$product->name}}" loading="lazy"></a>
                                @endforeach
                                @endif
                          </div>
                          

                          <!--<img class="xzoom4" id="xzoom-fancy" src="{{asset($product->image())}}" xoriginal="{{asset($product->image())}}" />-->
                      
                              
                         
                          
                         
                          
                        </div>
                        <!--<div class="alertdiv">-->
                        <!--    @if($product->stockStatus()==false)-->
                        <!--    <img src="{{asset('batikrom/images/stockOut.png')}}">-->
                        <!--    @endif-->
                        <!--</div>-->
                        <!--<img src="{{asset($product->image())}}"  alt="{{$product->name}}" style="max-width:100%;" />-->
                    </div>

                    <div class="col-lg-6">
                        <div class="products-process pd-info">
                            @php
                                $pdCategory = $product->productCategories->first();
                                $pdDiscount = $product->discountPercent();
                                $pdHasVariation = $product->variation_status && $product->productAttibutes->count() > 0;
                            @endphp
                            <div class="pd-top">
                                @if($pdCategory)
                                <a href="{{route('productCategory',$pdCategory->slug)}}" class="pd-cat">{{$pdCategory->name}}</a>
                                @endif
                                @if(Auth::check() && Auth::user()->admin)
                                <a href="{{route('admin.productsEdit',$product->id)}}" target="_blank" class="pd-admin-edit"><i class="fa-solid fa-pen"></i> Edit</a>
                                @endif
                            </div>
                            <h1 class="singleProducttitle">{{$product->name}}</h1>

                            <div class="pd-meta">
                                <span class="pd-stars">
                                    @for($pdStar = 1; $pdStar <= 5; $pdStar++)
                                    <i class="fa-solid fa-star {{ $pdStar <= round($product->productRating()) ? "" : "off" }}"></i>
                                    @endfor
                                </span>
                                <span>{{$product->productRating()}}/5 ({{$product->reviewTotal()}} reviews)</span>
                                <span class="pd-dot-sep"></span>
                                <span>{{$product->salesAll()->count()}} sold</span>
                                @if($product->sku_code)
                                <span class="pd-dot-sep"></span>
                                <span>SKU: {{$product->sku_code}}</span>
                                @endif
                            </div>

                            <div class="pd-price-box">
                                <h5 class="singlPrice"
                                data-currency="{{general()->currency}}"
                                data-currency-position="{{general()->currency_position}}"
                                data-decimal="{{general()->currency_decimal}}"
                                data-base-price="{{$product->final_price}}">
                                @if($product->regular_price > $product->final_price)
                                <del>{{priceFullFormat($product->regular_price)}}</del>
                                @endif
                                {{priceFullFormat($product->final_price)}}
                                </h5>
                                @if($pdDiscount > 0 && !$pdHasVariation)
                                <span class="pd-save">Save {{$pdDiscount}}%</span>
                                @endif
                            </div>

                            @if($product->stockStatus()==false)
                            <p class="pd-stock pd-stock-out"><span class="pd-stock-dot"></span> Out of stock</p>
                            @else
                            <p class="stockAvailable pd-stock"><span class="pd-stock-dot"></span> In stock &middot; <span class="stockAvailableQty">{{$product->quantity}}</span> available</p>
                            @endif

                            @if(trim(strip_tags($product->short_description)))
                            <div class="single-excerpt pd-excerpt">
                                {!!$product->short_description!!}
                            </div>
                            @endif

                            <form action="" class="addToCartProduct_{{$product->id}}">

                            @if($product->variation_status && $product->productAttibutes->count() > 0)
                            <div class="productVariationBox"
                            data-product-id="{{$product->id}}"
                            data-base-price="{{$product->final_price}}"
                            data-base-qty="{{$product->quantity}}">
                                @foreach($product->productAttibutes->unique('reff_id') as $group)
                                @if($group->attribute)
                                @php
                                    $isColorGroup = $group->attribute->view==2 || str_contains(strtolower($group->attribute->name),'colo');
                                @endphp
                                <div class="variationGroup" data-color-group="{{$isColorGroup?1:0}}">
                                    <label>{{ucfirst($group->attribute->name)}}: <span class="variationSelectedName"></span></label>
                                    <div class="variationOptions">
                                        @foreach($product->productAttibutes->where('reff_id',$group->reff_id) as $item)
                                        @if($item->attributeItem)
                                        <span class="variationOption @if($isColorGroup) variationColor @elseif($group->attribute->view==3) variationImage @else variationText @endif"
                                        data-item-id="{{$item->attributeItem->id}}"
                                        data-name="{{$item->attributeItem->name}}"
                                        title="{{$item->attributeItem->name}}"
                                        @if($isColorGroup)
                                        style="background-color: {{$item->value_1?:($item->attributeItem->icon?:strtolower($item->attributeItem->name))}};"
                                        @endif
                                        >
                                        @if($isColorGroup)
                                        @elseif($group->attribute->view==3)
                                        <img src="{{asset($item->image())}}" alt="{{$item->attributeItem->name}}">
                                        @else
                                        {{$item->attributeItem->name}}
                                        @endif
                                        </span>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                <div class="variationMessage text-danger" style="display:none;"></div>
                                <input type="hidden" name="sku_id" class="variationSkuIdInput" value="">
                            </div>
                            @endif



                            <div class="qountity-plus">
                                <span class="MessageQty{{$product->id}}"></span>
                                @if($product->stockStatus()==false)
                                <span class="pd-btn pd-btn-out"><i class="fa-solid fa-ban"></i> Stock Out</span>
                                @else
                                <div class="pd-actions">
                                    <div class="quantity pd-qty">
                                        <input type="button" value="-" class="qtyminus Quantityminus" data-min="{{$product->productMinQty()}}" data-id="{{$product->id}}" field="quantity" aria-label="Decrease quantity" />
                                        @if($hCart =hasCart(request()->cookie('carts'),$product->id))
                                        <input type="number" id="quantity" name="quantity" value="{{$hCart->quantity}}" class="qty qty_{{$product->id}}" aria-label="Quantity" />
                                        @else
                                        <input type="number" id="quantity" name="quantity" value="{{$product->productMinQty()}}" class="qty qty_{{$product->id}}" aria-label="Quantity" />
                                        @endif
                                        <input type="button" value="+" class="qtyplus Quantityplus" data-max="{{$product->productMaxQty()}}" data-id="{{$product->id}}" field="quantity" aria-label="Increase quantity" />
                                    </div>
                                    <a href="javascript:void(0)" class="btn singleaddCart pd-btn pd-btn-cart" data-id="{{$product->id}}" onclick="addToCart({{ json_encode($product) }})" data-url="{{route('addToCart',$product->id)}}">
                                        <img src="{{asset('medies/loading.gif')}}" class="pd-btn-loader" style="display:none;" alt="">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                        <span>Add to Cart</span>
                                    </a>
                                    <a href="javascript:void(0)" class="btn singleBuyCart addCartShow_{{$product->id}} pd-btn pd-btn-buy" data-id="{{$product->id}}" data-url="{{route('addToCart',[$product->id,'orderNow'=>true])}}">
                                        <img src="{{asset('medies/loading.gif')}}" class="pd-btn-loader" style="display:none;" alt="">
                                        <span>Buy Now</span>
                                        <i class="fa-solid fa-bolt"></i>
                                    </a>
                                </div>
                                @endif
                                <span class="MessageSuccess{{$product->id}} pd-msg"></span>
                            </div>
                            </form>

                            @if($mainProduct->variation_status && $mainProduct->productAttibutes->count() > 0)
                            <script>
                            (function(){
                                var variationMap = @json($mainProduct->variationSkuMap());
                                var productId = "{{ $mainProduct->id }}";
                                var box = document.querySelector('.productVariationBox[data-product-id="'+productId+'"]');
                                if(!box) return;

                                var groups = box.querySelectorAll('.variationGroup');
                                var skuInput = box.querySelector('.variationSkuIdInput');
                                var msgEl = box.querySelector('.variationMessage');
                                var priceEl = document.querySelector('.singlPrice');
                                var stockEl = document.querySelector('.stockAvailableQty');
                                var qtyPlus = document.querySelector('.qtyplus.Quantityplus[data-id="'+productId+'"]');
                                var qtyInput = document.querySelector('.qty_'+productId);
                                var addButtons = document.querySelectorAll('.singleaddCart[data-id="'+productId+'"], .singleBuyCart[data-id="'+productId+'"]');

                                var currency = priceEl ? priceEl.getAttribute('data-currency') : '';
                                var currencyPos = priceEl ? priceEl.getAttribute('data-currency-position') : '0';
                                var decimals = priceEl ? parseInt(priceEl.getAttribute('data-decimal')) : 0;

                                function formatPrice(amount){
                                    var fixed = Number(amount).toFixed(decimals);
                                    var parts = fixed.split('.');
                                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                    var formatted = parts.join('.');
                                    return currencyPos=='0' ? (currency+' '+formatted) : (formatted+' '+currency);
                                }

                                function setAddButtonsEnabled(enabled){
                                    addButtons.forEach(function(b){
                                        b.classList.toggle('addcartDisabled', !enabled);
                                    });
                                }

                                var skuList = [];
                                for(var k in variationMap){ skuList.push(variationMap[k]); }

                                groups = Array.prototype.slice.call(groups);

                                function activeId(g){
                                    var sel = g.querySelector('.variationOption.active');
                                    return sel ? sel.getAttribute('data-item-id') : null;
                                }

                                //SKU contains every selected item of the other groups
                                function compatible(sku, exceptGroup){
                                    return groups.every(function(g){
                                        if(g===exceptGroup) return true;
                                        var id = activeId(g);
                                        return !id || sku.items.indexOf(id)!==-1;
                                    });
                                }

                                //Disable options not available with current selection,
                                //hide a group (e.g. Size) when the selected Color has no item of it
                                function refreshAvailability(){
                                    for(var pass=0; pass<groups.length; pass++){
                                        var changed = false;
                                        groups.forEach(function(g){
                                            var options = g.querySelectorAll('.variationOption');
                                            var candidates = skuList.filter(function(sku){ return compatible(sku, g); });
                                            var groupNeeded = false;
                                            options.forEach(function(opt){
                                                var id = opt.getAttribute('data-item-id');
                                                var available = candidates.some(function(sku){ return sku.items.indexOf(id)!==-1; });
                                                if(available) groupNeeded = true;
                                                opt.classList.toggle('variationDisabled', !available);
                                                if(!available && opt.classList.contains('active')){
                                                    opt.classList.remove('active');
                                                    changed = true;
                                                }
                                            });
                                            g.style.display = groupNeeded ? '' : 'none';
                                        });
                                        if(!changed) break;
                                    }
                                }

                                function currentSelection(){
                                    var ids = [];
                                    var missing = false;
                                    groups.forEach(function(g){
                                        var id = activeId(g);
                                        if(id){ ids.push(id); }
                                        else if(g.style.display!=='none'){ missing = true; }
                                    });
                                    var combo = null;
                                    if(ids.length){
                                        combo = skuList.filter(function(sku){
                                            return sku.items.length===ids.length && ids.every(function(id){ return sku.items.indexOf(id)!==-1; });
                                        })[0] || null;
                                    }
                                    return {combo:combo, missing:missing};
                                }

                                var mainImg = document.getElementById('xzoom-fancy');
                                var defaultImage = mainImg ? mainImg.getAttribute('src') : '';

                                function setMainImage(url){
                                    if(!mainImg || mainImg.getAttribute('src')===url) return;
                                    mainImg.setAttribute('src', url);
                                    mainImg.setAttribute('xoriginal', url);
                                    document.querySelectorAll('.xzoom-thumbs .xzoom-gallery4').forEach(function(t){
                                        t.classList.toggle('xactive', t.parentNode.getAttribute('href')===url);
                                    });
                                }

                                function updateSelectedNames(){
                                    groups.forEach(function(g){
                                        var sel = g.querySelector('.variationOption.active');
                                        var nameEl = g.querySelector('.variationSelectedName');
                                        if(nameEl){ nameEl.textContent = sel ? sel.getAttribute('data-name') : ''; }
                                    });
                                }

                                function updateImage(){
                                    var combo = currentSelection().combo;
                                    if(combo && combo.image){ setMainImage(combo.image); return; }

                                    var colorGroup = box.querySelector('.variationGroup[data-color-group="1"]');
                                    var colorId = colorGroup ? activeId(colorGroup) : null;
                                    if(!colorId) return;
                                    for(var i=0; i<skuList.length; i++){
                                        if(skuList[i].image && skuList[i].items.indexOf(colorId)!==-1){ setMainImage(skuList[i].image); return; }
                                    }
                                    setMainImage(defaultImage);
                                }

                                function updateUI(){
                                    refreshAvailability();

                                    var sel = currentSelection();
                                    var combo = sel.combo;

                                    updateSelectedNames();
                                    updateImage();

                                    if(combo){
                                        skuInput.value = combo.sku_id;
                                        msgEl.style.display='none';

                                        if(priceEl){ priceEl.innerHTML = formatPrice(combo.price); }
                                        if(stockEl){ stockEl.textContent = combo.quantity; }
                                        if(qtyPlus){ qtyPlus.setAttribute('data-max', combo.quantity); }
                                        if(qtyInput && parseInt(qtyInput.value) > combo.quantity){
                                            qtyInput.value = combo.quantity > 0 ? combo.quantity : 1;
                                        }

                                        if(combo.quantity > 0){
                                            setAddButtonsEnabled(true);
                                        }else{
                                            setAddButtonsEnabled(false);
                                            msgEl.textContent = 'This option is out of stock';
                                            msgEl.style.display = 'block';
                                        }
                                    }else if(sel.missing){
                                        skuInput.value='';
                                        msgEl.style.display='none';
                                        setAddButtonsEnabled(false);
                                    }else{
                                        skuInput.value='';
                                        msgEl.textContent = 'This combination is not available';
                                        msgEl.style.display='block';
                                        setAddButtonsEnabled(false);
                                    }
                                }

                                groups.forEach(function(g){
                                    var options = g.querySelectorAll('.variationOption');
                                    if(options.length===1){
                                        options[0].classList.add('active');
                                    }
                                    options.forEach(function(opt){
                                        opt.addEventListener('click', function(){
                                            //Unavailable option clicked: start fresh from this option
                                            if(opt.classList.contains('variationDisabled')){
                                                groups.forEach(function(other){
                                                    if(other!==g){
                                                        other.querySelectorAll('.variationOption').forEach(function(o){ o.classList.remove('active'); });
                                                    }
                                                });
                                            }
                                            options.forEach(function(o){ o.classList.remove('active'); });
                                            opt.classList.add('active');
                                            updateUI();
                                        });
                                    });
                                });

                                updateUI();
                            })();
                            </script>
                            @endif


                            <div class="pd-trust">
                                <div><i class="fa-solid fa-truck"></i><span><b>Cash on Delivery</b>Pay when you receive</span></div>
                                <div><i class="fa-solid fa-qrcode"></i><span><b>Bangla QR</b>Pay with any app</span></div>
                                @if(general()->mobile)
                                <a href="tel:{{general()->mobile}}"><i class="fa-solid fa-headset"></i><span><b>Need help?</b>{{general()->mobile}}</span></a>
                                @endif
                            </div>

                            {{--<p class="categorysingle"><b>Category:</b> 
                            @foreach($product->productCategories as $i=>$ctg)
                            {{$i==0?'':'-'}} {{$ctg->name}} 
                            @endforeach
                            </p>--}}
                            
                         
                            
                            {{--<div class="singleSocial">
                                <ul>
                                    <li>
                                        <a href="#" style="background: #3a579a;"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                    </li>
                                    <li>
                                        <a href="#" style="background: #0da8e3;"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                    </li>
                                    
                                    <li>
                                        <a href="#" style="background-image: linear-gradient(#4560ca, #ff5745,#ffd057);"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                                    </li>
                                    <li>
                                        <a href="#" style="background: #1c9a11;"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                                    </li>
                                </ul>
                            </div>--}}
                            
                        </div>
                    </div>
                </div>

                @if(trim(strip_tags($product->description, '<img>')))
                <div class="pd-desc-card">
                    <h2><i class="fa-regular fa-file-lines"></i> Product Description</h2>
                    <div class="pd-desc proudctVeiwDescription">
                        {!!$product->description!!}
                    </div>
                </div>
                @endif
            </div>

            {{--<div class="description-parts">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <p class="nav-link Productdescription active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true" style="cursor: pointer; margin: 0;">
                            Additional Information
                        </p>
                    </li>
                    <li class="nav-item">
                        <p class="nav-link Productdescription" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false" style="cursor: pointer; margin: 0;">
                            Reviews
                        </p>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent" style="overflow: auto;">
                    <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="productnoticelist">
                            <p>
                                No Description is available
                            </p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                        <div class="productnoticelist">
                            <p>
                                No Review
                            </p>
                        </div>
                    </div>
                </div>
            </div>--}}


            
        </div>
    </div>
    
    <div class="singleRelated">
        <div class="container">
            
        <div class="section-title pd-related-title" style="margin-bottom: 0">
            <h3>Recommended for you</h3>

        </div>
        
        <div class="slider-one product-grids">
                    @foreach($relatedProducts as $product)
                    <div class="slick-box">
                        
                      @include(general()->theme.'.products.includes.productCard1')
                    
                    
                    </div>
                    @endforeach
                    
                </div>
        </div>
    </div>
</div>

@endsection 

@push('js') 
  <script type="text/javascript" src="{{asset('magnificjs/xzoom.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('magnificjs/fancybox/source/jquery.fancybox.js')}}"></script>
  <script type="text/javascript" src="{{asset('magnificjs/magnific-popup/js/magnific-popup.js')}}"></script>  


<script>

window.dataLayer = window.dataLayer || [];

window.dataLayer.push({
    event: "page_view",

    page: {
        page_title: "{{ $product->name }} - {{ general()->title }}",
        page_location: window.location.href,
        page_type: "product detail"
    }
    
});


// View Item Event
window.dataLayer.push({
    event: "view_item",

    ecommerce: {
        currency: "{{ general()->currency }}",
        value: {{ $product->offerPrice() }},

        items: [
            {
                item_id: "{{ $product->id }}",
                item_name: "{{ $product->name }}",
                affiliation: "{{ general()->title }}",
                discount: {{ $product->discount ?? 0 }},
                index: 0,
                item_variant: "{{ $product->variant ?? '' }}",
                price: {{ $product->offerPrice() }},
                quantity: 1,
                sku: "{{ $product->sku ?? '' }}"
            }
        ]
    }
});
console.log('view item event');

function addToCart(product) {
    window.dataLayer = window.dataLayer || [];

    let qty = parseInt($('#quantity').val(), 10) || 1;

    window.dataLayer.push({
        event: "add_to_cart",

        ecommerce: {
            currency: "{{ general()->currency }}",
            value: Number(product.final_price),

            items: [
                {
                    item_id: product.id,
                    item_name: product.name,
                    affiliation: "{{ general()->title }}",
                    discount: product.discount || 0,
                    index: 0,
                    item_variant: product.variant || "",
                    price: Number(product.final_price),
                    quantity: qty,
                    sku: product.sku || ""
                }
            ]
        }
    });
    
    console.log('ad to card event')
}


</script>


	<script>

    $(document).ready(function() {

        $('.xzoom4, .xzoom-gallery4').xzoom({tint: '#006699', Xoffset: 15,zoomWidth: 200,zoomHeight:200,position: 'lens',  sourceClass: 'xzoom-hidden'});

        //Integration with hammer.js
        var isTouchSupported = 'ontouchstart' in window;

        if (isTouchSupported) {
            //If touch device
            $('.xzoom4').each(function(){
                var xzoom = $(this).data('xzoom');
                xzoom.eventunbind();
            });
            

        $('.xzoom4').each(function() {
            var xzoom = $(this).data('xzoom');
            $(this).hammer().on("tap", function(event) {
                event.pageX = event.gesture.center.pageX;
                event.pageY = event.gesture.center.pageY;
                var s = 1, ls;

                xzoom.eventmove = function(element) {
                    element.hammer().on('drag', function(event) {
                        event.pageX = event.gesture.center.pageX;
                        event.pageY = event.gesture.center.pageY;
                        xzoom.movezoom(event);
                        event.gesture.preventDefault();
                    });
                }

                var counter = 0;
                xzoom.eventclick = function(element) {
                    element.hammer().on('tap', function() {
                        counter++;
                        if (counter == 1) setTimeout(openfancy,100);
                        event.gesture.preventDefault();
                    });
                }

                function openfancy() {
                    if (counter == 2) {
                        xzoom.closezoom();
                        $.fancybox.open(xzoom.gallery().cgallery);
                    } else {
                        xzoom.closezoom();
                    }
                    counter = 0;
                }
            xzoom.openzoom(event);
            });
        });
        
        

        } else {
            //If not touch device

            //Integration with fancybox plugin
            $('#xzoom-fancy').bind('click', function(event) {
                var xzoom = $(this).data('xzoom');
                xzoom.closezoom();
                $.fancybox.open(xzoom.gallery().cgallery, {padding: 0, helpers: {overlay: {locked: false}}});
                event.preventDefault();
            });
           
            //Integration with magnific popup plugin
            // $('#xzoom-magnific').bind('click', function(event) {
            //     var xzoom = $(this).data('xzoom');
            //     xzoom.closezoom();
            //     var gallery = xzoom.gallery().cgallery;
            //     var i, images = new Array();
            //     for (i in gallery) {
            //         images[i] = {src: gallery[i]};
            //     }
            //     $.magnificPopup.open({items: images, type:'image', gallery: {enabled: true}});
            //     event.preventDefault();
            // });
        }
    });

	</script>

@endpush