 <table class="table table-borderless" style="border: 1px solid #eaeded;">
            <tbody>
                
              <tr>
                <th>{{session()->get('locale')=='bn'?'সাবটোটাল':'Subtotal'}}</th>
                <td style="text-align: right;">BDT <span class="cartTotalPrice">{{number_format($cartTotalPrice) }}</span></td>
              </tr>

              <tr>
                <th>{{session()->get('locale')=='bn'?'ডিসকাউন্ট':'Discount'}} (-)</th>
                <td style="text-align: right;">BDT <span class="cartDiscount">{{ $couponDisc }}</span></td>
              </tr>
              
              <tr>
                <th>{{session()->get('locale')=='bn'?'ডেলিভারি চার্জ':'Delivery Charge'}}</th>
                <td style="text-align: right;">BDT 
                	<span class="cartDeliveryCharge">{{$deliveryCharge}}</span>
	            </td>
              </tr>
              <tr>
                <th>{{session()->get('locale')=='bn'?'সর্বমোট ':'Grand Total'}}</th>
                <th style="text-align: right;">BDT
                <span class="grandTotalPrice60 grandTotalPrice">{{number_format($grandTotal+$deliveryCharge)}}</span>
                </th>
              </tr>
            </tbody>
          </table>
        
          <div class="">
            
           <center>
            <p style="margin: 0;padding: 5px;background: #ef1b22;color: white;font-size: 20px;">{{session()->get('locale')=='bn'?'ডেলিভারি এলাকা':'Delivery Area'}}</p>
          </center>
          <label class="DeliverychargeOption {{$dhaka==1?'active':''}}">
            <p>1. Dhaka City in delivery charge is BDT {{$deliveryChargeIn}}</p>
          </label>
          <label class="DeliverychargeOption {{$dhaka==2?'active':''}}">
            <p>2.Out of Dhaka City in delivery charge is BDT {{$deliveryChargeOut}}</p>
          </label>
        </div>