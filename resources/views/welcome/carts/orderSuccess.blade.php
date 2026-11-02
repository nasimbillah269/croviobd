@extends(general()->theme.'.layouts.app') @section('title')
<title>অর্ডার সফল হয়েছে | {{general()->title}}</title>
@endsection @section('SEO')
@endsection

@push('css')
<style>
.orderSuccessPage{
    padding: 30px 0 50px;
}
.os-card{
    max-width: 680px;
    margin: 0 auto;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.08);
    padding: 30px 25px;
}
.os-icon{
    width: 70px;
    height: 70px;
    line-height: 70px;
    border-radius: 50%;
    background: #28a745;
    color: #fff;
    font-size: 36px;
    text-align: center;
    margin: 0 auto 15px;
}
.os-card h2{
    text-align: center;
    font-size: 26px;
    font-weight: 700;
    margin-bottom: 8px;
}
.os-invoice{
    text-align: center;
    font-size: 18px;
    margin-bottom: 20px;
}
.os-invoice b{
    color: #ff005e;
    letter-spacing: 1px;
}
.os-table th, .os-table td{
    padding: 6px 10px;
    font-size: 15px;
}
.os-steps{
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px 20px;
    margin: 20px 0;
}
.os-steps h5{
    font-weight: 700;
    margin-bottom: 10px;
}
.os-steps ol{
    padding-left: 20px;
    margin: 0;
}
.os-steps li{
    margin-bottom: 6px;
    line-height: 1.6;
}
.os-actions{
    text-align: center;
}
.os-actions .btn{
    margin: 4px;
}
</style>
@endpush

@section('contents')

<script>
    window.dataLayer = window.dataLayer || [];
    dataLayer.push({
        event: "purchase",
        ecommerce: {
            transaction_id: "{{ $order->invoice }}",
            currency: "{{ general()->currency }}",
            value: {{ round($order->grand_total) }},
            shipping: {{ round($order->shipping_charge) }},
            discount: {{ round($order->coupon_discount) }},
            items: [
                @foreach($order->items as $item)
                {
                    item_id: "{{ $item->product_id }}",
                    item_name: "{{ $item->product_name }}",
                    price: "{{ $item->price }}",
                    quantity: "{{ $item->quantity }}"
                }@if(!$loop->last),@endif
                @endforeach
            ]
        }
    });
</script>

<div class="orderSuccessPage">
    <div class="container">
        <div class="os-card">
            <div class="os-icon">&#10003;</div>
            <h2>ধন্যবাদ, {{$order->name}}!</h2>
            <p class="text-center">আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে।</p>
            <div class="os-invoice">অর্ডার নম্বর: <b>#{{$order->invoice}}</b></div>

            <table class="table table-bordered os-table">
                <tr>
                    <th>পেমেন্ট মেথড</th>
                    <td>{{$order->payment_method=='Cash On Delivery' ? 'ক্যাশ অন ডেলিভারি' : 'ফুল অগ্রিম (Bangla QR)'}}</td>
                </tr>
                <tr>
                    <th>সর্বমোট</th>
                    <td>৳{{en2bnNumber(number_format($order->grand_total))}}</td>
                </tr>
                @if($order->advance_amount > 0)
                <tr>
                    <th>অগ্রিম পেমেন্ট (যাচাই হবে)</th>
                    <td>৳{{en2bnNumber(number_format($order->advance_amount))}} &nbsp;<small class="text-muted">TrxID: {{$order->transection}}</small></td>
                </tr>
                @endif
                <tr>
                    <th>ডেলিভারির সময় দিতে হবে</th>
                    <td><b>৳{{en2bnNumber(number_format(max($order->grand_total - $order->advance_amount, 0)))}}</b></td>
                </tr>
                @if($order->fb_post_status=='pending')
                <tr>
                    <th>Facebook পোস্ট ডিসকাউন্ট</th>
                    <td>যাচাই চলছে। অনুমোদিত হলে @if($fbDiscount = fbDiscountFor($order->total_price))<b>৳{{en2bnNumber(number_format($fbDiscount))}}</b> @endif কম দিতে হবে (আগেই পরিশোধ করে থাকলে ফেরত দেওয়া হবে)। ফলাফল আপনাকে জানানো হবে।</td>
                </tr>
                @endif
                <tr>
                    <th>ডেলিভারি ঠিকানা</th>
                    <td>{{$order->full_address ?: $order->address}}</td>
                </tr>
                <tr>
                    <th>মোবাইল</th>
                    <td>{{$order->mobile}}</td>
                </tr>
            </table>

            <div class="os-steps">
                <h5>এরপর কী হবে?</h5>
                <ol>
                    @if($order->advance_amount > 0)
                    <li>আমরা আপনার দেওয়া TrxID দিয়ে অগ্রিম পেমেন্টটি যাচাই করব।</li>
                    @endif
                    <li>আমাদের একজন প্রতিনিধি <b>{{$order->mobile}}</b> নম্বরে কল করে আপনার অর্ডারটি কনফার্ম করবেন। অনুগ্রহ করে ফোনটি চালু রাখুন।</li>
                    <li>কনফার্ম হওয়ার পর পণ্যটি প্যাক করে কুরিয়ারে পাঠানো হবে।</li>
                    <li>পণ্য হাতে পেয়ে ডেলিভারি ম্যানকে বাকি টাকা পরিশোধ করুন।</li>
                </ol>
                @if((general()->sms_status && general()->order_place_sms_customer) || ($order->email && general()->mail_status))
                <p class="mt-2 mb-0" style="font-size: 14px;">
                    অর্ডারের কনফার্মেশন
                    @if((general()->sms_status && general()->order_place_sms_customer)) আপনার মোবাইলে SMS @endif
                    @if((general()->sms_status && general()->order_place_sms_customer) && $order->email && general()->mail_status) ও @endif
                    @if($order->email && general()->mail_status) ইমেইলে ({{$order->email}}) @endif
                    পাঠানো হয়েছে।
                </p>
                @endif
            </div>

            @if(general()->mobile)
            <p class="text-center" style="font-size: 14px;">যেকোনো প্রয়োজনে কল করুন: <a href="tel:{{general()->mobile}}">{{general()->mobile}}</a></p>
            @endif

            <div class="os-actions">
                <a href="{{route('invoiceView',$order->invoice)}}" class="btn btn-outline-dark">ইনভয়েস দেখুন</a>
                <a href="{{route('OrderTrack',['invoice'=>$order->invoice])}}" class="btn btn-outline-dark">অর্ডার ট্র্যাক করুন</a>
                @if(Auth::check())
                <a href="{{route('customer.orderDetails',$order->id)}}" class="btn btn-outline-dark">আমার অর্ডার</a>
                @endif
                <a href="{{route('index')}}" class="btn btn-dark">কেনাকাটা চালিয়ে যান</a>
            </div>
        </div>
    </div>
</div>

@endsection
