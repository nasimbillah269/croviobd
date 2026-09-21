<div class="posinvoice-inner PrintAreaContact2">
      <div class="posinvoice-header">
          <img src="{{asset(general()->logo())}}" style="max-width:150px;">
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