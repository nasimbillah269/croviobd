@extends('admin.layouts.app')
@section('title')
<title>Dashboard - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@php
    $currency = general()->currency;
    $money = function($amount) use ($currency) { return $currency.' '.number_format($amount); };
    $statusMeta = [
        'pending'   => ['Pending',   '#f59e0b', 'warning'],
        'confirmed' => ['Confirmed', '#4f46e5', 'primary'],
        'shipped'   => ['Shipped',   '#0ea5e9', 'info'],
        'delivered' => ['Delivered', '#10b981', 'success'],
        'cancelled' => ['Cancelled', '#ef4444', 'danger'],
    ];
    $statusTotal = $statusCounts->only(array_keys($statusMeta))->sum();
    $hour = (int)Carbon\Carbon::now()->format('H');
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
@endphp

@section('contents')
<div class="content-body">

    <div class="pa-welcome">
        <div>
            <h2>{{$greeting}}, {{Str::before(Auth::user()->name, ' ') ?: Auth::user()->name}} 👋</h2>
            <p>Here is what is happening with your store today.</p>
        </div>
        <span class="pa-date"><i class="far fa-calendar"></i> {{Carbon\Carbon::now()->format('l, d M Y')}}</span>
    </div>

    {{-- KPI tiles --}}
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="pa-kpi">
                <div class="pa-kpi-top">
                    <span class="pa-kpi-label">Revenue this month</span>
                    <span class="pa-kpi-icon pa-tone-primary"><i class="fas fa-wallet"></i></span>
                </div>
                <div class="pa-kpi-value">{{$money($stats['monthSales'])}}</div>
                <div class="pa-kpi-foot">
                    @if($stats['monthTrend'] === null)
                    <span class="pa-trend up">New</span>
                    @else
                    <span class="pa-trend {{$stats['monthTrend'] > 0 ? 'up' : ($stats['monthTrend'] < 0 ? 'down' : 'flat')}}">{{$stats['monthTrend'] > 0 ? '+' : ''}}{{$stats['monthTrend']}}%</span>
                    @endif
                    vs same days last month
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="pa-kpi">
                <div class="pa-kpi-top">
                    <span class="pa-kpi-label">Orders today</span>
                    <span class="pa-kpi-icon pa-tone-success"><i class="fas fa-shopping-bag"></i></span>
                </div>
                <div class="pa-kpi-value">{{number_format($stats['todayOrders'])}}</div>
                <div class="pa-kpi-foot">
                    @if($stats['todayTrend'] === null)
                    <span class="pa-trend up">New</span>
                    @else
                    <span class="pa-trend {{$stats['todayTrend'] > 0 ? 'up' : ($stats['todayTrend'] < 0 ? 'down' : 'flat')}}">{{$stats['todayTrend'] > 0 ? '+' : ''}}{{$stats['todayTrend']}}%</span>
                    @endif
                    vs yesterday &middot; {{$money($stats['todaySales'])}}
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{route('admin.orders','pending')}}" style="text-decoration: none;">
            <div class="pa-kpi">
                <div class="pa-kpi-top">
                    <span class="pa-kpi-label">Pending orders</span>
                    <span class="pa-kpi-icon pa-tone-warning"><i class="fas fa-hourglass-half"></i></span>
                </div>
                <div class="pa-kpi-value">{{number_format($stats['pending'])}}</div>
                <div class="pa-kpi-foot">{{number_format($stats['toShip'])}} confirmed, waiting to ship</div>
            </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="pa-kpi">
                <div class="pa-kpi-top">
                    <span class="pa-kpi-label">Customers</span>
                    <span class="pa-kpi-icon pa-tone-purple"><i class="fas fa-users"></i></span>
                </div>
                <div class="pa-kpi-value">{{number_format($stats['customers'])}}</div>
                <div class="pa-kpi-foot"><span class="pa-trend {{$stats['newCustomers'] ? 'up' : 'flat'}}">+{{number_format($stats['newCustomers'])}}</span> new this month</div>
            </div>
        </div>
    </div>

    {{-- Sales chart + needs attention --}}
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <div class="pa-card-head">
                        <div>
                            <h4 class="card-title">Sales overview</h4>
                            <div class="pa-sub">Last 30 days, cancelled orders excluded</div>
                        </div>
                        <div class="pa-stat-inline">
                            <div><span>Revenue</span><b>{{$money($chart['totalAmount'])}}</b></div>
                            <div><span>Orders</span><b>{{number_format($chart['totalOrders'])}}</b></div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-1">
                    <div class="pa-chart-wrap">
                        <div id="paSalesChart" style="min-height: 300px;"></div>
                        @if($chart['totalOrders'] == 0)
                        <div class="pa-chart-empty">
                            <i class="fas fa-chart-area"></i>
                            <b>No sales in the last 30 days</b>
                            New orders will show up here day by day.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Needs your attention</h4>
                </div>
                <div class="card-body py-0">
                    <ul class="pa-action-list">
                        <li>
                            <a href="{{route('admin.orders','pending')}}">
                                <span class="pa-action-icon pa-tone-warning"><i class="fas fa-phone-alt"></i></span>
                                <span><span class="pa-action-title d-block">Confirm pending orders</span><span class="pa-action-sub">Call customers and confirm</span></span>
                                <span class="pa-action-count {{$stats['pending'] ? '' : 'zero'}}">{{$stats['pending']}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.orders','advance-pending')}}">
                                <span class="pa-action-icon pa-tone-primary"><i class="fas fa-qrcode"></i></span>
                                <span><span class="pa-action-title d-block">Verify advance payments</span><span class="pa-action-sub">Match TrxID with statement</span></span>
                                <span class="pa-action-count {{$stats['advancePending'] ? '' : 'zero'}}">{{$stats['advancePending']}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.orders','fb-pending')}}">
                                <span class="pa-action-icon pa-tone-info"><i class="fab fa-facebook-f"></i></span>
                                <span><span class="pa-action-title d-block">Review Facebook posts</span><span class="pa-action-sub">Approve or reject discount</span></span>
                                <span class="pa-action-count {{$stats['fbPending'] ? '' : 'zero'}}">{{$stats['fbPending']}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.orders','confirmed')}}">
                                <span class="pa-action-icon pa-tone-success"><i class="fas fa-truck"></i></span>
                                <span><span class="pa-action-title d-block">Ready to ship</span><span class="pa-action-sub">Confirmed orders to hand over</span></span>
                                <span class="pa-action-count {{$stats['toShip'] ? '' : 'zero'}}">{{$stats['toShip']}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.stockList')}}">
                                <span class="pa-action-icon pa-tone-danger"><i class="fas fa-box-open"></i></span>
                                <span><span class="pa-action-title d-block">Low stock products</span><span class="pa-action-sub">5 or fewer left</span></span>
                                <span class="pa-action-count {{$stats['lowStock'] ? '' : 'zero'}}">{{$stats['lowStock']}}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Latest orders + status breakdown --}}
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <div class="pa-card-head">
                        <h4 class="card-title">Latest orders</h4>
                        <a href="{{route('admin.orders')}}" class="pa-link">View all <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover pa-table">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestOrders as $order)
                            @php($meta = $statusMeta[$order->order_status] ?? [ucfirst($order->order_status), '#64748b', 'secondary'])
                            <tr>
                                <td><a href="{{route('admin.ordersManage',$order->id)}}"><b>#{{$order->invoice}}</b></a></td>
                                <td class="pa-cust"><b>{{Str::limit($order->name,22)}}</b><span>{{$order->mobile}}</span></td>
                                <td>{{$order->created_at->format('d M, h:i A')}}</td>
                                <td>
                                    @if($order->payment_status=='paid')
                                    <span class="badge badge-success">Paid</span>
                                    @elseif($order->payment_status=='partial')
                                    <span class="badge badge-warning">Partial</span>
                                    @else
                                    <span class="badge badge-secondary">Unpaid</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-{{$meta[2]}}">{{$meta[0]}}</span></td>
                                <td class="text-right"><b style="color: var(--pa-text);">{{$money($order->grand_total)}}</b></td>
                            </tr>
                            @empty
                            <tr><td colspan="6"><div class="pa-empty"><i class="fas fa-inbox"></i>No orders yet</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <div class="pa-card-head">
                        <h4 class="card-title">Order status</h4>
                        <span class="pa-sub">All time</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="paStatusChart"></div>
                    <ul class="pa-legend">
                        @foreach($statusMeta as $key=>$meta)
                        <li>
                            <span><i class="dot" style="background: {{$meta[1]}};"></i> {{$meta[0]}}</span>
                            <span><b>{{number_format($statusCounts[$key] ?? 0)}}</b> <small class="text-muted">{{$statusTotal ? round(($statusCounts[$key] ?? 0)*100/$statusTotal) : 0}}%</small></span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Top products + low stock + catalog --}}
    <div class="row">
        <div class="col-xl-5 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="pa-card-head">
                        <h4 class="card-title">Top selling products</h4>
                        <a href="{{route('admin.products')}}" class="pa-link">All products <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="card-body py-1">
                    @forelse($topProducts as $item)
                    @php($product = $topProductModels->get($item->product_id))
                    <div class="pa-mini-product">
                        <img src="{{asset($product ? $product->image() : general()->favicon())}}" alt="">
                        <div>
                            <div class="pa-mp-name">{{Str::limit($product ? $product->name : $item->product_name, 42)}}</div>
                            <div class="pa-mp-meta">{{number_format($item->qty)}} sold</div>
                        </div>
                        <div class="pa-mp-right"><b>{{$money($item->amount)}}</b><span class="pa-mp-meta">revenue</span></div>
                    </div>
                    @empty
                    <div class="pa-empty"><i class="fas fa-chart-bar"></i>No sales yet</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="pa-card-head">
                        <h4 class="card-title">Low stock</h4>
                        <a href="{{route('admin.stockList')}}" class="pa-link">Stock list <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="card-body py-1">
                    @forelse($lowStockProducts as $product)
                    <div class="pa-mini-product">
                        <img src="{{asset($product->image())}}" alt="">
                        <div>
                            <div class="pa-mp-name">{{Str::limit($product->name, 36)}}</div>
                            <div class="pa-mp-meta">{{$money($product->final_price)}}</div>
                        </div>
                        <div class="pa-mp-right">
                            <span class="badge {{$product->quantity <= 0 ? 'badge-danger' : 'badge-warning'}}">{{$product->quantity <= 0 ? 'Out of stock' : (int)$product->quantity.' left'}}</span>
                            <a href="{{route('admin.productsEdit',$product->id)}}" class="d-block pa-mp-meta mt-1">Restock</a>
                        </div>
                    </div>
                    @empty
                    <div class="pa-empty"><i class="fas fa-check-circle" style="color: var(--pa-success);"></i>All products are well stocked</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Catalog</h4>
                </div>
                <div class="card-body py-0">
                    <ul class="pa-action-list">
                        <li><a href="{{route('admin.products')}}"><span class="pa-action-icon pa-tone-primary"><i class="fas fa-box"></i></span><span class="pa-action-title">Products</span><span class="pa-action-count">{{number_format($stats['products'])}}</span></a></li>
                        <li><a href="{{route('admin.posts')}}"><span class="pa-action-icon pa-tone-info"><i class="fas fa-file-alt"></i></span><span class="pa-action-title">Blog posts</span><span class="pa-action-count">{{number_format($stats['posts'])}}</span></a></li>
                        <li><a href="{{route('admin.pages')}}"><span class="pa-action-icon pa-tone-success"><i class="fas fa-copy"></i></span><span class="pa-action-title">Pages</span><span class="pa-action-count">{{number_format($stats['pages'])}}</span></a></li>
                        <li><a href="{{route('admin.brands')}}"><span class="pa-action-icon pa-tone-warning"><i class="fas fa-tags"></i></span><span class="pa-action-title">Brands</span><span class="pa-action-count">{{number_format($stats['brands'])}}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
<script>
    (function(){
        if(typeof ApexCharts === 'undefined'){ return; }
        var font = "'Inter', 'Hind Siliguri', sans-serif";
        var currency = @json($currency);
        var dates = @json($chart['dates']);
        var paOrderMax = Math.max(1, Math.max.apply(null, @json($chart['orders'])));
        function paPoints(values){ return values.map(function(v, i){ return [dates[i], v]; }); }

        new ApexCharts(document.querySelector('#paSalesChart'), {
            chart: { type: 'area', height: 300, fontFamily: font, toolbar: { show: false }, zoom: { enabled: false } },
            series: [
                { name: 'Revenue', type: 'area', data: paPoints(@json($chart['amount'])) },
                { name: 'Orders', type: 'line', data: paPoints(@json($chart['orders'])) }
            ],
            colors: ['#4f46e5', '#10b981'],
            stroke: { width: [2.5, 2], curve: 'smooth' },
            fill: { type: ['gradient', 'solid'], gradient: { shadeIntensity: 1, opacityFrom: .28, opacityTo: .02, stops: [0, 95] } },
            dataLabels: { enabled: false },
            markers: { size: 0, hover: { size: 5 } },
            grid: { borderColor: '#eef1f6', strokeDashArray: 4, padding: { left: 8, right: 8 } },
            xaxis: {
                type: 'datetime',
                axisBorder: { show: false }, axisTicks: { show: false }, tooltip: { enabled: false },
                labels: { style: { colors: '#94a3b8', fontSize: '11px' }, datetimeUTC: false, format: 'dd MMM' }
            },
            yaxis: [
                { labels: { style: { colors: '#94a3b8', fontSize: '11px' }, formatter: function(v){ return v >= 1000 ? (v/1000).toFixed(v >= 10000 ? 0 : 1) + 'k' : Math.round(v); } } },
                { opposite: true, min: 0, max: paOrderMax, tickAmount: Math.min(paOrderMax, 5), labels: { style: { colors: '#94a3b8', fontSize: '11px' }, formatter: function(v){ return Math.round(v); } } }
            ],
            legend: { position: 'top', horizontalAlign: 'left', fontSize: '12px', markers: { radius: 3 }, itemMargin: { horizontal: 10 } },
            tooltip: {
                shared: true, intersect: false,
                x: { format: 'dd MMM yyyy' },
                y: [
                    { formatter: function(v){ return currency + ' ' + Math.round(v).toLocaleString(); } },
                    { formatter: function(v){ return v + ' orders'; } }
                ]
            }
        }).render();

        new ApexCharts(document.querySelector('#paStatusChart'), {
            chart: { type: 'donut', height: 230, fontFamily: font },
            series: @json(collect(array_keys($statusMeta))->map(function($k) use ($statusCounts){ return (int)($statusCounts[$k] ?? 0); })->values()),
            labels: @json(collect($statusMeta)->pluck(0)->values()),
            colors: @json(collect($statusMeta)->pluck(1)->values()),
            stroke: { width: 3, colors: ['#fff'] },
            dataLabels: { enabled: false },
            legend: { show: false },
            plotOptions: { pie: { donut: { size: '74%', labels: {
                show: true,
                value: { fontSize: '24px', fontWeight: 750, color: '#0f172a', offsetY: 4 },
                total: { show: true, label: 'Orders', fontSize: '12px', color: '#64748b', formatter: function(w){ return w.globals.seriesTotals.reduce(function(a, b){ return a + b; }, 0).toLocaleString(); } }
            } } } },
            tooltip: { y: { formatter: function(v){ return v + ' orders'; } } }
        }).render();
    })();
</script>
@endpush
