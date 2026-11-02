@extends(general()->theme.'.layouts.app') @section('title')
<title>Shopping Cart | {{general()->title}}</title>
@endsection @section('SEO')
<meta name="description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta property="og:title" content="{{general()->meta_title}}" />
<meta property="og:description" content="{!!general()->meta_description!!}" />
<meta property="og:image" content="{{asset(general()->logo())}}" />
<meta property="og:url" content="{{route('index')}}" />
@endsection

@push('css')

<style>
    .ct-page { --ct-primary: #ff005e; --ct-text: #111827; --ct-muted: #6b7280; --ct-border: #eceef1; padding: 28px 0 56px; color: var(--ct-text); }
    .ct-page a { text-decoration: none; }

    /* steps */
    .ct-top { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 22px; }
    .ct-top h1 { font-size: 26px; font-weight: 700; margin: 0; }
    .ct-steps { display: flex; align-items: center; gap: 10px; list-style: none; margin: 0; padding: 0; font-size: 13px; font-weight: 600; color: #9ca3af; }
    .ct-steps li { display: flex; align-items: center; gap: 8px; }
    .ct-steps li span { width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; background: #f3f4f6; font-size: 12px; }
    .ct-steps li.active { color: var(--ct-text); }
    .ct-steps li.active span { background: var(--ct-primary); color: #fff; }
    .ct-steps .ct-step-line { width: 28px; height: 2px; background: #e5e7eb; }

    /* items card */
    .ct-card, .ct-summary { background: #fff; border: 1px solid var(--ct-border); border-radius: 14px; box-shadow: 0 1px 3px rgba(16,24,40,.05); }
    .ct-card { padding: 6px 22px 10px; margin-bottom: 20px; }
    .ct-card-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 16px 0; }
    .ct-card-head h4 { font-size: 17px; font-weight: 700; margin: 0; }
    .ct-card-head h4 span { color: var(--ct-muted); font-weight: 500; font-size: 14px; }
    .ct-link { font-size: 13.5px; font-weight: 600; color: var(--ct-primary) !important; }
    .ct-link:hover { text-decoration: underline; }

    .ct-cols, .ct-item { display: grid; grid-template-columns: 84px minmax(0, 1fr) 110px 130px 110px 36px; align-items: center; gap: 16px; }
    .ct-cols { padding: 10px 0; border-top: 1px solid var(--ct-border); border-bottom: 1px solid var(--ct-border); font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--ct-muted); }
    .ct-cols span:first-child { grid-column: 1 / 3; }
    .ct-cols span:nth-child(2), .ct-cols span:nth-child(3) { text-align: center; }
    .ct-cols span:nth-child(4) { text-align: right; grid-column: 5 / 7; padding-right: 52px; }

    .ct-item { padding: 18px 0; border-bottom: 1px solid #f3f4f6; animation: ctFade .35s ease both; }
    .ct-item:last-child { border-bottom: 0; }
    .ct-thumb { width: 84px; height: 84px; border-radius: 12px; overflow: hidden; background: #f9fafb; border: 1px solid var(--ct-border); display: block; }
    .ct-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s ease; }
    .ct-item:hover .ct-thumb img { transform: scale(1.06); }
    .ct-name { display: block; font-size: 15px; font-weight: 600; color: var(--ct-text) !important; line-height: 1.35; margin-bottom: 6px; }
    .ct-name:hover { color: var(--ct-primary) !important; }
    .ct-meta { display: flex; flex-wrap: wrap; gap: 6px; }
    .ct-chip { background: #f3f4f6; color: #4b5563; font-size: 12px; padding: 3px 9px; border-radius: 999px; }
    .ct-unit-mobile { display: none; font-size: 13px; color: var(--ct-muted); margin-top: 6px; }
    .ct-price { text-align: center; font-size: 14.5px; color: #374151; }
    .ct-total { text-align: right; font-size: 16px; font-weight: 700; }

    .ct-qty-wrap { display: flex; justify-content: center; }
    .ct-qty { display: inline-flex; align-items: center; border: 1px solid #e5e7eb; border-radius: 999px; padding: 3px; background: #fff; }
    .ct-qty button {
        width: 32px; height: 32px; border-radius: 50%; border: 0; background: #f3f4f6; color: #374151;
        display: inline-flex; align-items: center; justify-content: center; font-size: 11px; cursor: pointer; transition: background .15s, color .15s;
    }
    .ct-qty button:hover:not([disabled]) { background: var(--ct-primary); color: #fff; }
    .ct-qty button[disabled] { opacity: .4; cursor: not-allowed; }
    .ct-qty input { width: 42px; border: 0; text-align: center; font-weight: 700; font-size: 15px; background: transparent; -moz-appearance: textfield; }
    .ct-qty input::-webkit-outer-spin-button, .ct-qty input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .ct-qty input:focus { outline: none; }

    .ct-remove {
        width: 36px; height: 36px; border-radius: 50%; border: 0; background: transparent; color: #9ca3af;
        display: inline-flex; align-items: center; justify-content: center; font-size: 15px; cursor: pointer; transition: background .15s, color .15s;
    }
    .ct-remove:hover { background: #fef2f2; color: #dc2626; }

    /* summary */
    .ct-summary { padding: 22px; position: sticky; top: 100px; }
    .ct-summary h4 { font-size: 17px; font-weight: 700; margin: 0 0 16px; }
    .ct-row { display: flex; justify-content: space-between; gap: 12px; font-size: 14.5px; color: #374151; padding: 8px 0; }
    .ct-muted { color: var(--ct-muted); font-size: 13px; }
    .ct-grand { border-top: 1px dashed #e5e7eb; margin-top: 8px; padding-top: 16px; font-size: 18px; font-weight: 700; color: var(--ct-text); }
    .ct-checkout {
        position: relative; overflow: hidden;
        display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; height: 52px; margin-top: 18px;
        background: linear-gradient(120deg, #ff005e 0%, #ff3d84 100%); color: #fff !important;
        border-radius: 10px; font-size: 16px; font-weight: 700;
        box-shadow: 0 8px 20px rgba(255, 0, 94, .3); transition: transform .2s ease, box-shadow .2s ease;
    }
    .ct-checkout i { transition: transform .2s ease; }
    .ct-checkout:hover { transform: translateY(-2px); box-shadow: 0 12px 26px rgba(255, 0, 94, .4); }
    .ct-checkout:hover i { transform: translateX(4px); }
    .ct-continue { display: block; text-align: center; margin-top: 12px; font-size: 14px; font-weight: 600; color: #374151 !important; }
    .ct-continue:hover { color: var(--ct-primary) !important; }
    .ct-secure { display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 18px; padding-top: 16px; border-top: 1px solid #f3f4f6; font-size: 13px; color: var(--ct-muted); }
    .ct-secure i { color: #16a34a; }

    /* empty */
    .ct-empty { text-align: center; background: #fff; border: 1px solid var(--ct-border); border-radius: 16px; padding: 64px 20px; }
    .ct-empty-icon { width: 96px; height: 96px; border-radius: 50%; background: #fff0f5; color: var(--ct-primary); font-size: 40px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 18px; }
    .ct-empty h4 { font-weight: 700; margin-bottom: 8px; }
    .ct-empty p { color: var(--ct-muted); max-width: 380px; margin: 0 auto 22px; }

    .ct-mobile-bar { display: none; }
    .productList-table.ct-loading { opacity: .55; pointer-events: none; transition: opacity .2s; }

    @keyframes ctFade { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }

    @media (max-width: 991.98px) {
        .ct-summary { position: static; }
    }
    @media (max-width: 767.98px) {
        .ct-page { padding: 18px 0 100px; }
        .ct-top h1 { font-size: 21px; }
        .ct-steps { display: none; }
        .ct-card { padding: 4px 14px 6px; }
        .ct-cols { display: none; }
        .ct-item {
            grid-template-columns: 72px minmax(0, 1fr) 32px;
            grid-template-areas: "thumb info remove" "thumb qty total";
            gap: 10px 12px; padding: 14px 0;
        }
        .ct-thumb { grid-area: thumb; width: 72px; height: 72px; align-self: start; }
        .ct-info { grid-area: info; }
        .ct-remove { grid-area: remove; align-self: start; width: 32px; height: 32px; }
        .ct-price { display: none; }
        .ct-unit-mobile { display: block; }
        .ct-qty-wrap { grid-area: qty; justify-content: flex-start; }
        .ct-total { grid-area: total; grid-column: 2 / 4; font-size: 15px; }
        .ct-name { font-size: 14px; }
        .ct-mobile-bar {
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
            position: fixed; left: 0; right: 0; bottom: 0; z-index: 1035;
            background: #fff; padding: 12px 16px; box-shadow: 0 -6px 20px rgba(0,0,0,.1);
        }
        .ct-mobile-bar small { display: block; font-size: 12px; color: var(--ct-muted); }
        .ct-mobile-bar strong { font-size: 18px; }
        .ct-mobile-bar a {
            display: inline-flex; align-items: center; gap: 8px; padding: 12px 22px; border-radius: 10px;
            background: linear-gradient(120deg, #ff005e 0%, #ff3d84 100%); color: #fff !important; font-weight: 700;
        }
        /* keep the floating WhatsApp button above the checkout bar */
        .ct-has-bar ~ .whatsapp-fixed, body.ct-has-bar .whatsapp-fixed { bottom: 86px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .ct-item { animation: none; }
    }
</style>

@endpush

@section('contents')

<div class="ct-page">
    <div class="container">
        <div class="ct-top">
            <h1>Shopping Cart</h1>
            <ol class="ct-steps">
                <li class="active"><span>1</span> Cart</li>
                <li class="ct-step-line"></li>
                <li><span>2</span> Checkout</li>
                <li class="ct-step-line"></li>
                <li><span>3</span> Complete</li>
            </ol>
        </div>

        @if (session('info'))
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <strong>Oops! </strong> {{Session::get('info') }}.
        </div>
        @endif

        <div class="productList-table">
            @include(App\Models\General::first()->theme.'.carts.includes.cartItems')
        </div>
    </div>
</div>

@endsection

@push('js')

<script>
    $(document).ready(function(){

        // lift the WhatsApp button above the mobile checkout bar while it is shown
        function syncMobileBar() {
            $('body').toggleClass('ct-has-bar', $('.ct-mobile-bar').length > 0);
        }
        syncMobileBar();

        // dim the cart while an update request is running
        $(document).ajaxSend(function (e, xhr, settings) {
            if (settings.url && settings.url.indexOf('change-to-cart') !== -1) $('.productList-table').addClass('ct-loading');
        }).ajaxComplete(function (e, xhr, settings) {
            if (settings.url && settings.url.indexOf('change-to-cart') !== -1) {
                $('.productList-table').removeClass('ct-loading');
                syncMobileBar();
            }
        });

        $(document).on('change','.cartQtyChange',function(){

            var url = $(this).data('url');
            var qty = parseInt($(this).val(), 10);

            if (isNaN(qty) || qty < 1){
                qty = 1;
            }

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                cache: false,
                data: {'qty':qty},
            })
            .done(function(data) {
                $(".productList-table").empty().append(data.cartItems);
                $(".HeaderCartItems").empty().append(data.HeadercartItems);
                $(".HeaderCartItems2").empty().append(data.HeadercartItems2);
            });
        });
    });
</script>

@endpush
