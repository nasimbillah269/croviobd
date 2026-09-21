<div class="invoiceContainer">
	<div class="invoice-inner InnerInvoiePage" >
		<div class="invoice-header">
			<div class="row">
				<div class="col-5">
					<img src="{{asset(general()->logo())}}" style="max-height: 60px;">
				</div>
				<div class="col-2" style="text-align:center;">
				   
				</div>
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
				      <td style="width: 12%; text-align: center;">Unit Price</td>
				      <td style="width: 12%; text-align: center;">Quantity</td>
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
				      <td colspan="3"  style="text-align: end;">Subtotal</td>
				      <td style="text-align: center;">{{ priceFormat($order->total_price) }}</td>
				    </tr>
				    @if($order->tax > 0)
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