@extends(general()->theme.'.layouts.app')
@section('title')
<title>My Order | {{general()->title}} | {{general()->subtitle}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!general()->meta_dsc!!}">
<meta name="keywords" content="{{general()->meta_key}}">
<meta property="og:title" content="{{general()->name}}">
<meta property="og:description" content="{!!general()->meta_dsc!!}">
<meta property="og:image" content="{{asset(general()->logo())}}">
<meta property="og:url" content="{{route('index')}}">
@endsection
@push('css')
@endpush
@section('contents')
@php
    $tabs = [''=>'All', 'pending'=>'Pending', 'confirmed'=>'Confirmed', 'shipped'=>'Shipped', 'delivered'=>'Completed', 'cancelled'=>'Cancelled'];
    $currentType = request('type', '');
@endphp
<div class="userdashboard">
    <div class="container">
    <div class="row">
        <div class="col-lg-3 usersidebardiv">
            @include(general()->theme.'.customer.includes.sidebar')
        </div>
        <div class="col-lg-9 usermainbody">

        	@include(general()->theme.'.alerts')

            <div class="cd-card">
                <div class="cd-card-head">
                    <div>
                        <h4>My Orders</h4>
                        <small>{{ $orders->total() }} {{ Str::plural('order', $orders->total()) }} found</small>
                    </div>
                </div>

                <div class="cd-tabs">
                    @foreach($tabs as $type=>$label)
                    <a href="{{ $type ? route('customer.myOrders',['type'=>$type]) : route('customer.myOrders') }}" class="{{ $currentType==$type ? 'active' : '' }}">{{$label}}</a>
                    @endforeach
                </div>

                @if($orders->count())
                <div class="cd-table-wrap">
                    <table class="table cd-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Placed On</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $i=>$order)
                        <tr>
                            <td style="color:#9ca3af;">{{ $orders->firstItem() + $i }}</td>
                            <td><a class="cd-order-link" href="{{ route('customer.orderDetails',$order->id) }}">#{{ $order->invoice }}</a></td>
                            <td style="white-space:nowrap;">
                                {{ $order->created_at->format('d M Y') }}
                                <div style="font-size:12px;color:#9ca3af;">{{ $order->created_at->format('h:i A') }}</div>
                            </td>
                            <td>{{ $order->items->count() }}</td>
                            <td style="white-space:nowrap;font-weight:600;">{{ priceFullFormat($order->grand_total) }}</td>
                            <td>
                                @if($order->payment_method==null)
                                <span class="cd-pill unpaid">Payment Pending</span><br>
                                <a class="cd-pay-now" href="{{ route('orderPayment', $order->id) }}">Pay now <i class="fa fa-angle-right"></i></a>
                                @else
                                <span class="cd-pill {{$order->order_status}}">{{ $order->order_status=='delivered' ? 'Completed' : ucfirst($order->order_status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if(in_array($order->payment_status, ['paid','partial']))
                                <span class="cd-pill {{$order->payment_status}}">{{ucfirst($order->payment_status)}}</span>
                                @else
                                <span class="cd-pill unpaid">{{ucfirst($order->payment_status ?: 'unpaid')}}</span>
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <a href="{{ route('customer.orderDetails',$order->id) }}" class="btn btn-sm btn-light">View</a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top:20px;">
                    {{ $orders->links('pagination') }}
                </div>
                @else
                <div class="cd-empty">
                    <i class="fa fa-shopping-bag"></i>
                    <h5>No orders found</h5>
                    <p>{{ $currentType ? 'You have no '.strtolower($tabs[$currentType] ?? $currentType).' orders right now.' : 'You haven\'t placed any orders yet.' }}</p>
                    <a href="{{route('index')}}" class="btn btn-primary">Start Shopping</a>
                </div>
                @endif
            </div>

        </div>
    </div>
    </div>
</div>
@endsection
@push('js')
@endpush
