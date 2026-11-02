@extends(general()->theme.'.layouts.app') @section('title')
<title>Offer Products - {{general()->title}}</title>
@endsection @section('SEO')
<meta name="description" content="Discount and offer products at {{general()->title}}. {!!general()->meta_description!!}" />
<meta name="keywords" content="offer, discount, {{general()->meta_keyword}}" />
<meta property="og:title" content="Offer Products - {{general()->title}}" />
<meta property="og:description" content="Discount and offer products at {{general()->title}}" />
<meta property="og:url" content="{{route('offerProducts')}}" />
<meta property="og:image" content="{{asset(general()->logo())}}" />
@endsection @push('css')
<style>
    .offerPage { padding: 24px 0 48px; }
    .offer-hero {
        display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
        background: linear-gradient(120deg, #ff005e 0%, #ff4d8d 100%);
        color: #fff; border-radius: 14px; padding: 22px 26px; margin-bottom: 24px;
    }
    .offer-hero h1 { font-size: 24px; font-weight: 700; margin: 0 0 4px; color: #fff; }
    .offer-hero p { margin: 0; font-size: 14px; opacity: .9; }
    .offer-hero .offer-count { background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.4); border-radius: 999px; padding: 7px 16px; font-weight: 600; font-size: 14px; white-space: nowrap; }
    .offer-breadcrumb { font-size: 13px; color: #6b7280; margin-bottom: 14px; }
    .offer-breadcrumb a { color: #6b7280; }
    .offer-breadcrumb a:hover { color: #ff005e; }
    .offerPage .productRow > div { margin-bottom: 4px; }

    .offer-pagination { display: flex; justify-content: center; margin-top: 28px; }
    .offer-pagination .pagination { flex-wrap: wrap; gap: 6px; margin: 0; }
    .offer-pagination .page-link {
        border: 1px solid #e5e7eb !important; border-radius: 8px !important; min-width: 40px; height: 40px;
        display: flex; align-items: center; justify-content: center; color: #374151; font-weight: 600; padding: 0 12px;
    }
    .offer-pagination .page-link:hover { background: #fff0f5; color: #ff005e; border-color: #ffc2d8 !important; }
    .offer-pagination .page-item.active .page-link { background: #ff005e; border-color: #ff005e !important; color: #fff; }
    .offer-pagination .page-item.disabled .page-link { color: #c4c7cc; background: #fafafa; }

    .offer-empty { text-align: center; padding: 60px 20px; background: #fff; border: 1px solid #eceef1; border-radius: 14px; }
    .offer-empty i { font-size: 42px; color: #d1d5db; margin-bottom: 14px; }
    .offer-empty h5 { font-weight: 700; margin-bottom: 6px; }
    .offer-empty p { color: #6b7280; margin-bottom: 18px; }
    .offer-empty a { display: inline-block; background: #ff005e; color: #fff; padding: 10px 22px; border-radius: 8px; font-weight: 600; }

    @media (max-width: 767.98px) {
        .offer-hero { padding: 18px; }
        .offer-hero h1 { font-size: 20px; }
    }
</style>
@endpush

@section('contents')

<div class="offerPage">
    <div class="container">
        <div class="offer-breadcrumb">
            <a href="{{route('index')}}">Home</a> &rsaquo; Offer Products
        </div>

        <div class="offer-hero">
            <div>
                <h1><i class="fa-solid fa-tags"></i> Offer Products</h1>
                <p>Grab the best deals &mdash; all discounted products in one place.</p>
            </div>
            <span class="offer-count">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} on offer</span>
        </div>

        @if($products->count())
        <div class="row productRow">
            @foreach($products as $product)
            <div class="col-lg-3 col-md-4 col-6">
                @include(general()->theme.'.products.includes.productCard1')
            </div>
            @endforeach
        </div>

        @if($products->hasPages())
        <div class="offer-pagination">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
        @endif
        @else
        <div class="offer-empty">
            <i class="fa-solid fa-tags"></i>
            <h5>No offers right now</h5>
            <p>Check back soon for new discounts.</p>
            <a href="{{route('index')}}">Continue Shopping</a>
        </div>
        @endif
    </div>
</div>

@endsection @push('js')
<script>
    $(window).on("load", function () {
        $(".skeleton").fadeOut(500);
    });
</script>
@endpush
