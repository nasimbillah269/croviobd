 <ul class="nav nav-tabs" role="tablist" style="border-bottom: none;">
     <li class="nav-item">
         <a class="nav-link active" id="baseIcon-tab11"  href="#tabIcon1" data-toggle="tab" aria-controls="tabIcon1" href="#tabIcon1" role="tab" aria-selected="true"><i class="fas fa-print"></i> POS Invoice</a>
     </li>
     <li class="nav-item">
         <a class="nav-link" id="baseIcon-tab12"  href="#tabIcon2" data-toggle="tab" aria-controls="tabIcon2" href="#tabIcon2" role="tab" aria-selected="false"><i class="fas fa-print"></i> Invoice</a>
     </li>
    
 </ul>

 <div class="tab-content px-1 pt-1" style="border: 1px solid #ddd;">
     
     <div class="tab-pane active" id="tabIcon1" role="tabpanel" aria-labelledby="baseIcon-tab11">
         
         <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                    	<button class="btn btn-sm btn-success"><i class="fa fa-print"></i> Click Print</button>
                        <div class="posinvoice-inner">
                            <div class="posinvoice-header">
                                <img src="{{asset(general()->logo())}}">
                                <h4>{{general()->title}}</h4>
                                <p>Address: {!!general()->address!!}</p>
                                <p>Mobile: {{general()->mobile}}</p>
                                <p>Email: {{general()->email}}</p>
                            </div>
                            <hr style="margin: 5px 0px;">
                            <div class="posinvoice-info">
                                
                                <table class="table oneTable">
                                  <tbody>
                                    <tr>
                                      <th scope="row">Invoice ID:</th>
                                      <td>{{$order->invoice}}</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Date:</th>
                                      <td>{{$order->created_at->format('d/m/Y')}}</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Time:</th>
                                      <td>{{$order->created_at->format('h:i A')}}</td>
                                    </tr>
                                    
                                    <tr>
                                      <th scope="row">Customer Name:</th>
                                      <td>{{$order->name?:'Guest'}}</td>
                                    </tr>
                                    
                                    <tr>
                                      <th scope="row">Phone:</th>
                                      <td>{{$order->mobile}}</td>
                                    </tr>
                                    
                                    <tr>
                                      <th scope="row">Address:</th>
                                      <td>{{$order->address}}</td>
                                    </tr>
                                    
                                    <tr>
                                      <th scope="row">Sold By:</th>
                                      <td>{{$order->posByuser?$order->posByuser->name:''}}</td>
                                    </tr>
                                  </tbody>
                                </table>
                                
                            </div>
                            
                            <div class="bin-part">
                                <div class="row">
                                    <div class="col-8"><b>BIN-45488884</b></div>
                                    <div class="col-4" style="text-align: end;"><b>Mushak 6.3</b></div>
                                </div>
                            </div>
                            
                            <div class="posinvoice-products">
                                <h6>Invoice</h6>
                                <table class="table twoTable">
                                  <tbody>
                                    <tr>
                                      <th style="width: 8%">Sl</th>
                                      <th style="width: 32%">Name</th>
                                      <th style="text-align: center;">Qty</th>
                                      <th style="width:15%;text-align: center;">Price</th>
                                      <th style="width:20%; text-align: right;">Ammount</th>
                                    </tr>
                                    @foreach($order->items as $i=>$item)
                                    <tr>
                                      <td style="width: 8%">{{$i+1}}</td>
                                      <td style="width: 32%">{{$item->product_name}}</td>
                                      <td style="text-align: center;">{{$item->quantity}}</td>
                                      <td style="width:15%;text-align: center;">{{priceFormat($item->price)}}</td>
                                      <td style="width:20%; text-align: right;">{{priceFormat($item->total_price)}}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                    	<td colspan="5" style="border: 2px solid black;"></td>
                                    </tr>
                                    <tr>
                                    	<td colspan="4" style="text-align: right; font-weight: bold;">Subtotal</td>
                                    	<td style="text-align: right;">{{priceFormat($order->total_price)}}</td>
                                    </tr>
                                    <tr>
                                    	<td colspan="4" style="text-align: right; font-weight: bold;">Discount</td>
                                    	<td style="text-align: right;">{{priceFormat($order->discount_price)}}</td>
                                    </tr>
                                    <tr>
                                    	<td colspan="4" style="text-align: right; font-weight: bold;">Total Amount</td>
                                    	<td style="text-align: right;">{{priceFormat($order->grand_total)}}</td>
                                    </tr>
                                    <tr>
                                    	<td colspan="4" style="text-align: right; font-weight: bold;">Paid</td>
                                    	<td style="text-align: right;">{{priceFormat($order->paid_amount)}}</td>
                                    </tr>
                                    <tr>
                                    	<td colspan="4" style="text-align: right; font-weight: bold;">Due</td>
                                    	<td style="text-align: right;">{{priceFormat($order->due_amount)}}</td>
                                    </tr>
                                  </tbody>
                                </table>

                                
                                <div class="payments-section">
                                    <p style="margin: 0;">Payments</p>
                                    <table class="table fourTable">
                                      <tbody>
                                        <tr>
                                          <th style="width: 10%">Sl.</th>
                                          <th>Method</th>
                                          <th>Amount</th>
                                        </tr>
                                        
                                        @foreach($order->transections as $i=>$transection)
                                        <tr>
                                          <td style="width: 10%">{{$i+1}}</td>
                                          <td>{{$transection->method?$transection->method->name:'No Found'}}</td>
                                          <td>{{priceFullFormat($transection->amount)}}</td>
                                        </tr>
                                        @endforeach

                                      </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>




     </div>

     <div class="tab-pane" id="tabIcon2" role="tabpanel" aria-labelledby="baseIcon-tab12">
         
     	<button class="btn btn-sm btn-success"><i class="fa fa-print"></i> Click Print</button>
     	<div class="invoice-inner">
                    <div class="row">
                        <div class="col-8">

                            <div class="demo-info">
                                <img src="{{asset(general()->logo())}}" alt="company-logo">
                                <h6>{{general()->title}}</h6>
                                <p>{!!general()->address!!}</p>
                                <p><b>Mobile:</b> {{general()->mobile}}</p>
                                <p><b>Email:</b> {{general()->email}}</p>
                                <p><b>BIN:</b> 3456</p>
                                <p><b>Mushak:</b> 3578</p>
                                <p><b>Sold By:</b> {{$order->posByuser?$order->posByuser->name:''}}</p>
                                <p><b>Remarks:</b> </p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="invoice-info">
                                <h6>Invoice</h6>
                                <p>Invoice No: <b>{{$order->invoice}}</b></p>
                                <p>Date: {{$order->created_at->format('d/m/Y')}}</p>
                                <p>Time: {{$order->created_at->format('h:i A')}}</p>
                                <p class="billingTo">Billing To</p>
                                <h5>{{$order->name?:'Guest'}}</h5>
                                <p><b>Mobile:</b> {{$order->mobile}}</p>
                                <p><b>Address:</b> {{$order->address}}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="invoice-products">
                        <table class="table remarkTable">
                          <thead>
                            <tr>
                              <th style="width: 10%;">SL.</th>
                              <th style="width: 35%; text-align: center;">Item</th>
                              <th style="width: 15%;text-align: center;">Quantity</th>
                              <th style="width: 20%;text-align: center;">Rate</th>
                              <th style="text-align: end;">Total</th>
                            </tr>
                          </thead>
                          <tbody>
                          	@foreach($order->items as $i=>$item)
	                            <tr>
	                              <td>{{$i+1}}</td>
	                              <td style="text-align: center;">{{$item->product_name}}</td>
	                              <td style="text-align: center;">{{$item->quantity}}</td>
	                              <td style="text-align: center;">{{priceFormat($item->price)}}</td>
	                              <td style="text-align: end;">{{priceFormat($item->total_price)}}</td>
	                            </tr>
                            @endforeach
                            <tr>
                            	<th colspan="4" style="text-align: end;border-color: white;">Subtotal</th>
                            	<td style="text-align: end;border-color: white;">{{priceFormat($order->total_price)}}</td>
                            </tr>
                            <tr>
                            	<th colspan="4" style="text-align: end;border-color: white;">Discount</th>
                            	<td style="text-align: end;border-color: white;">{{priceFormat($order->discount_price)}}</td>
                            </tr>
                            <tr>
                            	<th colspan="4" style="text-align: end;border-color: white;">Total Amount</th>
                            	<td style="text-align: end;border-color: white;">{{priceFormat($order->grand_total)}}</td>
                            </tr>
                            <tr>
                            	<th colspan="4" style="text-align: end;border-color: white;">Paid</th>
                            	<td style="text-align: end;border-color: white;">{{priceFormat($order->paid_amount)}}</td>
                            </tr>
                            <tr>
                            	<th colspan="4" style="text-align: end;border-color: white;">Due</th>
                            	<td style="text-align: end;border-color: white;">{{priceFormat($order->due_amount)}}</td>
                            </tr>
                          </tbody>
                        </table>
                        
                        <div class="payment-details">
                            <span><b>Payment Method</b></span>
                            <div class="row">
                                <div class="col-8">
                                    <table class="table remarkTable">
                                      <thead>
                                        <tr>
                                          <th style="width: 10%;">SL.</th>
                                          <th style="width: 40%; text-align: center;">Payment Method</th>
                                          <th style="text-align: center;">Payment By</th>
                                          <th style="text-align: end;">Amount</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      	@foreach($order->transections as $transection)
                                        <tr>
                                          <td>1</td>
                                          <td style="text-align: center;">{{$transection->method?$transection->method->name:'No Found'}}</td>
                                          <td style="text-align: center;">{{$transection->methodOption?$transection->methodOption->name:'-'}}</td>
                                          <td style="text-align: end;">{{priceFullFormat($transection->amount)}}</td>
                                        </tr>
                                        @endforeach
                                      </tbody>
                                    </table>
                                    <hr style="margin: 5px 0;">
                                    <table class="table subtotalTable">
                                      <tbody>
                                          <tr>
                                            <td style="width: 80%; text-align: end;"><b>Total:</b>
                                            </td><td style="text-align: end;">{{priceFullFormat($order->transections->sum('amount'))}}
                                          </td></tr>
                                      </tbody>
                                    </table>
                                </div>
                                <div class="col-4"></div>
                            </div>
                        </div>
                        
                        <div class="signature-part">
                            <div class="row">
                                <div class="col-6">
                                    ------------------<br>
                                    <span><b>Received By</b></span>
                                </div>
                                <div class="col-6" style="text-align: end;">
                                    ------------------<br>
                                    <span><b>Authorised By</b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



     </div>

 </div>