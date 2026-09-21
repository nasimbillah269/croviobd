@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>Order Payment - {{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!App\Models\General::latest()->first()->meta_description!!}">
    <meta name="keywords" content="{{App\Models\General::latest()->first()->meta_keyword}}">
    <meta property="og:title" content="Order Payment - {{App\Models\General::latest()->first()->title}}">
    <meta property="og:description" content="{!!App\Models\General::latest()->first()->meta_description!!}">
    <meta property="og:image" content="{{asset(App\Models\General::first()->logo?:'admin-assets/img/logo.png')}}">
    <meta property="og:url" content="{{route('orderPayment',$order->id)}}">
@endsection
@push('css')


@endpush
@section('contents')



<div class="container">
    

<div class="fullcartdiv cart-all-div" style="min-height: 450px;background: white;margin: 10px 0;">

    <div class="row" style="margin:0;">

        <div class="col-md-12 cart-table-div">
            <h4 style="text-align: center;color: #099b09;padding: 10px;margin: 0;">{{session()->get('locale')=='bn'?'কার্ট দেখুন':'ORDER PAYMENT'}} ({{__('lang.currency')}} {{number_format($order->due_amount)}})</h4>
            <div class="row" style="padding-top: 10px;border-top: 1px solid #e7e8e9;">
            	<div class="col-md-2"></div>
            	<div class="col-md-8">
            		
            		<ul class="nav nav-tabs">
                    
					
					
                    @if(App\Models\General::first()->handcash_payment)
					  <li class="nav-item">
					    <a class="nav-link {{$active=='handcash_payment'?'active':''}}" data-toggle="pill" href="#hc">Cash on Delivery</a>
					  </li>
                    @endif
                    
                    @if(App\Models\General::first()->wallet_payment)
					  <li class="nav-item">
					    <a class="nav-link {{$active=='wallet_payment'?'active':''}}" data-toggle="pill" href="#balance">Wallet</a>
					  </li>
					@endif
                    
                    @if(App\Models\General::first()->online_payment)
					  <li class="nav-item">
					    <a class="nav-link {{$active=='online_payment'?'active':''}}" data-toggle="pill" href="#online">Online Payment</a>
					  </li>
                    @endif
					</ul>

					<div class="tab-content">
 
						<div class="tab-pane container {{$active=='online_payment'?'bg-white active':'fade'}}" id="online">
						    <div class="p-5">
						        <img src="{{asset('medies/SS-Commerz-Pay-With-logo.png')}}" width="100%"> <br><br>
						       <button class="btn" id="sslczPayBtn" token="5" postdata="" order="5" endpoint="/pay-via-ajax" style="background: #f32525;border-color: #f32525;color: white;"> Pay Online Now
						        </button>
						    </div>
						</div>

						<div class="tab-pane container {{$active=='wallet_payment'?'bg-white active':'fade'}}" id="balance">
						    <div class="p-3 bg-white">
						      	<div class="mybalanetotal">
						          <span>{{App\Models\General::first()->currency}} {{Auth::user()->balance}}</span>
							      <br>
                                  @if($order->due_amount > Auth::user()->balance)
							      <button class="btn btn-sm btn-warning">You have no enough balance</button> <br>
							      <a class="btn btn-link" href="{{route('customer.myBalance')}}">Add Balance</a><br><br>
							      @else
                                  <a class="btn btn-xs text-center" href="{{route('orderPaymentSend',['wallet',$order->id])}}"  style="display: inline-block;background: #f32525;border-color: #f32525;color: white;">Payment My Wallet</a>
							      @endif
                                </div>
						    </div>
						</div>

						<div class="tab-pane container {{$active=='handcash_payment'?'bg-white active':'fade'}}" id="hc">
							<div class="p-5 bg-white">
							    <p>Hand Cash Payment want to pay pleace click to cash on delivery</p>
							      <br>
							    <a class="btn btn-xs text-center" href="{{route('orderPaymentSend',['handcash',$order->id])}}" style="background: #f32525;border-color: #f32525;color: white;">Cash on Delivery</a>
							</div>
						</div>
						
					</div>










            	</div>
            	<div class="col-md-2"></div>
            </div>

        </div>

    </div>

</div>

</div>

    
@endsection
@push('js')
@endpush