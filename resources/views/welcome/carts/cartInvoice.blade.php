@extends(general()->theme.'.layouts.app') @section('title')
<title>{{general()->title}} | {{general()->subtitle}}</title>
@endsection @section('SEO')
<meta name="description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta property="og:title" content="{{general()->meta_title}}" />
<meta property="og:description" content="{!!general()->meta_description!!}" />
<meta property="og:image" content="{!!general()->meta_description!!}" />
<meta property="og:url" content="{{route('index')}}" />
@endsection 

@push('css')

@endpush 

@section('contents')

<div class="main-home-page">
    <div class="container">
    	<div class="cart-page">
    		
    		
    		<div class="Invoice-table">

    		   <div class="row" style="margin: 0;">
                <div class="col-lg-2 ">
                   
                </div>
                <div class="col-lg-12" style="padding:0;"> 
                        
                        
                @include(App\Models\General::first()->theme.'.alerts')
                <p class="text-center"><span id="PrintAction" class="btn btn-sm btn-success">Print</span></p>
                <div class="invoicePage PrintAreaContact" style="overflow:auto;">
                	<div class="container invoiceContainer" style="min-width: 650px;">
                	    <style type="text/css">
                            .invoice-inner {
                                box-shadow: 0px 0px 5px #ccc;
                                padding: 10px 20px;
                            }
                            
                            .invoice-header {
                                padding: 20px 0px 35px;
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
                            .infoTable tr td{
                                padding: 1px 5px;
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
                		<div class="invoice-inne" >
                			<div class="invoice-header">
                				<div class="row">
                					<div class="col-4">
                						<img src="{{asset(general()->logo())}}" style="max-height:80px;">
                					</div>
                					<div class="col-3"></div>
                					<div class="col-5" style="text-align: end;">
                						<h6>CONTACT INFORMATION:</h6>
                						<p>{!!general()->address_one!!}</p>
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
                				<div class="col-5 mt-3" style="flex: 0 0 41.666667%;max-width: 41.666667%;margin-top: 1rem;">
                					<p style="margin: 0;line-height: 15px;font-size: 12px;">Order From:</p>
                
                					<table class="table table-striped infoTable">
                					    <tr>
                					        <td><b>Name:</b> {{$order->name}}</td>
                					    </tr>
                					    <tr>
                					        <td><b>Mobile:</b> {{$order->mobile}}</td>
                					    </tr>
                					    <tr>
                					        <td><b>Address:</b> {{$order->address}}</td>
                					    </tr>
                					    <tr>
                					        <td><b>Area:</b> {{$order->area_name}}</td>
                					    </tr>
                					 
                					</table>
                				</div>
                				<div class="col-2 mt-3" style="flex: 0 0 16.666667%;max-width: 16.666667%;margin-top: 1rem;">
                					
                				</div>
                				<div class="col-5" style="flex: 0 0 41.666667%;max-width: 41.666667%;">
                					<div class="ordrinfotable" style="border: 1px solid #ccc;padding: 10px 12px;">
                						<table class="tableOrderinfo table">
                						  <thead>
                						    <tr>
                						      <td style="width: 40%;padding: 0;font-size: 13px;line-height: 17px;border: none;">Order Number</td>
                						      <td style="padding: 0;font-size: 13px;line-height: 17px;border: none;">: {{ $order->invoice}}</td>
                						    </tr>
                						    <tr>
                						      <td style="width: 40%;padding: 0;font-size: 13px;line-height: 17px;border: none;">Order Date</td>
                						      <td style="padding: 0;font-size: 13px;line-height: 17px;border: none;">: {{ $order->created_at->format('d-m-Y h:i A') }}</td>
                						    </tr>
                						    <tr>
                						      <td style="width: 40%;padding: 0;font-size: 13px;line-height: 17px;border: none;">Payment Method</td>
                						      <td style="padding: 0;font-size: 13px;line-height: 17px;border: none;">: Cash On Delivery</td>
                						    </tr>
                						    <tr>
                						      <td style="width: 40%;padding: 0;font-size: 13px;line-height: 17px;border: none;">Order Status</td>
                						      <td style="padding: 0;font-size: 13px;line-height: 17px;border: none;">: {{ucfirst($order->order_status)}}</td>
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
            						      <td style="width: 12%; text-align: center;">Quantity</td>
            						      <td style="width: 12%; text-align: center;">Unit Price</td>
            						      <td style="width: 15%; text-align: center;">Total Price</td>
            						    </tr>
            						  </thead>
            						  <tbody>
            						      
            						    @foreach($order->items as $i=>$item)
            						    <tr>
            						      <td>{{ $item->product_name }}</td>
            						      <td style="text-align: center;">{{ priceFormat($item->price) }}</td>
            						      <td style="text-align: center;">{{$item->quantity}}</td>
            						      <td style="text-align: center;">{{ priceFormat($item->final_price) }}</td>
            						    </tr>
            						    @endforeach
            						    
            						    <tr>
            						      <td colspan="3" style="text-align: end;">Subtotal</td>
            						      <td style="text-align: center;">{{ priceFormat($order->total_price) }}</td>
            						    </tr>
            						    @if($order->tax >0)
            						    <tr>
            						      <td colspan="3" style="text-align: end;">Tax</td>
            						      <td style="text-align: center;">{{ priceFormat($order->tax) }}</td>
            						    </tr>
            						    @endif
            						    <tr>
            						      <td colspan="3" style="text-align: end;">Shipping</td>
            						      <td style="text-align: center;">
            						      @if($order->shipping_charge>0)
            						      {{priceFormat($order->shipping_charge)}}
            						      @else
            						      Free Shipping
            						      @endif
            						      </td>
            						    </tr>

            						    <tr>
            						      <td colspan="3" style="text-align: end;">Grand Total</td>
            						      <td style="text-align: center;">{{ priceFormat($order->grand_total) }}</td>
            						    </tr>
            						  </tbody>
            						</table>
                    			</div>
                			</div>
        
                			<div class="frozenTable">

                				<div class="row" style="display:flex;">
                				    <div class="col-8" style="flex: 0 0 66.666667%;max-width: 66.666667%;">
                				        @if($order->note)
                				        <b>Order Note</b><br>
                    				    <p>{!!$order->note!!}</p>
                				        @endif
                				    </div>
                					<div class="col-2" style="flex: 0 0 16.666667%;max-width: 16.666667%;">
                					</div>
                					<div class="col-md-2" style="flex: 0 0 16.666667%;max-width: 16.666667%;">
                					    @if($order->payment_status=='paid')
                					    <div class="paidsStatus" style="text-align:center;">
                					        <img src="{{asset('medies/paid.png')}}" style="max-width:80px;">
                					    </div>
                					     @endif
                					</div>
                				</div>
                			</div>
        
                			<div class="footerInvoice">
                				<div class="row" style="dispaly:flex;">
                					<div class="col-6" style="flex: 0 0 50%;max-width: 50%;">
                						<p>Thank you for shopping from {{general()->title}}</p>
                					</div>
                					<div class="col-6" style="text-align: end;flex: 0 0 50%;max-width: 50%;">
                						------------------------
                						<p>Authorised Sign</p>
                					</div>
                				</div>
                			</div>
                		</div>
                	</div>
                </div>
                        
                        
                        
                        
        		</div>
                </div>
    		
    	</div>
    </div>
</div>



@endsection 

@push('js') 

<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js"></script>
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

    <script>


    window.dataLayer = window.dataLayer || [];
    
        dataLayer.push({
            event: "purchase",
            ecommerce: {
                currency: "{{ general()->currency }}",
                value: {{ round($order->grand_total)}},
                vat: {{ round($order->tax ?? 0) }},
                shipping: {{ round($order->shipping_charge ?? 0) }},
                discount: {{ round($order->coupon_discount ?? 0) }},
                transaction_id: {{ $order->invoice}},
                items: [
                    @foreach($order->items as $item)
                    {
                        @if($product =$item->product)
                        item_id: "{{$product->id}}",
                        item_name: "{{ $product->name }}",
                        item_category: "{!! implode(' - ', $product->productCategories->pluck('name')->toArray()) !!}",
                        item_brand: "{{ $product->brand ? $product->brand->name : '' }}",
                        price: "{{ round($item->price) }}",
                        quantity: "{{ $item->quantity }}",
                    
                        @endif
                    }@if(!$loop->last),@endif
                    @endforeach
                ]
            },
            user_data: {
            name: "{{ $order->name }}",
            email: "{{ $order->email }}",
            mobile: "{{ $order->mobile }}",
            address: "{{ $order->address}}",
            external_id:"{{ $order->id}}",
            country: "BD"
        }
    });

    console.log('purcehase')

</script>



<script src="{{asset('app-assets/js/printThis.js')}}"></script>

<script type="text/javascript">
	$('#PrintAction').on("click", function () {
        $('.PrintAreaContact').printThis();
      });
</script>

@endpush