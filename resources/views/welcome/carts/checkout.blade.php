@extends(general()->theme.'.layouts.app') @section('title')
<title>Checkout | {{general()->title}}</title>
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
.checkoutPage { --ck-primary: #ff005e; --ck-text: #111827; --ck-muted: #6b7280; --ck-border: #eceef1; padding: 28px 0 56px; color: var(--ck-text); }
.checkoutPage a { text-decoration: none; }

/* header + steps */
.ck-top { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 18px; }
.ck-top h1 { font-size: 26px; font-weight: 700; margin: 0; }
.ck-steps { display: flex; align-items: center; gap: 10px; list-style: none; margin: 0; padding: 0; font-size: 13px; font-weight: 600; color: #9ca3af; }
.ck-steps li { display: flex; align-items: center; gap: 8px; }
.ck-steps li span { width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; background: #f3f4f6; font-size: 12px; }
.ck-steps li.done { color: #16a34a; }
.ck-steps li.done span { background: #dcfce7; color: #16a34a; }
.ck-steps li.active { color: var(--ck-text); }
.ck-steps li.active span { background: var(--ck-primary); color: #fff; }
.ck-steps .ck-step-line { width: 28px; height: 2px; background: #e5e7eb; }

.ck-notice {
    display: flex; align-items: center; gap: 12px;
    background: #fff0f5; border: 1px solid #ffd1e2; color: #9d174d;
    border-radius: 12px; padding: 12px 16px; font-size: 15px; margin-bottom: 18px;
}
.ck-notice i { color: var(--ck-primary); font-size: 18px; }
.ck-notice b { color: var(--ck-primary); }

/* section cards */
.ck-card { background: #fff; border: 1px solid var(--ck-border); border-radius: 14px; box-shadow: 0 1px 3px rgba(16,24,40,.05); padding: 22px; margin-bottom: 18px; }
.ck-section-title { display: flex; align-items: center; gap: 12px; font-size: 17px; font-weight: 700; margin: 0 0 18px; padding: 0; border: 0; }
.ck-section-title .ck-num {
    width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
    display: inline-flex; align-items: center; justify-content: center;
    background: linear-gradient(120deg, #ff005e, #ff3d84); color: #fff; font-size: 14px;
}
.ck-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 16px; }
.ck-grid .ck-full { grid-column: 1 / -1; }
.ck-field { margin-bottom: 16px; }
.ck-label { display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px; }
.checkoutPage .form-control {
    height: auto; min-height: 46px; padding: 11px 14px; font-size: 15px;
    border: 1px solid #d1d5db; border-radius: 10px; color: var(--ck-text); background-color: #fff;
    box-shadow: none; transition: border-color .15s, box-shadow .15s;
}
.checkoutPage textarea.form-control { min-height: 84px; }
.checkoutPage .form-control:focus { border-color: var(--ck-primary); box-shadow: 0 0 0 3px rgba(255,0,94,.12); }
.checkoutPage .form-control:disabled { background-color: #f9fafb; color: #9ca3af; cursor: not-allowed; }
.ck-error { color: #dc2626; margin: 6px 0 0; font-size: 13px; }

/* payment options */
.ck-pay-option {
    position: relative; display: block; cursor: pointer;
    border: 2px solid #e5e7eb; border-radius: 12px; padding: 14px 16px 14px 50px; margin-bottom: 12px;
    transition: border-color .2s, background .2s, box-shadow .2s;
}
.ck-pay-option:hover { border-color: #ffb3cf; }
.ck-pay-option input { position: absolute; opacity: 0; pointer-events: none; }
.ck-pay-option:before {
    content: ''; position: absolute; left: 16px; top: 16px;
    width: 20px; height: 20px; border-radius: 50%; border: 2px solid #d1d5db; background: #fff; transition: all .2s;
}
.ck-pay-option.active { border-color: var(--ck-primary); background: #fff5f9; box-shadow: 0 6px 18px rgba(255,0,94,.12); }
.ck-pay-option.active:before { border-color: var(--ck-primary); background: var(--ck-primary); box-shadow: inset 0 0 0 4px #fff; }
.ck-pay-option b { font-size: 15.5px; }
.ck-pay-option small { display: block; color: var(--ck-muted); margin: 4px 0 0; font-size: 13px; line-height: 1.5; }
.ck-pay-icon { position: absolute; right: 16px; top: 14px; color: #9ca3af; font-size: 20px; }
.ck-pay-option.active .ck-pay-icon { color: var(--ck-primary); }
.ck-offer {
    display: inline-block; margin: 8px 0 0;
    background: #dcfce7; color: #15803d; border-radius: 999px; padding: 3px 10px; font-size: 12.5px; font-weight: 600;
}
.ck-qr-box { border: 1px dashed #ff8fb8; background: #fffafc; border-radius: 12px; padding: 18px; margin-top: 4px; }
.ck-qr-box img { max-width: 220px; width: 100%; display: block; margin: 0 auto 12px; border-radius: 10px; background: #fff; padding: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
.ck-qr-box p { font-size: 14px; }
.ck-pay-amount { text-align: center; font-size: 17px; margin-bottom: 12px; }
.ck-pay-amount b { color: var(--ck-primary); font-size: 22px; }
.ck-hint { font-size: 13px; color: #4b5563; line-height: 1.6; margin-top: 8px; }
.ck-coupon-msg { font-size: 13.5px; margin: 6px 0 0; }
.ck-hint a { word-break: break-word; }

/* facebook post type */
.ck-fb-types { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin: 10px 0 4px; }
.ck-fb-type {
    display: flex; align-items: center; gap: 8px; margin: 0; cursor: pointer;
    border: 2px solid #e5e7eb; border-radius: 10px; padding: 10px 12px; font-size: 14.5px; font-weight: 600;
    transition: border-color .2s, background .2s;
}
.ck-fb-type input { accent-color: var(--ck-primary); width: 16px; height: 16px; margin: 0; flex-shrink: 0; }
.ck-fb-type b { color: var(--ck-primary); margin-left: auto; }
.ck-fb-type.active { border-color: var(--ck-primary); background: #fff5f9; }

/* mobile sticky order bar */
.ck-mobile-bar { display: none; }

/* summary */
.checkoutInfo.ck-summary {
    background: #fff; border: 1px solid var(--ck-border); border-radius: 14px; box-shadow: 0 1px 3px rgba(16,24,40,.05);
    padding: 22px; position: sticky; top: 100px; margin: 0;
}
.ck-summary h4 { font-size: 18px; font-weight: 700; margin: 0 0 16px; }
.ck-items { max-height: 260px; overflow-y: auto; margin: 0 -4px 16px; padding: 0 4px; }
.ck-item { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f3f4f6; }
.ck-item:last-child { border-bottom: 0; }
.ck-item-img { position: relative; flex-shrink: 0; }
.ck-item-img img { width: 54px; height: 54px; object-fit: cover; border-radius: 10px; border: 1px solid var(--ck-border); }
.ck-item-qty {
    position: absolute; top: -7px; right: -7px; min-width: 20px; height: 20px; padding: 0 5px; border-radius: 999px;
    background: #374151; color: #fff; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center;
}
.ck-item-info { flex: 1; min-width: 0; }
.ck-item-name { display: block; font-size: 14px; font-weight: 600; color: var(--ck-text) !important; line-height: 1.35; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
.ck-item-meta { font-size: 12px; color: var(--ck-muted); }
.ck-item-price { font-size: 14px; font-weight: 700; white-space: nowrap; }

.ck-coupon { padding: 14px 0; border-top: 1px solid #f3f4f6; border-bottom: 1px solid #f3f4f6; margin-bottom: 8px; }
.ck-coupon .input-group .form-control { border-radius: 10px 0 0 10px; min-height: 44px; }
.ck-coupon .input-group .btn { white-space: nowrap; border-radius: 0 10px 10px 0; background: #111827; border-color: #111827; font-weight: 600; padding: 0 18px; }
.ck-coupon .input-group .btn:hover { background: #000; }
#couponAppliedBox .badge { background: #dcfce7; color: #15803d; padding: 7px 12px; border-radius: 999px; }

.ordertable { margin: 0; }
.ordertable td, .ordertable th { padding: 8px 0; font-size: 15px; font-weight: 500; color: #374151; border-top: 0; }
.ordertable .ck-total th, .ordertable .ck-total td { font-size: 19px; font-weight: 700; color: var(--ck-text); border-top: 1px dashed #e5e7eb; padding-top: 14px; }
.ordertable .ck-advance th, .ordertable .ck-advance td { color: var(--ck-primary); font-weight: 700; }
.ordertable .ck-due th, .ordertable .ck-due td { font-weight: 700; color: var(--ck-text); }

.checkoutPage .placeOrderbtn {
    position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: center; gap: 10px;
    width: 100%; height: 56px; margin-top: 16px; border: 0; border-radius: 12px;
    background: linear-gradient(120deg, #ff005e 0%, #ff3d84 100%); color: #fff;
    font-size: 18px; font-weight: 700; cursor: pointer;
    box-shadow: 0 10px 24px rgba(255,0,94,.32); transition: transform .2s ease, box-shadow .2s ease;
}
.checkoutPage .placeOrderbtn:after {
    content: ''; position: absolute; top: 0; left: -75%; width: 50%; height: 100%;
    background: linear-gradient(100deg, transparent, rgba(255,255,255,.35), transparent); transform: skewX(-20deg);
    animation: ckShine 3.5s ease-in-out infinite;
}
.checkoutPage .placeOrderbtn:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(255,0,94,.42); }
.checkoutPage .placeOrderbtn:disabled { opacity: .75; cursor: wait; transform: none; }
@keyframes ckShine { 0%, 55% { left: -75%; } 100% { left: 130%; } }

.ck-trust { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f3f4f6; }
.ck-trust div { text-align: center; font-size: 12px; color: var(--ck-muted); line-height: 1.35; }
.ck-trust i { display: block; font-size: 18px; color: #16a34a; margin-bottom: 6px; }

@media (max-width: 991.98px) {
    .checkoutInfo.ck-summary { position: static; margin-top: 4px; }
}
@media (max-width: 767.98px) {
    .checkoutPage { padding: 12px 0 96px; }
    .checkoutPage .container { padding-left: 10px; padding-right: 10px; }
    .ck-top { margin-bottom: 10px; }
    .ck-top h1 { font-size: 19px; }
    .ck-steps { display: none; }
    .ck-notice { font-size: 13px; padding: 9px 12px; gap: 9px; border-radius: 10px; margin-bottom: 12px; line-height: 1.5; }
    .ck-notice i { font-size: 15px; }
    .ck-card, .checkoutInfo.ck-summary { padding: 14px 12px; border-radius: 12px; margin-bottom: 12px; }
    .ck-section-title { font-size: 15px; gap: 9px; margin-bottom: 12px; }
    .ck-section-title .ck-num { width: 24px; height: 24px; font-size: 12px; }
    .ck-grid { grid-template-columns: 1fr; }
    .ck-field { margin-bottom: 11px; }
    .ck-label { font-size: 13px; margin-bottom: 4px; line-height: 1.5; }
    .checkoutPage .form-control { min-height: 42px; padding: 8px 12px; font-size: 14px; border-radius: 8px; }
    .checkoutPage textarea.form-control { min-height: 64px; }
    .ck-error { font-size: 12px; }

    .ck-pay-option { padding: 11px 40px 11px 42px; margin-bottom: 9px; border-radius: 10px; }
    .ck-pay-option:before { left: 13px; top: 13px; width: 18px; height: 18px; }
    .ck-pay-option b { font-size: 14px; }
    .ck-pay-option small { font-size: 12px; }
    .ck-pay-icon { right: 13px; top: 12px; font-size: 17px; }
    .ck-offer { font-size: 11.5px; padding: 2px 9px; }
    .ck-qr-box { padding: 12px; }
    .ck-qr-box img { max-width: 180px; }
    .ck-qr-box p { font-size: 13px !important; line-height: 1.5 !important; }
    .ck-pay-amount { font-size: 14px; }
    .ck-pay-amount b { font-size: 18px; }
    .ck-hint { font-size: 12px; line-height: 1.55; }
    .ck-coupon-msg { font-size: 12.5px; }

    .ck-fb-types { gap: 8px; }
    .ck-fb-type { font-size: 13px; padding: 8px 10px; gap: 6px; }

    .ck-summary h4 { font-size: 16px; margin-bottom: 10px; }
    .ck-items { max-height: 210px; margin-bottom: 10px; }
    .ck-item { gap: 10px; padding: 8px 0; }
    .ck-item-img img { width: 46px; height: 46px; border-radius: 8px; }
    .ck-item-name { font-size: 13px; }
    .ck-item-meta { font-size: 11.5px; }
    .ck-item-price { font-size: 13px; }
    .ck-coupon { padding: 10px 0; }
    .ck-coupon .input-group .form-control { min-height: 40px; }
    .ck-coupon .input-group .btn { padding: 0 12px; font-size: 13px; }
    .ordertable td, .ordertable th { padding: 6px 0; font-size: 13.5px; }
    .ordertable .ck-total th, .ordertable .ck-total td { font-size: 16px; padding-top: 10px; }
    .checkoutPage .placeOrderbtn { height: 48px; font-size: 16px; border-radius: 10px; margin-top: 10px; }
    .ck-trust { margin-top: 12px; padding-top: 12px; }
    .ck-trust div { font-size: 11px; }
    .ck-trust i { font-size: 15px; margin-bottom: 4px; }

    /* total + confirm button always in reach; hidden while the summary button is on screen */
    .ck-mobile-bar {
        display: flex; align-items: center; gap: 12px;
        position: fixed; left: 0; right: 0; bottom: 0; z-index: 999;
        background: #fff; border-top: 1px solid var(--ck-border); box-shadow: 0 -4px 16px rgba(16,24,40,.08);
        padding: 8px 12px calc(8px + env(safe-area-inset-bottom));
        transition: transform .2s ease;
    }
    .ck-mobile-bar.ck-hide { transform: translateY(110%); }
    .ck-mobile-bar-total { flex-shrink: 0; line-height: 1.2; }
    .ck-mobile-bar-total small { display: block; font-size: 11px; color: var(--ck-muted); }
    .ck-mobile-bar-total b { font-size: 17px; color: var(--ck-text); }
    .checkoutPage .ck-mobile-bar .placeOrderbtn { flex: 1; height: 44px; margin: 0; font-size: 15px; box-shadow: none; }
    .whatsapp-fixed { bottom: 76px; transition: bottom .2s ease; }
    body.ck-bar-hidden .whatsapp-fixed { bottom: 16px; }
}
@media (max-width: 359.98px) {
    .ck-fb-types { grid-template-columns: 1fr; }
}
@media (prefers-reduced-motion: reduce) {
    .checkoutPage .placeOrderbtn:after { animation: none; }
}
</style>
@endpush

@section('contents')

    <script>
        window.dataLayer = window.dataLayer || [];
        dataLayer.push({
            event: "begin_checkout",
            ecommerce: {
                currency: "{{ general()->currency }}",
                value: {{ round($totals['subtotal']) }},
                discount: {{ round($totals['couponDisc']) }},
                items: [
                    @foreach($carts as $item)
                    {
                        @if($product =$item->product)
                        item_id: "{{ $item->id }}",
                        item_name: "{{ $product->name }}",
                        item_category: "{!! implode(' - ', $product->productCategories->pluck('name')->toArray()) !!}",
                        item_brand: "{{ $product->brand ? $product->brand->name : '' }}",
                        price: "{{ $product->offerPrice() }}",
                        quantity: "{{ $item->quantity }}",
                        @endif
                    }@if(!$loop->last),@endif
                    @endforeach
                ]
            }
        });
    </script>

<div class="checkoutPage">
    <div class="container">
        <div class="ck-top">
            <h1>Checkout</h1>
            <ol class="ck-steps">
                <li class="done"><span><i class="fa fa-check"></i></span> Cart</li>
                <li class="ck-step-line"></li>
                <li class="active"><span>2</span> Checkout</li>
                <li class="ck-step-line"></li>
                <li><span>3</span> Complete</li>
            </ol>
        </div>
        <div>
            @if (session('error'))
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <strong>Oops! </strong> {{Session::get('error') }}.
            </div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">
                অনুগ্রহ করে লাল চিহ্নিত ঘরগুলো ঠিক করে আবার চেষ্টা করুন।
            </div>
            @endif
        </div>
        <form action="{{route('checkoutPost')}}" method="post" enctype="multipart/form-data" id="checkoutForm">
            @csrf
        <div class="row">
            <div class="col-lg-7">
                <div class="ck-form">
                    <div class="ck-notice">
                        <i class="fa fa-circle-info"></i>
                        <span>নিচের তথ্যগুলো সঠিকভাবে পূরণ করে <b>"অর্ডার কনফার্ম করুন"</b> বাটনে ক্লিক করুন।</span>
                    </div>

                    <div class="ck-card">
                    <div class="ck-section-title"><span class="ck-num">১</span> আপনার তথ্য</div>
                    <div class="ck-grid">

                        <div class="ck-field">
                            <label class="ck-label" for="name">আপনার নাম: <span class="text-danger">*</span></label>
                        <input type="text" id="name" value="{{old('name', $user ? $user->name : '')}}" name="name" class="form-control" placeholder="আপনার নাম লিখুন" required>
                        @if ($errors->has('name'))
                        <p class="ck-error">{{ $errors->first('name') }}</p>
                        @endif
                        @if(Session::has('nameError'))
                        <p class="ck-error">{{Session::get('nameError') }}</p>
                        @endif
                        </div>

                        <div class="ck-field">
                            <label class="ck-label" for="mobile">মোবাইল নম্বর: <span class="text-danger">*</span></label>
                        <input type="tel" id="mobile" name="mobile" class="form-control" value="{{old('mobile', $user ? $user->mobile : '')}}" placeholder="01XXXXXXXXX" required>
                        @if ($errors->has('mobile'))
                        <p class="ck-error">{{ $errors->first('mobile') }}</p>
                        @endif
                        </div>

                        <div class="ck-field ck-full">
                            <label class="ck-label" for="email">ইমেইল (অপশনাল):</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{old('email', $user ? $user->email : '')}}" placeholder="অর্ডার কনফার্মেশন ইমেইলে পেতে লিখুন">
                        @if ($errors->has('email'))
                        <p class="ck-error">{{ $errors->first('email') }}</p>
                        @endif
                        </div>

                    </div>
                    </div>

                    <div class="ck-card">
                    <div class="ck-section-title"><span class="ck-num">২</span> ডেলিভারি ঠিকানা</div>
                    <div class="ck-grid">

                        <div class="ck-field">
                            <label class="ck-label" for="district_id">জেলা: <span class="text-danger">*</span></label>
                        <select name="district_id" id="district_id" class="form-control" required>
                            <option value="">-- জেলা সিলেক্ট করুন --</option>
                            @foreach($districts as $district)
                            <option value="{{$district['id']}}" {{old('district_id')==$district['id']?'selected':''}}>{{$district['name']}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('district_id'))
                        <p class="ck-error">{{ $errors->first('district_id') }}</p>
                        @endif
                        </div>

                        <div class="ck-field">
                            <label class="ck-label" for="upazila_id">উপজেলা / থানা: <span class="text-danger">*</span></label>
                        <select name="upazila_id" id="upazila_id" class="form-control" required disabled>
                            <option value="">-- আগে জেলা সিলেক্ট করুন --</option>
                        </select>
                        @if ($errors->has('upazila_id'))
                        <p class="ck-error">{{ $errors->first('upazila_id') }}</p>
                        @endif
                        </div>

                        <div class="ck-field ck-full">
                            <label class="ck-label" for="address">আপনার ঠিকানা: <span class="text-danger">*</span></label>
                        <textarea id="address" name="address" class="form-control" rows="2" placeholder="বাসা নং, রোড নং, এলাকার নাম লিখুন" required>{{old('address', $user ? $user->address_line1 : '')}}</textarea>
                        @if ($errors->has('address'))
                        <p class="ck-error">{{ $errors->first('address') }}</p>
                        @endif
                        </div>

                        <div class="ck-field ck-full">
                            <label class="ck-label" for="note">নোট (অপশনাল):</label>
                        <textarea id="note" name="note" class="form-control" rows="2" placeholder="আপনি চাইলে কোন নোট লিখতে পারেন।">{{old('note')}}</textarea>
                        </div>

                    </div>
                    </div>

                    <div class="ck-card">
                    <div class="ck-section-title"><span class="ck-num">৩</span> পেমেন্ট মেথড</div>

                    <label class="ck-pay-option" data-method="cod">
                        <input type="radio" name="payment_method" value="cod" {{old('payment_method','cod')=='cod'?'checked':''}}>
                        <i class="fa fa-truck ck-pay-icon"></i>
                        <b>ক্যাশ অন ডেলিভারি</b>
                        <small>ডেলিভারি চার্জের {{en2bnNumber($general->cod_advance_percent)}}% অগ্রিম দিয়ে অর্ডার কনফার্ম করুন, বাকি টাকা পণ্য হাতে পেয়ে দিন।</small>
                    </label>

                    <label class="ck-pay-option" data-method="full_advance">
                        <input type="radio" name="payment_method" value="full_advance" {{old('payment_method')=='full_advance'?'checked':''}}>
                        <i class="fa fa-qrcode ck-pay-icon"></i>
                        <b>অনলাইন পেমেন্ট (Bangla QR)</b>
                        <small>সম্পূর্ণ টাকা এখনই Bangla QR-এ পেমেন্ট করুন।</small>
                        @if($general->full_advance_free_delivery)
                        <span class="ck-offer">ফুল অগ্রিমে ফ্রি ডেলিভারি!</span>
                        @endif
                    </label>
                    @if ($errors->has('payment_method'))
                    <p class="ck-error">{{ $errors->first('payment_method') }}</p>
                    @endif

                    <div class="ck-qr-box" id="qrBox">
                        <div class="ck-pay-amount" id="payAmountText"></div>

                        @if($general->bangla_qr_image)
                        <img src="{{asset($general->bangla_qr_image)}}" alt="Bangla QR">
                        @if($general->bangla_qr_number)
                        <p class="text-center mb-1" style="font-size: 15px;">QR / মার্চেন্ট নম্বর: <b>{{$general->bangla_qr_number}}</b></p>
                        @endif
                        <p class="text-center" style="font-size: 14px;line-height: 20px;">যেকোনো ব্যাংক / মোবাইল ব্যাংকিং অ্যাপ দিয়ে উপরের Bangla QR স্ক্যান করে পেমেন্ট করুন।</p>
                        @elseif($general->bangla_qr_number)
                        <p class="text-center" style="font-size: 15px;line-height: 22px;">এই নম্বরে পেমেন্ট করুন: <b>{{$general->bangla_qr_number}}</b></p>
                        @else
                        <p class="text-center text-danger" style="font-size: 14px;line-height: 20px;">QR কোড পাওয়া যায়নি। পেমেন্টের জন্য আমাদের সাথে যোগাযোগ করুন{{general()->mobile ? ': '.general()->mobile : '।'}}</p>
                        @endif

                        <div class="row mt-3" id="paymentProof">
                            <div class="col-md-12 mb-2">
                                <label class="ck-label" for="trx_id">TrxID: <span class="text-danger">*</span></label>
                                <input type="text" id="trx_id" name="trx_id" class="form-control" value="{{old('trx_id')}}" placeholder="যেমন: 9AB3CD7EF2">
                                @if ($errors->has('trx_id'))
                                <p class="ck-error">{{ $errors->first('trx_id') }}</p>
                                @endif
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="ck-label" for="payment_screenshot">পেমেন্টের স্ক্রিনশট (অপশনাল):</label>
                                <input type="file" id="payment_screenshot" name="payment_screenshot" class="form-control" accept="image/*" style="height: auto;">
                                @if ($errors->has('payment_screenshot'))
                                <p class="ck-error">{{ $errors->first('payment_screenshot') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    </div>

                    @if($general->fb_discount_status)
                    @php($fbDiscount = fbDiscountFor($totals['subtotal'], $general, 'post'))
                    @php($fbReelsDiscount = fbDiscountFor($totals['subtotal'], $general, 'reels'))
                    @php($fbOldType = old('fb_post_type') == 'reels' ? 'reels' : 'post')
                    <div id="fbDiscountBox" class="ck-card">
                        <div class="ck-section-title"><span class="ck-num">৪</span> Facebook পোস্টে স্পেশাল ডিসকাউন্ট (অপশনাল)</div>
                        <label class="ck-label" for="fb_post_link">{!! nl2br(e($general->fb_discount_text ?: 'Facebook পোস্টের লিংক দিন, স্পেশাল ডিসকাউন্ট পান')) !!}</label>
                        <input type="url" id="fb_post_link" name="fb_post_link" class="form-control" value="{{old('fb_post_link')}}" placeholder="https://www.facebook.com/...">
                        <p class="ck-coupon-msg" id="fbPostMsg"></p>
                        @if ($errors->has('fb_post_link'))
                        <p class="ck-error">{{ $errors->first('fb_post_link') }}</p>
                        @endif
                        <div class="ck-fb-types">
                            <label class="ck-fb-type">
                                <input type="radio" name="fb_post_type" value="post" {{$fbOldType=='post'?'checked':''}}> ফটো পোস্ট
                                @if($fbDiscount > 0)<b style="color: #ff005e;">৳{{en2bnNumber(number_format($fbDiscount))}}</b>@endif
                            </label>
                            <label class="ck-fb-type">
                                <input type="radio" name="fb_post_type" value="reels" {{$fbOldType=='reels'?'checked':''}}> রিলস
                                @if($fbReelsDiscount > 0)<b style="color: #ff005e;">৳{{en2bnNumber(number_format($fbReelsDiscount))}}</b>@endif
                            </label>
                        </div>
                        <div class="ck-hint">
                            @if($fbDiscount > 0 || $fbReelsDiscount > 0)
                            সঠিক লিংক দিলেই এই অর্ডারে ফটো পোস্টে <b style="color: #ff005e;">৳{{en2bnNumber(number_format($fbDiscount))}}</b> আর রিলসে <b style="color: #ff005e;">৳{{en2bnNumber(number_format($fbReelsDiscount))}}</b> ছাড় সাথে সাথে যোগ হবে (যাচাইয়ে বাতিল হলে ছাড়টি বাদ যাবে)।<br>
                            @endif
                            শর্ত: আগে আমাদের কাছ থেকে কেনা ও ডেলিভারি পাওয়া পণ্য নিয়ে Facebook পোস্ট,
                            পোস্টে {!! fbPageHtml($general) !!} পেজ ট্যাগ বা কোলাবরেশন থাকতে হবে,
                            আর পোস্টটি কমপক্ষে {{en2bnNumber($general->fb_post_min_days)}} দিন পুরনো হতে হবে। একটি পোস্ট একবারই ব্যবহার করা যাবে।
                        </div>
                    </div>
                    @if($general->fb_full_advance_rule=='not_allowed')
                    <p id="fbNotAllowedMsg" class="ck-hint" style="display: none;">Facebook পোস্ট ডিসকাউন্ট ফুল অগ্রিম পেমেন্টে প্রযোজ্য নয়।</p>
                    @endif
                    @endif

                </div>
            </div>

            <div class="col-lg-5">
                <div class="checkoutInfo ck-summary">
                    <h4>অর্ডার সামারি</h4>

                    <div class="ck-items">
                        @foreach($carts as $cart)
                        @if($cart->product)
                        <div class="ck-item">
                            <div class="ck-item-img">
                                <img src="{{asset($cart->product->image())}}" alt="{{$cart->product->name}}">
                                <span class="ck-item-qty">{{$cart->quantity}}</span>
                            </div>
                            <div class="ck-item-info">
                                <a href="{{route('productView',$cart->product->slug?:Str::slug($cart->product->name))}}" class="ck-item-name">{{$cart->product->name}}</a>
                                <div class="ck-item-meta">
                                    {{priceFullFormat($cart->itemprice())}} &times; {{$cart->quantity}}
                                    @if($cart->color) &middot; {{$cart->color}}@endif
                                    @if($cart->size) &middot; {{$cart->size}}@endif
                                </div>
                            </div>
                            <div class="ck-item-price">{{priceFullFormat($cart->quantity * $cart->itemprice())}}</div>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <div class="ck-coupon">
                        <label class="ck-label" for="coupon_code">কুপন কোড:</label>
                        <div class="input-group" id="couponApplyBox" style="{{$totals['coupon']?'display:none;':''}}">
                            <input type="text" id="coupon_code" class="form-control" placeholder="কুপন কোড থাকলে লিখুন">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-dark" id="couponApplyBtn">প্রয়োগ করুন</button>
                            </div>
                        </div>
                        <div id="couponAppliedBox" style="{{$totals['coupon']?'':'display:none;'}}">
                            <span class="badge badge-success" style="font-size: 14px;" id="couponAppliedCode">{{$totals['coupon']?$totals['coupon']->code:''}}</span>
                            <a href="javascript:void(0)" id="couponRemoveBtn" class="text-danger ml-2">বাতিল করুন</a>
                        </div>
                        <p class="ck-coupon-msg" id="couponMsg"></p>
                    </div>

                    <table class="ordertable table">
                      <tbody>
                        <tr>
                          <th>সাবটোটাল</th>
                          <td style="text-align: end;" id="sumSubtotal"></td>
                        </tr>
                        <tr id="rowDiscount">
                          <th>কুপন ছাড়</th>
                          <td style="text-align: end;" id="sumDiscount"></td>
                        </tr>
                        <tr id="rowFbDiscount" style="display: none;">
                          <th>Facebook পোস্ট ছাড়</th>
                          <td style="text-align: end;" id="sumFbDiscount"></td>
                        </tr>
                        <tr>
                          <th>ডেলিভারি চার্জ</th>
                          <td style="text-align: end;" id="sumDelivery"></td>
                        </tr>
                        <tr id="rowTax">
                          <th>ট্যাক্স</th>
                          <td style="text-align: end;" id="sumTax"></td>
                        </tr>
                        <tr class="ck-total">
                          <th>সর্বমোট</th>
                          <td style="text-align: end;" id="sumTotal"></td>
                        </tr>
                        <tr class="ck-advance">
                          <th id="sumAdvanceLabel">এখন অগ্রিম দিতে হবে</th>
                          <td style="text-align: end;" id="sumAdvance"></td>
                        </tr>
                        <tr class="ck-due">
                          <th>ডেলিভারির সময় দিতে হবে</th>
                          <td style="text-align: end;" id="sumDue"></td>
                        </tr>
                      </tbody>
                    </table>

                    <button type="submit" class="placeOrderbtn" id="placeOrderBtn">অর্ডার কনফার্ম করুন</button>

                    <div class="ck-trust">
                        <div><i class="fa fa-lock"></i>নিরাপদ চেকআউট</div>
                        <div><i class="fa fa-truck"></i>ক্যাশ অন ডেলিভারি</div>
                        <div><i class="fa fa-headset"></i>কাস্টমার সাপোর্ট</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ck-mobile-bar" id="ckMobileBar">
            <div class="ck-mobile-bar-total">
                <small>সর্বমোট</small>
                <b id="mbTotal"></b>
            </div>
            <button type="submit" class="placeOrderbtn" id="mbPlaceOrderBtn">অর্ডার কনফার্ম করুন</button>
        </div>
        </form>
    </div>
</div>


@endsection

@push('js')

<script>
    $(document).ready(function(){

        // Mirrors CartController::checkoutTotals(); the server recalculates everything on submit
        var ck = {
            subtotal: {{ (float)$totals['subtotal'] }},
            tax: {{ (float)$totals['tax'] }},
            discount: {{ (float)$totals['couponDisc'] }},
            codPercent: {{ (int)$general->cod_advance_percent }},
            fullAdvanceFree: {{ $general->full_advance_free_delivery ? 'true' : 'false' }},
            upazilaCharges: {},
            fbDiscountAmounts: {
                post: {{ $general->fb_discount_status ? (float)fbDiscountFor($totals['subtotal'], $general, 'post') : 0 }},
                reels: {{ $general->fb_discount_status ? (float)fbDiscountFor($totals['subtotal'], $general, 'reels') : 0 }}
            },
            fbLinkValid: false,
            fbNotWithFullAdvance: {{ $general->fb_discount_status && $general->fb_full_advance_rule=='not_allowed' ? 'true' : 'false' }},
            fbCheckUrl: "{{ route('fbPostCheck') }}",
            locationsUrl: "{{ route('checkoutLocations') }}",
            couponApplyUrl: "{{ route('couponApply') }}",
            couponRemoveUrl: "{{ route('couponRemove') }}",
            csrf: "{{ csrf_token() }}",
            oldUpazila: "{{ old('upazila_id') }}"
        };

        function bn(n){
            var digits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            return Math.round(n).toLocaleString('en-US').replace(/[0-9]/g, function(d){ return digits[d]; });
        }

        function money(n){
            return '৳' + bn(n);
        }

        function calculate(){
            var upazilaId = $('#upazila_id').val();
            var method = $('input[name="payment_method"]:checked').val();
            var hasZone = !!upazilaId && ck.upazilaCharges[upazilaId] !== undefined;
            var delivery = hasZone ? ck.upazilaCharges[upazilaId] : 0;
            var baseDelivery = delivery;
            var freeDelivery = false;
            if(method === 'full_advance' && ck.fullAdvanceFree){
                freeDelivery = delivery > 0;
                delivery = 0;
            }

            // Mirrors checkoutTotals(): only for a valid, unused link and not with full advance when that is disallowed
            var fbDiscount = 0;
            if(ck.fbLinkValid && !(ck.fbNotWithFullAdvance && method === 'full_advance')){
                var fbType = $('input[name="fb_post_type"]:checked').val() === 'reels' ? 'reels' : 'post';
                fbDiscount = Math.min(ck.fbDiscountAmounts[fbType], Math.max(ck.subtotal - ck.discount, 0));
            }

            var total = ck.subtotal - ck.discount - fbDiscount + ck.tax + delivery;
            var advance = 0;
            if(method === 'full_advance'){
                advance = total;
            }else if(method === 'cod'){
                advance = Math.ceil(delivery * ck.codPercent / 100);
            }

            return {hasZone: hasZone, method: method, delivery: delivery, baseDelivery: baseDelivery, freeDelivery: freeDelivery, fbDiscount: fbDiscount, total: total, advance: advance, due: total - advance};
        }

        function render(){
            var t = calculate();

            $('#sumSubtotal').text(money(ck.subtotal));
            $('#rowDiscount').toggle(ck.discount > 0);
            $('#sumDiscount').text('- ' + money(ck.discount));
            $('#rowFbDiscount').toggle(t.fbDiscount > 0);
            $('#sumFbDiscount').text('- ' + money(t.fbDiscount));
            $('#rowTax').toggle(ck.tax > 0);
            $('#sumTax').text(money(ck.tax));

            if(!t.hasZone){
                $('#sumDelivery').html('<small class="text-muted">থানা সিলেক্ট করুন</small>');
            }else if(t.freeDelivery){
                $('#sumDelivery').html('<del class="text-muted">' + money(t.baseDelivery) + '</del> <b class="text-success">৳০ (ফ্রি)</b>');
            }else{
                $('#sumDelivery').text(money(t.delivery));
            }

            $('#sumTotal').text(money(t.total));
            $('#mbTotal').text(money(t.total));
            $('#sumAdvanceLabel').text(t.method === 'full_advance' ? 'এখন পেমেন্ট করতে হবে' : 'এখন অগ্রিম দিতে হবে');
            $('#sumAdvance').text(t.hasZone || t.method === 'full_advance' ? money(t.advance) : '—');
            $('#sumDue').text(t.hasZone || t.method === 'full_advance' ? money(t.due) : '—');

            $('.ck-pay-option').removeClass('active');
            $('.ck-pay-option[data-method="' + t.method + '"]').addClass('active');
            $('.ck-fb-type').each(function(){
                $(this).toggleClass('active', $(this).find('input').is(':checked'));
            });

            var text = '';
            if(t.method === 'full_advance'){
                text = 'সম্পূর্ণ <b>' + money(t.advance) + '</b> Bangla QR-এ পেমেন্ট করুন';
                if(!t.hasZone){
                    text += '<br><small class="text-muted">ডেলিভারি চার্জসহ সঠিক হিসাব দেখতে জেলা ও থানা সিলেক্ট করুন</small>';
                }
            }else if(!t.hasZone){
                text = '<small>অগ্রিমের পরিমাণ দেখতে আগে ঠিকানার জেলা ও থানা সিলেক্ট করুন</small>';
            }else if(t.advance > 0){
                text = 'ডেলিভারি চার্জের ' + bn(ck.codPercent) + '% অগ্রিম <b>' + money(t.advance) + '</b> Bangla QR-এ পেমেন্ট করুন'
                    + '<br><small>ডেলিভারির সময় দিতে হবে: ' + money(t.due) + '</small>';
            }else{
                text = 'এই অর্ডারে কোনো অগ্রিম লাগবে না।';
            }
            $('#payAmountText').html(text);

            if(ck.fbNotWithFullAdvance){
                $('#fbDiscountBox').toggle(t.method !== 'full_advance');
                $('#fbNotAllowedMsg').toggle(t.method === 'full_advance');
            }

            // TrxID is only required when there is something to pay now
            var needsProof = t.advance > 0 || (t.method === 'full_advance');
            $('#qrBox').toggle(t.method === 'full_advance' || !t.hasZone || t.advance > 0);
            $('#paymentProof').toggle(needsProof);
            $('#trx_id').prop('required', needsProof && t.hasZone);
        }

        function fillSelect($select, items, placeholder, selected){
            $select.empty().append($('<option>', {value: '', text: placeholder}));
            $.each(items, function(i, item){
                $select.append($('<option>', {value: item.id, text: item.name, selected: String(item.id) === String(selected)}));
            });
            $select.prop('disabled', items.length === 0);
        }

        function loadUpazilas(selected){
            var district = $('#district_id').val();
            ck.upazilaCharges = {};
            fillSelect($('#upazila_id'), [], district ? 'লোড হচ্ছে...' : '-- আগে জেলা সিলেক্ট করুন --');
            if(!district){ return; }

            $.get(ck.locationsUrl, {district: district}, function(res){
                // A district can span zones (e.g. Dhaka City / outside), so the charge is kept per thana
                $.each(res.items, function(i, item){
                    ck.upazilaCharges[item.id] = item.charge;
                });
                fillSelect($('#upazila_id'), res.items, '-- উপজেলা / থানা সিলেক্ট করুন --', selected);
                render();
            });
        }

        $('#district_id').change(function(){
            loadUpazilas();
            render();
        });

        $('#upazila_id').change(render);

        $('input[name="payment_method"]').change(render);

        function couponMessage(text, ok){
            $('#couponMsg').text(text).css('color', ok ? '#28a745' : 'red');
        }

        $('#couponApplyBtn').click(function(){
            var code = $.trim($('#coupon_code').val());
            if(!code){
                couponMessage('কুপন কোড লিখুন।', false);
                return;
            }
            var $btn = $(this).prop('disabled', true);
            $.post(ck.couponApplyUrl, {_token: ck.csrf, coupon_code: code}, function(res){
                couponMessage(res.message, res.success);
                if(res.success){
                    ck.discount = parseFloat(res.discount) || 0;
                    $('#couponAppliedCode').text(res.code);
                    $('#couponApplyBox').hide();
                    $('#couponAppliedBox').show();
                    render();
                }
            }).fail(function(){
                couponMessage('কিছু একটা সমস্যা হয়েছে, আবার চেষ্টা করুন।', false);
            }).always(function(){
                $btn.prop('disabled', false);
            });
        });

        $('#coupon_code').keypress(function(e){
            if(e.which === 13){
                e.preventDefault();
                $('#couponApplyBtn').click();
            }
        });

        $('#couponRemoveBtn').click(function(){
            $.post(ck.couponRemoveUrl, {_token: ck.csrf}, function(){
                ck.discount = 0;
                $('#coupon_code').val('');
                $('#couponAppliedBox').hide();
                $('#couponApplyBox').show();
                couponMessage('', true);
                render();
            });
        });

        // Checks the link as it is typed/pasted; a valid, unused link takes the discount off the summary right away
        var fbTimer = null;
        function checkFbLink(){
            var link = $.trim($('#fb_post_link').val());
            ck.fbLinkValid = false;
            render();
            if(!link){
                $('#fbPostMsg').text('');
                return;
            }
            $.get(ck.fbCheckUrl, {link: link}, function(res){
                if(link !== $.trim($('#fb_post_link').val())){ return; }
                $('#fbPostMsg').text(res.message).css('color', res.success ? '#28a745' : 'red');
                ck.fbLinkValid = !!res.success;
                // Only switch to Reels when the link is clearly a reel; other links keep the customer's choice
                if(res.type === 'reels'){
                    $('input[name="fb_post_type"][value="reels"]').prop('checked', true);
                }
                render();
            });
        }
        $('input[name="fb_post_type"]').on('change', render);
        $('#fb_post_link').on('input change', function(){
            clearTimeout(fbTimer);
            fbTimer = setTimeout(checkFbLink, 400);
        });
        if($.trim($('#fb_post_link').val())){
            checkFbLink();
        }

        $('#checkoutForm').submit(function(){
            $('#placeOrderBtn, #mbPlaceOrderBtn').prop('disabled', true).text('অর্ডার প্রসেস হচ্ছে...');
        });

        // The bottom bar would duplicate the summary button, so hide it while that one is visible
        if('IntersectionObserver' in window){
            new IntersectionObserver(function(entries){
                $('#ckMobileBar').toggleClass('ck-hide', entries[0].isIntersecting);
                $('body').toggleClass('ck-bar-hidden', entries[0].isIntersecting);
            }).observe(document.getElementById('placeOrderBtn'));
        }

        if($('#district_id').val()){
            loadUpazilas(ck.oldUpazila);
        }
        render();

    });
</script>

@endpush
