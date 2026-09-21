@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>My Order | {{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta name="keywords" content="{{App\Models\General::latest()->first()->meta_key}}">
<meta property="og:title" content="{{App\Models\General::latest()->first()->name}}">
<meta property="og:description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta property="og:image" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta property="og:url" content="{{route('index')}}">
@endsection
@push('css')

<style type="text/css">
    .myrecentorder tr td, th {
        padding: 5px !important;
    }
</style>

@endpush
@section('contents')

<div class="userdashboard">
    <div class="container">
    <div class="row" style="margin:0;">
        <div class="col-lg-3 usersidebardiv">
            @include(App\Models\General::first()->theme.'.customer.includes.sidebar')
        </div>
        <div class="col-lg-9 usermainbody">

        	@include(App\Models\General::first()->theme.'.alerts')

            <div class="usercontent">
                <div class="myrecentorder" style="min-height: 300px;">
                    <p style="font-weight: bold;padding-top: 5px;">My Orders</p>
                    <div style="overflow: auto;">
                  

                    <table class="table table-bordered">

                        <tr>
                            <th style="width: 50px;min-width: 50px;">SL</th>
                            <th style="width: 150px;min-width: 150px;">Order ID</th>
                            <th style="width: 170px;min-width: 170px;">Placed On</th>
                            <th style="width: 50px;min-width: 50px;">Items</th>
                            <th style="width: 150px;min-width: 150px;">Total</th>
                            <th style="width: 80px;min-width: 80px;">Status</th>
                            <th style="width: 80px;min-width: 80px;">Payment</th>
                        </tr>
                        @foreach($orders as $i=>$order)
                        <tr>
                            <td>{{$orders->currentpage()==1?$i+1:$i+($orders->perpage()*($orders->currentpage() - 1))+1}}</td>
                            <td><a href="{{ route('customer.orderDetails',$order->id) }}">{{ $order->invoice }}</a></td>
                            <td>{{ $order->created_at->format('d-m-Y h:i A') }}</td>
                            <td style="text-align:center;"> {{$order->items->count()}}</td>
                            <td>{{ priceFullFormat($order->grand_total)}}</td>
                            
                            <td style="font-size: 14px;padding: 4px;">
                                
                                @if($order->payment_method==null)
                                <a class="" href="{{ route('orderPayment', $order->id) }}">Pay now</a><br>
                                <b style="color: #ff5722;">Payment Pending</b>
                                @else
                                @if($order->order_status=='confirmed')
                                <span class="badge badge-success" style="background:#e91e63;">{{ucfirst($order->order_status)}}</span>
                                @elseif($order->order_status=='shipped')
                                <span class="badge badge-success" style="background:#673ab7;">{{ucfirst($order->order_status)}}</span>
                                @elseif($order->order_status=='delivered')
                                <span class="badge badge-success" style="background:#1c84c6;">{{ucfirst($order->order_status)}}</span>
                                @elseif($order->order_status=='cancelled')
                                <span class="badge badge-success" style="background:#f44336;">{{ucfirst($order->order_status)}}</span>
                                @else
                                <span class="badge badge-success" style="background:#ff9800;">{{ucfirst($order->order_status)}}</span>
                                @endif
                                @endif
                            </td>
                            
                            <td>
                                @if($order->payment_status=='partial')
                                <span class="badge badge-success" style="background:#ff9800;">{{ucfirst($order->payment_status)}}</span>
                                @elseif($order->payment_status=='paid')
                                <span class="badge badge-success" style="background:#673ab7;">{{ucfirst($order->payment_status)}}</span>
                                @else
                                <span class="badge badge-success" style="background:#f44336;">{{ucfirst($order->payment_status)}}</span>
                                @endif
                            </td>
                        </tr>
                       
                        @endforeach
                    </table>


                    </div>
                    {{ $orders->links('pagination') }}
                </div>
            </div>


        </div>
    </div>
    </div>
</div>
@endsection
@push('js')
@endpush