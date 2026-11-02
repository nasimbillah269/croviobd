@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>Dashboard | {{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta name="keywords" content="{{App\Models\General::latest()->first()->meta_key}}">
<meta property="og:title" content="{{App\Models\General::latest()->first()->name}}">
<meta property="og:description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta property="og:image" content="{{asset(general()->logo())}}">
<meta property="og:url" content="{{route('index')}}">
@endsection
@push('css')
@endpush
@section('contents')
@php
    $user = Auth::user();
    $statusCounts = $user->orders()->reorder()->selectRaw('order_status, count(*) as total')->groupBy('order_status')->pluck('total','order_status');
    $stats = [
        ['label'=>'All Orders','value'=>$statusCounts->sum(),'icon'=>'fa fa-shopping-bag','tone'=>'pink','type'=>null],
        ['label'=>'Pending','value'=>$statusCounts['pending'] ?? 0,'icon'=>'fa-regular fa-clock','tone'=>'amber','type'=>'pending'],
        ['label'=>'Confirmed','value'=>$statusCounts['confirmed'] ?? 0,'icon'=>'fa-regular fa-circle-check','tone'=>'blue','type'=>'confirmed'],
        ['label'=>'Shipped','value'=>$statusCounts['shipped'] ?? 0,'icon'=>'fa fa-truck','tone'=>'violet','type'=>'shipped'],
        ['label'=>'Completed','value'=>$statusCounts['delivered'] ?? 0,'icon'=>'fa fa-check','tone'=>'green','type'=>'delivered'],
    ];
    $recentOrders = $user->orders()->take(5)->withCount('items')->get();
@endphp
<div class="userdashboard">
    <div class="container">
    <div class="row">
        <div class="col-lg-3 usersidebardiv">
            @include(App\Models\General::first()->theme.'.customer.includes.sidebar')
        </div>
        <div class="col-lg-9 usermainbody">

            @include(App\Models\General::first()->theme.'.alerts')

            <div class="cd-welcome">
                <div class="cd-welcome-user">
                    <img src="{{asset($user->image())}}" alt="{{$user->name}}">
                    <div style="min-width:0;">
                        <h3>Hello, {{$user->name}}</h3>
                        <p>Track your orders, manage your profile and shipping details.</p>
                    </div>
                </div>
                <div class="cd-welcome-actions">
                    <a href="{{route('customer.profileEdit')}}"><i class="fa fa-pencil"></i> Edit Profile</a>
                    <a href="{{route('customer.changePassword')}}" class="cd-ghost"><i class="fa fa-lock"></i> Password</a>
                </div>
            </div>

            <div class="cd-stats">
                @foreach($stats as $stat)
                <a class="cd-stat" href="{{ $stat['type'] ? route('customer.myOrders',['type'=>$stat['type']]) : route('customer.myOrders') }}">
                    <div class="cd-stat-icon cd-tone-{{$stat['tone']}}"><i class="{{$stat['icon']}}"></i></div>
                    <div class="cd-stat-value">{{$stat['value']}}</div>
                    <div class="cd-stat-label">{{$stat['label']}}</div>
                </a>
                @endforeach
            </div>

            <div class="cd-card">
                <div class="cd-card-head">
                    <div>
                        <h4>Recent Orders</h4>
                        <small>Your latest {{ $recentOrders->count() }} orders</small>
                    </div>
                    @if($recentOrders->count())
                    <a href="{{route('customer.myOrders')}}" class="cd-link">View all <i class="fa fa-angle-right"></i></a>
                    @endif
                </div>
                @if($recentOrders->count())
                <div class="cd-table-wrap">
                    <table class="table cd-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td><a class="cd-order-link" href="{{ route('customer.orderDetails',$order->id) }}">#{{ $order->invoice }}</a></td>
                                <td style="white-space:nowrap;">{{ $order->created_at->format('d M Y') }}</td>
                                <td>{{ $order->items_count }}</td>
                                <td style="white-space:nowrap;font-weight:600;">{{ priceFullFormat($order->grand_total) }}</td>
                                <td>
                                    @if($order->payment_method==null)
                                    <span class="cd-pill unpaid">Payment Pending</span>
                                    @else
                                    <span class="cd-pill {{$order->order_status}}">{{ $order->order_status=='delivered' ? 'Completed' : ucfirst($order->order_status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="cd-empty">
                    <i class="fa fa-shopping-bag"></i>
                    <h5>No orders yet</h5>
                    <p>When you place an order, it will show up here.</p>
                    <a href="{{route('index')}}" class="btn btn-primary">Start Shopping</a>
                </div>
                @endif
            </div>

            <div class="cd-card">
                <div class="cd-card-head">
                    <div>
                        <h4>Shipping Address</h4>
                        <small>Used as default for your orders</small>
                    </div>
                    <a href="{{route('customer.profileEdit')}}" class="cd-link"><i class="fa fa-pencil"></i> Edit</a>
                </div>
                <dl class="cd-info">
                    <div class="cd-full">
                        <dt>Address</dt>
                        <dd>{!! $user->address_line1 ?: '<span class="cd-empty-val">Not set</span>' !!}</dd>
                    </div>
                    <div>
                        <dt>City</dt>
                        <dd>{!! $user->city_name ? e($user->city_name) : '<span class="cd-empty-val">Not set</span>' !!}</dd>
                    </div>
                    <div>
                        <dt>Postal Code</dt>
                        <dd>{!! $user->postal_code ? e($user->postal_code) : '<span class="cd-empty-val">Not set</span>' !!}</dd>
                    </div>
                    <div>
                        <dt>District</dt>
                        <dd>{!! $user->districtN ? e($user->districtN->name) : '<span class="cd-empty-val">Not set</span>' !!}</dd>
                    </div>
                    <div>
                        <dt>Country</dt>
                        <dd>Bangladesh</dd>
                    </div>
                </dl>
            </div>

        </div>
    </div>
    </div>
</div>
@endsection
@push('js')
@endpush
