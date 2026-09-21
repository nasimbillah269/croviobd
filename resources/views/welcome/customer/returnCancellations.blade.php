@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>Return Cancellations | {{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
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
                    <p style="font-weight: bold;border-bottom: 1px solid #eaeded;padding: 5px 0;">My  Return & Cancellations</p>
                    <div class="ivoiceTable" style="overflow:auto;">
                <table class="table table-bordered invoice-table table-sm ">
                    <thead>
                        <tr>
                            <th>Item Description</th>
                            <th style="width:50%;">Reason & Message</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($orderItems as $i=>$item)
                        <tr>
                            <td style="min-width:250px;">
                                <div class="row" style="margin:0;">
                                    <div class="col-2" style="padding:0;">
                                        @if($item->product)
                                            <img src="{{asset($item->product->fi())}}" style="max-width:50px;width:100%;">
                                        @endif
                                    </div>
                                    <div class="col-10" style="padding:0">
                                    @if($item->Mitem)
                                        <div><strong style="font-size: 12px;line-height: 14px;display: block;">{{ $item->Mitem->product_name }}</strong></div>
                                    
                                        <small>
                                            @if($item->Mitem->color)
                                            Color: {{ $item->Mitem->color }}, 
                                            @endif
        
                                            @if($item->Mitem->size)
                                            Size: {{ $item->Mitem->size }},
                                            @endif
                                        </small>
                                        @if($item->Mitem->order)
                                        <a href="{{ route('customer.orderDetails',$item->Mitem->order_id) }}">#{{$item->Mitem->order->invoice}}</a>
                                        @endif
                                @endif <br>
                                {{App\Models\General::first()->currency}} {{ number_format($item->return_price,0) }}
                                @if($item->return_type)
                                <span class="badge badge-info" style="background: #ff9800;">Return</span>
                                @else
                                <span class="badge badge-primary" style="background: #ff5722;">Cancel</span>
                                @endif
                                </div>
                                </div>
                                </td>
                                <td style="font-size: 12px;">
                                <span><b>Qty:</b></span> {{$item->return_quantity}} <b>Status:</b> 
                                @if($item->status=='confirmed')
                                <span class="badge badge-info" style="background: #3f51b5;">{{ucfirst($item->status)}}</span>
                                @else
                                <span class="badge badge-info" style="background: #ff9800;">{{ucfirst($item->status)}}</span>
                                @endif
                                
                                <br>    
                                    
                                <span><b>Type:</b></span> 
                               @if($item->reasion=='Return With Replace')
                               <span class="badge badge-success" style="background-color: #e91e63;">{{$item->reasion}}</span>
                               @else
                               <span class="badge badge-success" style="background-color: #f44336;">{{$item->reasion}}</span>
                               @endif<br>
                                <span>{{$item->description}}</span>
                                </td>
                            
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
            </div>
                </div>
            </div>


        </div>
    </div>
    </div>
</div>
@endsection
@push('js')
@endpush