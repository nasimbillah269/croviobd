@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>{{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
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

    .statustimeline {
    text-align: center;
    padding: 15px 0px 10px;
    position: relative;
    }
    .statustimeline ul {
    padding: 0;
    list-style: none;
    margin: 0;
    position: relative;
    }
    .statustimeline ul li {
    width: 23%;
    text-align: center;
    display: inline-block;
    }
    .statustimeline ul li span {
    display: block;
    font-size: 14px;
    }
    .statustimeline ul li span.statuscheckok i {
    background: #04a150;
    padding: 5px;
    border-radius: 50%;
    color: white;
    min-width: 26px;
    margin: 0 4px;
    }
    
    .statustimeline:before {
    content: '';
    top: 28px;
    position: absolute;
    background: #eaeded;
    width: 100%;
    left: 0;
    z-index: 0;
    height: 2px;
    }


@media only screen and (max-width: 567px){
    
    .statustimeline ul li span {
        display: block;
        font-size: 10px;
    }
    
    .statustimeline ul li span.time {
        font-size: 5px;
    }
    
    .statustimeline ul li span.statuscheckok i {
        min-width: 20px;
    }
     .invoiedetailtitle{
       font-size:12px;
    }
    
    
}

</style>

@endpush
@section('contents')


<div class="row" style="background: #e7e7e7;margin: 0;">
    <div class="col-md-12" style="background: white;border-right: 1px solid #c6c6c6;border-bottom: 1px solid #c6c6c6;">
        <p style="margin: 0;cursor: pointer;padding: 5px;">
           <a style="text-decoration: none;color: gray;font-size: 14px;" href="javascript:void(0)">Order Track</a> 
        </p>
    </div>
</div>


<div style="background:white;margin: 20px 0;">

    <div class="row">
        <div class="col-md-3">
            
        </div>
        <div class="col-md-6">
            <div class="seller-serach">
                <h3>Order Tracking</h3>
                <p>Track you order input your order number here..</p>
                <form action="{{route('OrderTrack')}}">
                <div class="input-group mb-3 headersearch">
                  <input type="text" class="form-control" name="invoice" value="{{$r->invoice?:old('invoice')}}" placeholder="Enter Your Invoice Number here.." required="">
                  <div class="input-group-append">
                      <button class="btn" type="submit" style="background: #04a150;color: white;"><i class="fa fa-search"></i> Find Order</button>
                  </div>
                </div>
                </form>
                
            </div>
        </div>
        <div class="col-md-3">
            
        </div>
    </div>

</div>

<div style="background:white;margin: 20px 0;min-height:250px;">

    <div class="row">
        <div class="col-md-3">
            
        </div>
        <div class="col-md-6">
            <div class="order-serach">
               
               @if($order)
               
               <div class="myrecentorder" style="margin-bottom:10px;">  

                 <div class="statustimeline">
                    @if($order->payment_method==null)
                    @include(App\Models\General::first()->theme.'.customer.includes.orderpaydmentpending')
                    @else
                     
                    @if($order->order_status=='pending')
                        @include(App\Models\General::first()->theme.'.customer.includes.orderpendingstatus')
                    @elseif($order->order_status=='confirmed')
                        @include(App\Models\General::first()->theme.'.customer.includes.orderconfirmstatus')
                    @elseif($order->order_status=='shipped')
                        @include(App\Models\General::first()->theme.'.customer.includes.ordershipedstatus')
                    @elseif($order->order_status=='delivered')
                        @include(App\Models\General::first()->theme.'.customer.includes.orderdeliveredstatus')
                    @elseif($order->order_status=='Cancelled')
                        @include(App\Models\General::first()->theme.'.customer.includes.ordercancelstatus')
                    @endif
                    @endif
                </div>
                
                <center>
                    <a href="{{ route('customer.orderDetails',$order->id) }}" class="btn btn-sm btn-success" style="background: #bf203d;border-color: #bf203d;"> View Invoice</a>
                </center>
                </div>
               
               @else
               
               <center style="padding: 20px;color: gray;">
                   <h4>No Order Found</h4>
               </center>
               
               @endif
                
            </div>
        </div>
        <div class="col-md-3">
            
        </div>
    </div>

</div>


    
@endsection
@push('js')
@endpush