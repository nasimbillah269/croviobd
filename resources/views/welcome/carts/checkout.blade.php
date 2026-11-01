@extends(general()->theme.'.layouts.app') @section('title')
<title>{{general()->title}} | {{general()->subtitle}}</title>
@endsection @section('SEO')
<meta name="description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta property="og:title" content="{{general()->meta_title}}" />
<meta property="og:description" content="{!!general()->meta_description!!}" />
<meta property="og:image" content="{!!general()->meta_description!!}" />
<meta property="og:url" content="{{route('index')}}" />
@endsection
@push('css')
<style>
.overViewTable tr th {
    background: #eeeeee;
}
.overViewTable tr th, .overViewTable tr td{
    padding: 5px 8px;
}
.ordertable td, .ordertable th{
    padding: 6px 12px;
    font-size: 16px;
    font-weight: 400;
    border-top: 0;
}
.ordertable .ck-total th, .ordertable .ck-total td{
    font-size: 18px;
    font-weight: 700;
    border-top: 1px solid #ddd;
}
.ordertable .ck-advance th, .ordertable .ck-advance td{
    color: #ff005e;
    font-weight: 700;
}
.ordertable .ck-due th, .ordertable .ck-due td{
    font-weight: 700;
}
.ck-section-title{
    font-size: 17px;
    font-weight: 700;
    margin: 20px 0 12px;
    padding-bottom: 6px;
    border-bottom: 1px solid #eee;
}
.ck-label{
    font-weight: bold;
}
.ck-error{
    color: red;
    margin: 0;
    font-size: 14px;
}
.ck-pay-option{
    display: block;
    border: 2px solid #ddd;
    border-radius: 8px;
    padding: 12px 14px;
    margin-bottom: 10px;
    cursor: pointer;
}
.ck-pay-option.active{
    border-color: #ff005e;
    background: #fff5f9;
}
.ck-pay-option input{
    margin-right: 8px;
}
.ck-pay-option small{
    display: block;
    color: #666;
    margin-left: 22px;
}
.ck-qr-box{
    border: 1px dashed #ff005e;
    border-radius: 8px;
    padding: 15px;
    margin-top: 5px;
}
.ck-qr-box img{
    max-width: 220px;
    width: 100%;
    display: block;
    margin: 0 auto 10px;
}
.ck-pay-amount{
    text-align: center;
    font-size: 18px;
    margin-bottom: 12px;
}
.ck-pay-amount b{
    color: #ff005e;
    font-size: 22px;
}
.ck-offer{
    display: inline-block;
    background: #28a745;
    color: #fff;
    border-radius: 4px;
    padding: 1px 8px;
    font-size: 13px;
    margin-left: 22px;
}
.ck-coupon .input-group .btn{
    white-space: nowrap;
}
.ck-coupon-msg{
    font-size: 14px;
    margin: 5px 0 0;
}
.checkoutForm select.form-control{
    height: auto;
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
            <div class="col-md-7">
                <div class="checkoutForm">
                    <h5 style="text-align: center;color: #ff005e;">
                        নিচের তথ্যগুলো সঠিকভাবে পূরণ করে
                        <b>"অর্ডার কনফার্ম করুন"</b>
                        বাটনে ক্লিক করুন।
                    </h5>

                    <div class="ck-section-title">১. আপনার তথ্য</div>

                    <div class="row mb-3">
                        <label class="col-md-3 ck-label" for="name">
                            আপনার নাম: <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="text" id="name" value="{{old('name', $user ? $user->name : '')}}" name="name" class="form-control" placeholder="আপনার নাম লিখুন" required>
                            @if ($errors->has('name'))
                            <p class="ck-error">{{ $errors->first('name') }}</p>
                            @endif
                            @if(Session::has('nameError'))
                            <p class="ck-error">{{Session::get('nameError') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 ck-label" for="mobile">
                            মোবাইল নম্বর: <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="tel" id="mobile" name="mobile" class="form-control" value="{{old('mobile', $user ? $user->mobile : '')}}" placeholder="01XXXXXXXXX" required>
                            @if ($errors->has('mobile'))
                            <p class="ck-error">{{ $errors->first('mobile') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 ck-label" for="email">
                            ইমেইল (অপশনাল):
                        </label>
                        <div class="col-md-9">
                            <input type="email" id="email" name="email" class="form-control" value="{{old('email', $user ? $user->email : '')}}" placeholder="অর্ডার কনফার্মেশন ইমেইলে পেতে লিখুন">
                            @if ($errors->has('email'))
                            <p class="ck-error">{{ $errors->first('email') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="ck-section-title">২. ডেলিভারি ঠিকানা</div>

                    <div class="row mb-3">
                        <label class="col-md-3 ck-label" for="zone_id">
                            সেক্টর / বিভাগ: <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <select name="zone_id" id="zone_id" class="form-control" required>
                                <option value="">-- সেক্টর / বিভাগ সিলেক্ট করুন --</option>
                                @foreach($zones as $zone)
                                @php([$minCharge, $maxCharge] = $zone->chargeRange())
                                <option value="{{$zone->id}}" {{old('zone_id')==$zone->id?'selected':''}}>
                                    {{$zone->bn_name ?: $zone->name}} (ডেলিভারি চার্জ ৳{{en2bnNumber(number_format($minCharge))}}{{$maxCharge > $minCharge ? '–'.en2bnNumber(number_format($maxCharge)) : ''}})
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('zone_id'))
                            <p class="ck-error">{{ $errors->first('zone_id') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 ck-label" for="district_id">
                            জেলা: <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <select name="district_id" id="district_id" class="form-control" required disabled>
                                <option value="">-- আগে সেক্টর সিলেক্ট করুন --</option>
                            </select>
                            @if ($errors->has('district_id'))
                            <p class="ck-error">{{ $errors->first('district_id') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 ck-label" for="upazila_id">
                            উপজেলা / থানা: <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <select name="upazila_id" id="upazila_id" class="form-control" required disabled>
                                <option value="">-- আগে জেলা সিলেক্ট করুন --</option>
                            </select>
                            @if ($errors->has('upazila_id'))
                            <p class="ck-error">{{ $errors->first('upazila_id') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 ck-label" for="address">
                            আপনার ঠিকানা: <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <textarea id="address" name="address" class="form-control" rows="2" placeholder="বাসা নং, রোড নং, এলাকার নাম লিখুন" required>{{old('address', $user ? $user->address_line1 : '')}}</textarea>
                            @if ($errors->has('address'))
                            <p class="ck-error">{{ $errors->first('address') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-3 ck-label" for="note">
                            নোট (অপশনাল):
                        </label>
                        <div class="col-md-9">
                            <textarea id="note" name="note" class="form-control" rows="2" placeholder="আপনি চাইলে কোন নোট লিখতে পারেন।">{{old('note')}}</textarea>
                        </div>
                    </div>

                    <div class="ck-section-title">৩. পেমেন্ট মেথড</div>

                    <label class="ck-pay-option" data-method="cod">
                        <input type="radio" name="payment_method" value="cod" {{old('payment_method','cod')=='cod'?'checked':''}}>
                        <b>ক্যাশ অন ডেলিভারি</b>
                        <small>ডেলিভারি চার্জের {{en2bnNumber($general->cod_advance_percent)}}% অগ্রিম দিয়ে অর্ডার কনফার্ম করুন, বাকি টাকা পণ্য হাতে পেয়ে দিন।</small>
                    </label>

                    <label class="ck-pay-option" data-method="full_advance">
                        <input type="radio" name="payment_method" value="full_advance" {{old('payment_method')=='full_advance'?'checked':''}}>
                        <b>ফুল অগ্রিম (Bangla QR)</b>
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
                            <div class="col-md-6 mb-2">
                                <label class="ck-label" for="trx_id">TrxID: <span class="text-danger">*</span></label>
                                <input type="text" id="trx_id" name="trx_id" class="form-control" value="{{old('trx_id')}}" placeholder="যেমন: 9AB3CD7EF2">
                                @if ($errors->has('trx_id'))
                                <p class="ck-error">{{ $errors->first('trx_id') }}</p>
                                @endif
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="ck-label" for="sender_number">প্রেরকের নম্বর: <span class="text-danger">*</span></label>
                                <input type="tel" id="sender_number" name="sender_number" class="form-control" value="{{old('sender_number')}}" placeholder="যে নম্বর থেকে পেমেন্ট করেছেন">
                                @if ($errors->has('sender_number'))
                                <p class="ck-error">{{ $errors->first('sender_number') }}</p>
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

                    @if($general->fb_discount_status)
                    @php($fbDiscount = fbDiscountFor($totals['subtotal'], $general))
                    <div id="fbDiscountBox">
                        <div class="ck-section-title">৪. Facebook পোস্টে স্পেশাল ডিসকাউন্ট (অপশনাল)</div>
                        <label class="ck-label" for="fb_post_link">Facebook পোস্টের লিংক দিন, স্পেশাল ডিসকাউন্ট পান</label>
                        <input type="url" id="fb_post_link" name="fb_post_link" class="form-control" value="{{old('fb_post_link')}}" placeholder="https://www.facebook.com/...">
                        <p class="ck-coupon-msg" id="fbPostMsg"></p>
                        @if ($errors->has('fb_post_link'))
                        <p class="ck-error">{{ $errors->first('fb_post_link') }}</p>
                        @endif
                        <div style="font-size: 13px;color: #555;line-height: 20px;margin-top: 6px;">
                            @if($fbDiscount > 0)
                            যাচাইয়ে অনুমোদিত হলে এই অর্ডারে <b style="color: #ff005e;">৳{{en2bnNumber(number_format($fbDiscount))}}</b> কম দিতে হবে।<br>
                            @endif
                            শর্ত: আগে আমাদের কাছ থেকে কেনা ও ডেলিভারি পাওয়া পণ্য নিয়ে Facebook পোস্ট,
                            পোস্টে {{$general->fb_page_name ?: general()->title}} পেজ ট্যাগ বা কোলাবরেশন থাকতে হবে,
                            আর পোস্টটি কমপক্ষে {{en2bnNumber($general->fb_post_min_days)}} দিন পুরনো হতে হবে। একটি পোস্ট একবারই ব্যবহার করা যাবে।
                        </div>
                    </div>
                    @if($general->fb_full_advance_rule=='not_allowed')
                    <p id="fbNotAllowedMsg" style="display: none;font-size: 13px;color: #777;margin-top: 15px;">Facebook পোস্ট ডিসকাউন্ট ফুল অগ্রিম পেমেন্টে প্রযোজ্য নয়।</p>
                    @endif
                    @endif

                    <hr>

                    <div>
                        <h4>Product Overview</h4>

                        <div class="table-responsive">
                            <table class="table table-bordered overViewTable">
                                <tr>
                                    <th style="width: 60px;min-width: 60px;">Image</th>
                                    <th style="min-width: 200px;">Product</th>
                                    <th style="width: 100px;min-width: 100px;">Price</th>
                                    <th style="width: 60px;min-width: 60px;">Quantity</th>
                                    <th style="width: 110px;min-width: 110px;">Total</th>
                                </tr>
                                @foreach($carts as $cart)
                                <tr>
                                    <td>
                                        @if($cart->product)
                                        <img src="{{asset($cart->product->image())}}" style="width: 40px;margin-right: 5px;">
                                        @endif
                                    </td>
                                    <td>
                                        @if($cart->product)
                                        <a href="{{route('productView',$cart->product->slug?:Str::slug($cart->product->name))}}" style="color: #000000;font-weight: bold;">
                            		      {{$cart->product->name}}
                            		    </a>
                                        @if($cart->color || $cart->size)
                                        <br>
                                        <small style="color:#777;">
                                        @if($cart->color)Color: {{$cart->color}}@endif
                                        @if($cart->color && $cart->size) &nbsp; @endif
                                        @if($cart->size)Size: {{$cart->size}}@endif
                                        </small>
                                        @endif
                                        @else
                                        Not Found
                                        @endif
                                    </td>
                                    <td>{{$cart->product?$cart->itemprice():0}}</td>
                                    <td>{{$cart->quantity}}</td>
                                    <td>
                                        @if($cart->product)
                        		          {{priceFullFormat($cart->quantity * $cart->itemprice())}}
                        		        @else
                        		           {{priceFullFormat($cart->quantity*0)}}
                        		        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="checkoutInfo">
                    <h4>অর্ডার সামারি</h4>

                    <div class="ck-coupon mb-3">
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
                </div>
            </div>
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
            zones: {!! json_encode($zones->mapWithKeys(function($z){ return [$z->id => (float)$z->shipping_charge]; })) !!},
            districtCharges: {},
            fbNotWithFullAdvance: {{ $general->fb_discount_status && $general->fb_full_advance_rule=='not_allowed' ? 'true' : 'false' }},
            fbCheckUrl: "{{ route('fbPostCheck') }}",
            locationsUrl: "{{ route('checkoutLocations') }}",
            couponApplyUrl: "{{ route('couponApply') }}",
            couponRemoveUrl: "{{ route('couponRemove') }}",
            csrf: "{{ csrf_token() }}",
            oldDistrict: "{{ old('district_id') }}",
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
            var zoneId = $('#zone_id').val();
            var method = $('input[name="payment_method"]:checked').val();
            var hasZone = zoneId !== '' && ck.zones[zoneId] !== undefined;

            var districtId = $('#district_id').val();
            var delivery = hasZone ? ck.zones[zoneId] : 0;
            if(hasZone && districtId && ck.districtCharges[districtId] !== undefined){
                delivery = ck.districtCharges[districtId];
            }
            var baseDelivery = delivery;
            var freeDelivery = false;
            if(method === 'full_advance' && ck.fullAdvanceFree){
                freeDelivery = delivery > 0;
                delivery = 0;
            }

            var total = ck.subtotal - ck.discount + ck.tax + delivery;
            var advance = 0;
            if(method === 'full_advance'){
                advance = total;
            }else if(method === 'cod'){
                advance = Math.ceil(delivery * ck.codPercent / 100);
            }

            return {hasZone: hasZone, method: method, delivery: delivery, baseDelivery: baseDelivery, freeDelivery: freeDelivery, total: total, advance: advance, due: total - advance};
        }

        function render(){
            var t = calculate();

            $('#sumSubtotal').text(money(ck.subtotal));
            $('#rowDiscount').toggle(ck.discount > 0);
            $('#sumDiscount').text('- ' + money(ck.discount));
            $('#rowTax').toggle(ck.tax > 0);
            $('#sumTax').text(money(ck.tax));

            if(!t.hasZone){
                $('#sumDelivery').html('<small class="text-muted">সেক্টর সিলেক্ট করুন</small>');
            }else if(t.freeDelivery){
                $('#sumDelivery').html('<del class="text-muted">' + money(t.baseDelivery) + '</del> <b class="text-success">৳০ (ফ্রি)</b>');
            }else{
                $('#sumDelivery').text(money(t.delivery));
            }

            $('#sumTotal').text(money(t.total));
            $('#sumAdvanceLabel').text(t.method === 'full_advance' ? 'এখন পেমেন্ট করতে হবে' : 'এখন অগ্রিম দিতে হবে');
            $('#sumAdvance').text(t.hasZone || t.method === 'full_advance' ? money(t.advance) : '—');
            $('#sumDue').text(t.hasZone || t.method === 'full_advance' ? money(t.due) : '—');

            $('.ck-pay-option').removeClass('active');
            $('.ck-pay-option[data-method="' + t.method + '"]').addClass('active');

            var text = '';
            if(t.method === 'full_advance'){
                text = 'সম্পূর্ণ <b>' + money(t.advance) + '</b> Bangla QR-এ পেমেন্ট করুন';
                if(!t.hasZone){
                    text += '<br><small class="text-muted">ডেলিভারি চার্জসহ সঠিক হিসাব দেখতে সেক্টর সিলেক্ট করুন</small>';
                }
            }else if(!t.hasZone){
                text = '<small>অগ্রিমের পরিমাণ দেখতে আগে ঠিকানার সেক্টর / বিভাগ সিলেক্ট করুন</small>';
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
            $('#trx_id, #sender_number').prop('required', needsProof && t.hasZone);
        }

        function fillSelect($select, items, placeholder, selected){
            $select.empty().append($('<option>', {value: '', text: placeholder}));
            $.each(items, function(i, item){
                $select.append($('<option>', {value: item.id, text: item.name, selected: String(item.id) === String(selected)}));
            });
            $select.prop('disabled', items.length === 0);
        }

        function loadDistricts(selected, upazilaSelected){
            var zone = $('#zone_id').val();
            fillSelect($('#district_id'), [], zone ? 'লোড হচ্ছে...' : '-- আগে সেক্টর সিলেক্ট করুন --');
            fillSelect($('#upazila_id'), [], '-- আগে জেলা সিলেক্ট করুন --');
            if(!zone){ return; }

            $.get(ck.locationsUrl, {zone: zone}, function(res){
                // Remember per-district charges; show the charge next to districts that differ from the zone default
                ck.districtCharges = {};
                $.each(res.items, function(i, item){
                    ck.districtCharges[item.id] = item.charge;
                    if(item.charge !== ck.zones[zone]){
                        item.name += ' (৳' + bn(item.charge) + ')';
                    }
                });
                fillSelect($('#district_id'), res.items, '-- জেলা সিলেক্ট করুন --', selected);
                // A zone with a single district (e.g. Dhaka City) skips a pointless step
                if(!selected && res.items.length === 1){
                    $('#district_id').val(res.items[0].id);
                    selected = res.items[0].id;
                }
                if(selected){ loadUpazilas(upazilaSelected); }
                render();
            });
        }

        function loadUpazilas(selected){
            var zone = $('#zone_id').val();
            var district = $('#district_id').val();
            fillSelect($('#upazila_id'), [], district ? 'লোড হচ্ছে...' : '-- আগে জেলা সিলেক্ট করুন --');
            if(!district){ return; }

            $.get(ck.locationsUrl, {zone: zone, district: district}, function(res){
                fillSelect($('#upazila_id'), res.items, '-- উপজেলা / থানা সিলেক্ট করুন --', selected);
            });
        }

        $('#zone_id').change(function(){
            loadDistricts();
            render();
        });

        $('#district_id').change(function(){
            loadUpazilas();
            render();
        });

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

        // Warns straight away when the post link is invalid or was already used
        $('#fb_post_link').on('change', function(){
            var link = $.trim($(this).val());
            if(!link){
                $('#fbPostMsg').text('');
                return;
            }
            $.get(ck.fbCheckUrl, {link: link}, function(res){
                $('#fbPostMsg').text(res.message).css('color', res.success ? '#28a745' : 'red');
            });
        });

        $('#checkoutForm').submit(function(){
            $('#placeOrderBtn').prop('disabled', true).text('অর্ডার প্রসেস হচ্ছে...');
        });

        if($('#zone_id').val()){
            loadDistricts(ck.oldDistrict, ck.oldUpazila);
        }
        render();

    });
</script>

@endpush
