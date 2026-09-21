<div class="table-responsive">
	                              
		<table class="table table-bordered">
			<tr>
				<th style="min-width:100px;width:100px;">Image</th>
				<th>Product Name</th>
				<th style="min-width:100px;width:100px;">Stock</th>
				<th style="min-width:150px;width:150px;">Quantity</th>
				<th style="min-width:150px;width:150px;">Purchase Price</th>
				<th style="min-width:150px;width:150px;">Total Price</th>
				<th style="min-width:60px;width:60px;">
					<i class="fa fa-trash"></i>
				</th>
			</tr>
			@foreach($order->items as $item)
			<tr>
				<td>
					@if($item->product)
					<img src="{{asset($item->product->image())}}" style="max-width: 60px;max-height: 35px;">
					@endif
				</td>
				<td>
				    {{$item->product_name}}
				@if($item->product)
				    {{$item->product->bar_code?:''}}
				@endif
				</td>
				<td>
				@if($item->product)
				    {{$item->product->quantity?:0}}
				@endif
				</td>
				<td style="padding:3px;">
					<input type="text"  value="{{$item->quantity?:''}}"
					

					data-type="itemQty" data-id="{{$item->id}}" class="form-control form-control-sm itemUpdatePurchase" placeholder="Enter Quantity" style="text-align: center;">
				</td>
				<td style="padding:3px;">
					<input type="text"  value="{{$item->price?:''}}" data-type="itemPrice" data-id="{{$item->id}}" class="form-control form-control-sm itemUpdatePurchase" placeholder="Enter Price" style="text-align: center;">
				</td>
				<td style="text-align: center;">{{priceFormat($item->total_price)}}</td>
				<td style="text-align: center;">
				    @if($item->product)
				        
				        @if($item->quantity > $item->product->quantity?:0)  @else
				        <span class="badge badge-danger itemRemovePurchase" data-type="itemRemove" data-id="{{$item->id}}" style="cursor:pointer;"> <i class="fa fa-trash"></i> </span>
				        @endif
    				   
    				@else
    				<span class="badge badge-danger itemRemovePurchase" data-type="itemRemove" data-id="{{$item->id}}" style="cursor:pointer;"> <i class="fa fa-trash"></i> </span>
    				@endif
					
				</td>
			</tr>
			@endforeach
			<tr>
				<th colspan="5" style="text-align: right;">Total Amount</th>
				<td style="text-align: center;">
					{{priceFormat($order->total_price)}}
				</td>
				<td></td>
			</tr>
			<tr>
				<th colspan="5" style="text-align: right;">Paid Amount</th>
				<td style="text-align: center;">
					{{priceFormat($order->paid_amount)}}
				</td>
				<td></td>
			</tr>
			<tr>
				<th colspan="5" style="text-align: right;">Due Amount</th>
				<td style="text-align: center;">
					{{priceFormat($order->due_amount)}}
				</td>
				<td></td>
			</tr>
			@if($order->extra_amount)
			<tr>
				<th colspan="5" style="text-align: right;">Return Amount</th>
				<td style="text-align: center;">
					{{priceFormat($order->extra_amount)}}
				</td>
				<td></td>
			</tr>
			@endif
		</table>
	</div>

	<div class="row">
		<div class="col-md-8">
		    <h3>Add Payment</h3>
		    <form class="PaymentAddform">
                <div class="row" style="margin:0;">
                    <div class="col-md-3" style="padding:5px;">
                        <select class="form-control form-control-sm paymentMethod" name="type">
                            @foreach($methods as $method)
                               <option value="{{$method->id}}" >{{$method->name}} </option>
                            @endforeach
                        </select>
                        <span class="text-danger paymentMethodError"></span>
                    </div>
                    <div class="col-md-3" style="padding:5px;">
                        <select class="form-control form-control-sm paymentOption" name="method">
                           @if($methodOption =$methods->first())
                           @foreach($methodOption->methodOptions as $option)
                           <option value="{{$option->id}}">{{$option->name}}</option>
                           @endforeach
                           @endif
                        </select>
                        <span class="text-danger paymentOptionError"></span>
                    </div>
                    <div class="col-md-3" style="padding:5px;">
                        <input type="number" class="form-control form-control-sm paymentAmount" name="amount" step="0.01" value="{{$order->due_amount?filter_var(priceFormat($order->due_amount), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION):''}}" placeholder="0" style="text-align:center;">
                        <span class="text-danger paymentAmountError"></span>
                    </div>
                    <div class="col-md-3" style="padding:0px;">
                        <span class="btn btn-md btn-success PaymentAddformSubmit" data-type="addPayment"><i class="fa fa-check"></i> Confirmed</span>
                    </div>
                </div>
                </form>
			<div class="table-responsive">
		       <table class="table table-bordered areaChargeTable">
		           <thead>   
		               <tr>
		                   <th style="min-width: 50px;width: 50px;">SL</th>
		                   <th style="min-width: 200px;width: 200px;">Method</th>
		                   <th style="min-width: 150px;width: 150px;">Amount</th>
		                   <th style="min-width:80px;width:80px;text-align: center;">
		                    
		                   </th>
		               </tr>
		           </thead>
		           <tbody class="PaymentRow">
		            @foreach($order->transections as $i=>$transection)
		               <tr>
		                   <td>
		                      {{$i+1}}
		                   </td>
		                   <td>
		                       {{$transection->methodOption?$transection->methodOption->name:'Not Found'}}
		                   </td>
		                   <td>
                                {{priceFullFormat($transection->amount)}}
		                   </td>
		                   <td style="text-align: center;">
		                       <span class="btn btn-sm btn-danger PaymentDelete" data-id="{{$transection->id}}" data-type="deletePayment"><i class="fa fa-trash"></i></span>
		                   </td>
		               </tr>

		            @endforeach
		           </tbody>

		       </table>

		   </div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label>Order Status</label>
				<select class="form-control" name="status" required="">
					<option value="confirmed" {{$order->order_status=='confirmed'?'selected':''}}>Confirmed</option>
				</select>
			</div>
			@if($order->items->count()>0)
			<div class="form-group">
				<button type="sumit" class="btn btn-block btn-success" >Submit</button>
			</div>
			@endif
		</div>
	</div>