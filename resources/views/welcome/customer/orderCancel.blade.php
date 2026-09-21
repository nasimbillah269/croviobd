@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>Order Cancel | {{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
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
                        <p style="font-weight: bold;border-bottom: 1px solid #eaeded;padding: 5px 0;">Orders Cancel
                        <a class="btn btn-sm btn-info float-right" href="{{route('customer.orderDetails',$order->id)}}">Back Order</a>
                        </p>
                        <form action="{{route('customer.orderCancelPost',$order->id)}}" method="post">
                            @csrf
                            
                            @if ($errors->has('itemId'))
                              <p style="color: red;margin: 0;">{{ $errors->first('itemId') }}</p>
                            @endif
                        <table class="table table-bordered">
                            <tr>
                               
                                <th>Item Description</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                        
                            </tr>
                            @foreach($order->items as $i=>$item)
                        <tr>
                            <td style="min-width:250px;">
                                <div class="row" style="margin:0;">
                                    <div class="col-2" style="padding:0px 5px;">
                                        @if($item->product)
                                            <img src="{{asset($item->product->fi())}}" style="max-width:50px;width:100%;">
                                        @endif
                                    </div>
                                    <div class="col-10" style="padding:0">
                                        <div><strong style="font-size: 12px;line-height: 14px;display: block;">{{ $item->product_name }}</strong></div>
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


                                
                                
                                @if($order->order_status=='delivered')
                                <a href="{{ route('customer.orderReview',$item->id) }}" style="color: #ff9800;font-weight: bold;font-size: 14px;">Give a Rivew</a>
                                @if ($item->product && $item->product->product_key)
                                    <br><span style="color: #009688;font-weight: bold;"><b>Product Key: </b> {{$item->product->product_key}}</span>
                                @endif
                                @endif
                                    </div>
                                </div>
                                </td>
                                <td style="min-width:100px;text-align: center;">{{ number_format($item->price,0) }}</td>
                                <td style="min-width:120px;text-align: center;">{{ number_format($item->total_deal_discount + $item->total_coupon_discount,0) }}</td>
                                <td style="min-width:100px;text-align: center;">{{ number_format($item->quantity,0) }}</td>
                                <td style="min-width:100px;text-align: right;">{{ number_format($item->final_price,0) }}</td>
                            </tr>

                            @endforeach
                            
                        </table>
                        <div class="form-group">
                        <label>Reasion</label>
                        @if ($errors->has('reason'))
                              <p style="color: red;margin: 0;">{{ $errors->first('reason') }}</p>
                            @endif
                         <select class="form-control" name="reason" required="">
                                        <option>Change/combine order</option>
                                        <option>Delivery time is too long</option>
                                        <option>Duplicate order</option>
                                        <option>Change of Delivery Address</option>
                                        <option>Shipping Fees</option>
                                        <option>Change of mind</option>
                                        <option>Forgot to use Coupon issue</option>
                                        <option>Decided for alternative product</option>
                                        <option>Found cheaper elsewhere</option>
                                        <option>Change payment method</option>
                                    </select>
                        </div>
                        <div class="form-group">
                            <label>Write You Message</label>
                            @if ($errors->has('message'))
                              <p style="color: red;margin: 0;">{{ $errors->first('message') }}</p>
                            @endif
                            <textarea class="form-control" rows="5" name="message" required="" ></textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success" type="submit">Submit</button>
                        </div>
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