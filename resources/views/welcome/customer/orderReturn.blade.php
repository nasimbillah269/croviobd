@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>Order Return | {{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
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
            	
            	@if(session('selectError'))
                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <strong>Oops! </strong> {{Session::get('selectError') }}.
                </div>
                @endif
    
                <div class="usercontent">
                    <div class="myrecentorder" style="min-height: 300px;">
                        <p style="font-weight: bold;border-bottom: 1px solid #eaeded;padding: 5px 0;">Orders Return
                            <a class="btn btn-sm btn-info float-right" href="{{route('customer.orderDetails',$order->id)}}">Back Order</a>
                        </p>
                        <form action="{{route('customer.orderReturnPost',$item->id)}}" method="post">
                           
                        @if($order->order_status=='delivered' && $order->delivered_at > Carbon\Carbon::now()->subDays(7))
                        @csrf
                            
                            @if ($errors->has('itemId'))
                              <p style="color: red;margin: 0;">{{ $errors->first('itemId') }}</p>
                            @endif
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="2">Products</th>
                                <th>Quantity</th>
                                <th>Return Type</th>
                            </tr>
                          
                            <tr>
                                <td>
                                    @if($item->product)
                                        <img src="{{asset($item->product->fi())}}" style="max-width:50px;width:100%;">
                                    @else
                                    <span>Not Found</span>
                                    @endif
                                </td>
                                <td>
                                    <div><strong style="font-size: 12px;line-height: 14px;display: block;">{{ $item->product_name }}</strong>
                                    Price: {{ number_format($item->final_price) }}
                                    </div>
                                <small>
                                    @if($item->color)
                                    Color: {{ $item->color }}, 
                                    @endif

                                    @if($item->size)
                                    Size: {{ $item->size }},
                                    @endif
                                    @if($item->status=='cancelled')
                                    <span class="badge badge-success" style="background-color: #f44336;">{{ucfirst($item->status)}}</span><br>
                                    @endif
                                </small>
                                </td>
                                <td>
                                     @if ($errors->has('qty'))
                                        <p style="color: red;margin: 0;">{{ $errors->first('qty') }}</p>
                                      @endif
                                    @if($item->quantity-$item->total_return > 0)
                                    <select class="form-control" name="qty" required="">
                                        @for($i = 0; $i < $item->quantity-$item->total_return;$i++)
                                        <option value="{{($item->quantity-$item->total_return)-$i}}">{{($item->quantity-$item->total_return)-$i}}</option>
                                        @endfor
                                    </select>
                                    @else
                                    <span>{{$item->quantity}}</span>
                                    @endif
                                </td>
                                <td>
                                     @if ($errors->has('returntype'))
                                        <p style="color: red;margin: 0;">{{ $errors->first('returntype') }}</p>
                                      @endif
                                    @if($item->quantity-$item->total_return > 0)
                                    <select class="form-control" name="returntype" required="">
                                        <option value="Return With Replace">Return With Replace</option>
                                        <option value="Return With Refund">Return With Refund</option>
                                    </select>
                                    @else
                                    <span></span>
                                    @endif
                                </td>
                            </tr>
                           
                        </table>
                        @endif
                        @if($item->returnItems->count() > 0)
                        <div class="ivoiceTable" style="overflow:auto;">
                            <table class="table table-bordered invoice-table table-sm ">
                                <thead>
                                    <tr>
                                        <th>Item Description</th>
                                        <th style="width:50%;">Reason & Message</th>
                                    </tr>
                                </thead>
                                <tbody>
            
                                    @foreach($item->returnItems as $i=>$rItem)
                                    <tr>
                                        <td style="min-width:250px;">
                                            <div class="row" style="margin:0;">
                                                <div class="col-2" style="padding:0;">
                                                    @if($rItem->product)
                                                        <img src="{{asset($rItem->product->fi())}}" style="max-width:50px;width:100%;">
                                                    @endif
                                                </div>
                                                <div class="col-10" style="padding:0">
                                                @if($rItem->Mitem)
                                                    <div><strong style="font-size: 12px;line-height: 14px;display: block;">{{ $rItem->Mitem->product_name }}</strong></div>
                                                
                                                    <small>
                                                        @if($rItem->Mitem->color)
                                                        Color: {{ $rItem->Mitem->color }}, 
                                                        @endif
                    
                                                        @if($rItem->Mitem->size)
                                                        Size: {{ $rItem->Mitem->size }},
                                                        @endif
                                                    </small>
                                                    <!--@if($rItem->Mitem->order)-->
                                                    <!--<a href="{{ route('customer.orderDetails',$rItem->Mitem->order_id) }}">#{{$rItem->Mitem->order->invoice}}</a>-->
                                                    <!--@endif-->
                                            @endif <br>
                                            {{App\Models\General::first()->currency}} {{ number_format($rItem->return_price,0) }}<br>
                                            <span><b>Qty:</b></span> {{$rItem->return_quantity}}  
                                            <b>Status:</b> 
                                             @if($rItem->status=='confirmed')
                                            <span class="badge badge-info" style="background: #3f51b5;">{{ucfirst($rItem->status)}}</span>
                                            @else
                                            <span class="badge badge-info" style="background: #ff9800;">{{ucfirst($rItem->status)}}</span>
                                            @endif
                                            <br>
                                            </div>
                                            </div>
                                            </td>
                                            <td style="font-size: 12px;">
                                            <span><b>Type:</b></span> 
                                           @if($rItem->reasion=='Return With Replace')
                                           <span class="badge badge-success" style="background-color: #e91e63;">{{$rItem->reasion}}</span>
                                           @else
                                           <span class="badge badge-success" style="background-color: #f44336;">{{$rItem->reasion}}</span>
                                           @endif<br>
                                            <span>{{$rItem->description}}</span>
                                            </td>
                                        
                                        </tr>
            
                                        @endforeach
            
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        
                        @if($order->order_status=='delivered' && $order->delivered_at > Carbon\Carbon::now()->subDays(7))
                        @if($item->quantity-$item->total_return > 0)
                        <div class="form-group">
                            <label>Write You Message</label>
                             @if ($errors->has('message'))
                                <p style="color: red;margin: 0;">{{ $errors->first('message') }}</p>
                              @endif
                            <textarea class="form-control" rows="5" name="message" required=""></textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success" type="submit">Submit</button>
                        </div>
                        @endif
                        @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
@endpush