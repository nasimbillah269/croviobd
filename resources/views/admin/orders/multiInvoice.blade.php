@extends('admin.layouts.app') @section('title')
<title>Multi Invoices - {{general()->title}} | {{general()->subtitle}}</title>
@endsection @push('css')


<style type="text/css">
    .invoiceContainer {
        overflow: auto;
    }
    
    .invoice-inner.InnerInvoiePage {
        min-width: 600px;
    }

    .invoice-inner {
        /*box-shadow: 0px 0px 5px #ccc;*/
        padding: 10px 20px;
        overflow: auto;
        min-width: 600px;
    }
    
    .invoice-header {
        padding: 20px 0px 35px;
    }
    
    .invoice-header img{
        width: 100%;
    }
    
    .invoice-header h6{
        margin-top: 15px!important;
    }
    
    .invoice-header h6, p{
        margin: 0;
        line-height: 15px;
        font-size: 12px;
    }
    
    .invoice-inner h2{
        margin: 10px 0px;
        font-size: 41px;
        letter-spacing: 3px;
        color: #00549e;
    }
    
    .ordrinfotable {
        padding: 10px 12px;
        border: 1px solid #ccc;
    }
    
    table.tableOrderinfo.table {
        margin: 0;
        padding: 0;
    }
    
    .tableOrderinfo td{
        padding: 0;
        font-size: 13px;
        line-height: 17px;
        border: none;
    }
    
    .mainTable{
        margin: 30px 0;
    }
    
    .mainproducttable{
        margin: 0;
        padding: 0;
        width: 100%;
    }
    
    .mainproducttable td{
        padding: 5px 7px;
        font-size: 12px;
        border: 1px solid #ccc;
    }
    
    tr.headerTable {
        background-color: #e2e2e2;
    }
    
    tr.headerTable td{
        font-size: 13px;
        padding: 7px;
    }
    
    .boxFrozen {
        border: 1px solid #ccc;
        text-align: center;
        margin-bottom: 6px;
        border-bottom: 0px solid #ccc;
    }
    
    .boxFrozen h3{
        padding: 5px;
        color: #fff;
        margin: 0;
        background-color: #ff1414;
        font-size: 16px;
    }
    
    .boxFrozen p{
        font-size: 16px;
        padding: 5px 0px;
        border-bottom: 1px solid #ccc;
    }
    
    .footerInvoice{
        margin-top: 100px;
    }

    @media only screen and (max-width: 567px) {
        .invoice-inner {
            padding: 10px;
            margin: 10px 0px;
        }
        .invoiceContainer{
            padding:0;
        }
    }
</style>

@endpush @section('contents')


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Multi Invoices</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Multi Invoices</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">

            <a class="btn btn-outline-primary" href="{{route('admin.orders')}}">Back</a>
            <button class="btn btn-success" id="PrintAction" ><i class="fa fa-print"></i> Print</button>
            <a class="btn btn-outline-primary" href="{{route('admin.multiInvoicesView')}}">
                <i class="fa-solid fa-rotate"></i>
            </a>
        </div>
    </div>
</div>

<div class="content-body">
    <!-- Basic Elements start -->
    <section class="basic-elements">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-9">
                <div class="card">

            	   <div class="invoice-inner invoicePage PrintAreaContact">
                        
                        @foreach($orders as $order)
                        <div class="invoiceContainer">
	                    <div class="invoice-inner InnerInvoiePage" >
                			<div class="invoice-header">
                				<div class="row">
                					<div class="col-4">
                						<img src="{{asset(general()->logo())}}">
                					</div>
                					<div class="col-1"></div>
                					<div class="col-7" style="text-align: end;">
                						<h6>CONTACT INFORMATION:</h6>
                						<p>{{general()->address_one}}</p>
                						<p>{{general()->mobile}}</p>
                						<p>{{general()->website}}</p>
                						<p>{{general()->email}}</p>
                					</div>
                				</div>
                			</div>
                			<hr style="border: 2px solid #00549e; margin: 0;">
                			<h2 style="margin: 10px 0px;font-size: 41px;letter-spacing: 3px;color: #00549e;">INVOICE</h2>
                			<div class="orderInfo">
                				<div class="row" style="flex-wrap: wrap;">
                					<div class="col-3 mt-1" style="flex: 0 0 25%;max-width: 25%;margin-top: 1rem;">
                						<p style="margin: 0;line-height: 15px;font-size: 12px;">Order From:</p>
                						@if($order->user)
                						<p style="margin: 0;line-height: 15px;font-size: 12px;">{{ $order->user->name}}</p>
                						<p style="margin: 0;line-height: 15px;font-size: 12px;">{{ $order->user->mobile}}</p>
                						<p style="margin: 0;line-height: 15px;font-size: 12px;">{{ $order->user->email}}</p>
                						<p style="margin: 0;line-height: 15px;font-size: 12px;">{{ $order->user->fullAddress() }}</p>
                						@endif
                					</div>
                					<div class="col-4 mt-1" style="flex: 0 0 33.333333%;max-width: 33.333333%;margin-top: 1rem;">
                						<p style="margin: 0;line-height: 15px;font-size: 12px;">Delivery To:</p>
                						
                						<p style="margin: 0;line-height: 15px;font-size: 12px;">
                						@if($order->name)
                						{{$order->name}}
                						@else
                						{{$order->user?$order->user->name:''}}
                						@endif
                						</p>
                						<p style="margin: 0;line-height: 15px;font-size: 12px;">
                						    	@if($order->mobile)
                        						{{$order->mobile}}
                        						@else
                        						{{$order->user?$order->user->mobile:''}}
                        						@endif
                						 </p>
                						<p style="margin: 0;line-height: 15px;font-size: 12px;">
                						        @if($order->email)
                        						{{$order->email}}
                        						@else
                        						{{$order->user?$order->user->email:''}}
                        						@endif
                						</p>
                						<p style="margin: 0;line-height: 15px;font-size: 12px;">{{$order->fullAddress()}}</p>
                					</div>
                					<div class="col-5" style="flex: 0 0 41.666667%;max-width: 41.666667%;">
                						<div class="ordrinfotable" style="border: 1px solid #ccc;padding: 10px 12px;">
                							<table class="tableOrderinfo table">
        									  <thead>
        									    <tr>
        									      <td style="width: 40%;padding: 0;font-size: 13px;line-height: 17px;border: none;">Invoice Number</td>
        									      <td style="font-size: 13px;line-height: 17px;border: none;">: {{ $order->invoice }}</td>
        									    </tr>
        									    <tr>
        									      <td style="width: 40%;padding: 0;font-size: 13px;line-height: 17px;border: none;">Invoice Date</td>
        									      <td style="padding: 0;font-size: 13px;line-height: 17px;border: none;">: {{ $order->created_at->format('d-m-Y h:i A') }}</td>
        									    </tr>
        									    <tr>
        									      <td style="width: 40%;padding: 0;font-size: 13px;line-height: 17px;border: none;">Order Number</td>
        									      <td style="padding: 0;font-size: 13px;line-height: 17px;border: none;">: {{ $order->id}}</td>
        									    </tr>
        									    <tr>
        									      <td style="width: 40%;padding: 0;font-size: 13px;line-height: 17px;border: none;">Order Date</td>
        									      <td style="padding: 0;font-size: 13px;line-height: 17px;border: none;">: {{ $order->created_at->format('d-m-Y h:i A') }}</td>
        									    </tr>
        									    <tr>
        									      <td style="width: 40%;padding: 0;font-size: 13px;line-height: 17px;border: none;">Payment Method</td>
        									      <td style="padding: 0;font-size: 13px;line-height: 17px;border: none;">: Cash On Delivery</td>
        									    </tr>
        									  </thead>
        									</table>
                						</div>							
                					</div>
                				</div>
                			</div>
                            
                            <div class="table-responsive">
                    			<div class="mainTable" style="margin: 30px 0;">
                    				<table class="table mainproducttable">
            						  <thead>
            						    <tr class="headerTable">
            						      <td style="width: 40%;">Product Name & Description</td>
            						      <td style="width: 10%; text-align: center;">Unit Size</td>
            						      <td style="width: 12%; text-align: center;">Quantity</td>
            						      <td style="width: 10%; text-align: center;">Total Unit</td>
            						      <td style="width: 12%; text-align: center;">Unit Price</td>
            						      <td style="width: 15%; text-align: center;">Total Price</td>
            						    </tr>
            						  </thead>
            						  <tbody>
            						      
            						    @foreach($order->items as $i=>$item)
            						    <tr>
            						      <td>{{ $item->product_name }}</td>
            						      <td style="text-align: center;">
            						      @if($item->product)
            						        {{$item->product->productWeight()}}
            						      @endif
            						      </td>
            						      <td style="text-align: center;">{{$item->quantity}}</td>
            						      <td style="text-align: center;">
            						          @if($item->product)
                						        {{$item->productWeightTotal()}}
                						      @endif
            						      </td>
            						      <td style="text-align: center;">{{ priceFormat($item->price) }}</td>
            						      <td style="text-align: center;">{{ priceFormat($item->final_price) }}</td>
            						    </tr>
            						    @endforeach
            						    
            						    <tr>
            						      <td colspan="2" style="text-align: end;">Product Qty</td>
            						      <td style="text-align: center;">{{$order->items->sum('quantity')}}</td>
            						      <td style="text-align: center;"></td>
            						      <td style="text-align: end;">Subtotal</td>
            						      <td style="text-align: center;">{{ priceFormat($order->total_price) }}</td>
            						    </tr>
            						    <tr>
            						      <td colspan="5" style="text-align: end;">Tax</td>
            						      <td style="text-align: center;">{{ priceFormat($order->tax) }}</td>
            						    </tr>
            						    <tr>
            						      <td colspan="5" style="text-align: end;">Shipping</td>
            						      <td style="text-align: center;">
            						      @if($order->shipping_charge>0)
            						      {{priceFormat($order->shipping_charge)}}
            						      @else
            						      Free Shipping
            						      @endif
            						      </td>
            						    </tr>
            						    <tr>
            						      {{--<td colspan="3">In words: <span id="inWordTotal" data-amount="{{$order->grand_total}}"></span></td>--}}
            						      <td colspan="5" style="text-align: end;">Grand Total</td>
            						      <td style="text-align: center;">{{ priceFormat($order->grand_total) }}</td>
            						    </tr>
            						  </tbody>
            						</table>
                    			</div>
                			</div>
        
                			<div class="frozenTable">
                				<div class="row" style="display:flex;">
                				    <div class="col-5" style="flex: 0 0 41.666667%;max-width: 41.666667%;">
                				        <div class="boxFrozen" style="border: 1px solid #ccc;text-align: center;margin-bottom: 6px;border-bottom: 0px solid #ccc;">
                							<h3 style="padding: 5px;color: #fff;margin: 0;background: #ff1414;font-size: 16px;-webkit-print-color-adjust: exact; ">
                							    @if($order->product_type==1)
                							    <span>FROZEN</span>
                							    @elseif($order->product_type==2)
                							    <span>DRY</span>
                							    @else
                							    <span>MIX</span>
                							    @endif
                							     
                							    PRODUCTS
                							</h3>
                							<p style="font-size: 16px;padding: 5px 0px;border-bottom: 1px solid #ccc;">Delivery Date: 
                							@if($order->delivery_date==null || $order->delivery_date=='NaN/NaN/NaN')@else
                    						{{Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y')}}
                    						@endif
                							</p>
                							<p style="font-size: 16px;padding: 5px 0px;border-bottom: 1px solid #ccc;">Delivery Time: {{$order->delivery_time}}</p>
                						</div>
                				    </div>
                					<div class="col-5" style="flex: 0 0 41.666667%;max-width: 41.666667%;">
                						@if($order->TotalGramUnit() > 0 || $order->TotalLiterUnit() > 0)
                    				    <table class="table table-bordered">
                    				        <tr style="background-color: #ff1414;text-align: center;color: white;font-size: 16px;padding: 2px;-webkit-print-color-adjust: exact;">
                    				            <td colspan="2" style="padding: 0px;">
                    				            <h3 style="padding: 5px;color: #fff;text-align: center;margin: 0;background: #ff1414;font-size: 16px;-webkit-print-color-adjust: exact; ">
                    				            Total Unit
                    				            </h3>
                    				            </td>
                    				        </tr>
                    				        @if($order->TotalGramUnit() > 0 && $order->TotalLiterUnit() > 0)
                    				        <tr>
                    				            <td style="text-align: center;padding:4px;">
                    				                @if($order->TotalGramUnit() >= 1000 )
                        						    {{$order->TotalGramUnit()/1000}} Kgs
                        						    @else
                        						    {{$order->TotalGramUnit()}} gram
                        						    @endif
                    				            </td>
                    				            <td style="text-align: center;padding:4px;">
                    				                @if($order->TotalLiterUnit() >= 1000 )
                        						    {{$order->TotalLiterUnit()/1000}} Liters
                        						    @else
                        						    {{$order->TotalLiterUnit()}} ml
                        						    @endif
                    				            </td>
                    				        </tr>
                    				        @else
                    				        @if($order->TotalGramUnit() > 0)
                    				            <td style="text-align: center;padding:4px;">
                    				                @if($order->TotalGramUnit() >= 1000 )
                        						    {{$order->TotalGramUnit()/1000}} Kgs
                        						    @else
                        						    {{$order->TotalGramUnit()}} gram
                        						    @endif
                    				            </td>
                    				        @else
                    				            <td style="text-align: center;padding:4px;">
                    				                @if($order->TotalLiterUnit() >= 1000 )
                        						    {{$order->TotalLiterUnit()/1000}} Liters
                        						    @else
                        						    {{$order->TotalLiterUnit()}} ml
                        						    @endif
                    				            </td>
                    				        @endif
                    				        @endif
                    				    </table>
                    				    @endif
                						
                					</div>
                					<div class="col-md-2" style="flex: 0 0 16.666667%;max-width: 16.666667%;">
                					    @if($order->payment_status=='paid')
                					    <div class="paidsStatus" style="text-align:center;">
                					        <img src="{{asset('medies/paid.png')}}" style="max-width:80px;">
                					    </div>
                					     @endif
                					</div>
                					@if($order->note)
                    				<div class="col-12">
                    				    <b>Order Note</b><br>
                    				    <p>{!!$order->note!!}</p>
                    				</div>
                    				@endif
                    				
                				</div>
                			</div>
        
                			<div class="footerInvoice">
                				<div class="row" style="dispaly:flex;">
                					<div class="col-6" style="flex: 0 0 50%;max-width: 50%;">
                						<p>Thank you for shopping from Baticrom</p>
                					</div>
                					<div class="col-6" style="text-align: end;flex: 0 0 50%;max-width: 50%;">
                						------------------------
                						<p>Authorised Sign</p>
                					</div>
                				</div>
                			</div>
                		</div>
                        </div>
                        <div style="page-break-after:always"></div>
                        @endforeach
                    
                    
                    </div>
                    
                </div>

            </div>
        </div>
    </section>
    <!-- Basic Inputs end -->
</div>




@endsection 

@push('js') 

<script src="{{asset('batikrom/js/inword.js')}}"></script>

<script type='text/javascript'>
    
    $(document).ready(function(){
        
        var date = new Date();
        date.setDate(date.getDate() + 7);
        
        console.log(date);
        
        
        var words="";

        $(function() {
        	var totalamount = (
        		Number($('#inWordTotal').data('amount'))
        		);
        	words = toWords(totalamount);
        	$('#inWordTotal').empty().append(words + 'Taka only');
        });
        
    });
</script>

@endpush
