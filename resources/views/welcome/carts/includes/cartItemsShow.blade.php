<table class="table">
<tbody>
  <tr>
  <th colspan="2" style="min-width: 240px;padding: 5px;">{{session()->get('locale')=='bn'?'পণ্য':'ITEMS'}}</th>
  <th style="text-align: center;min-width: 140px;padding: 5px;"></th>
  <th style="text-align: center;min-width: 140px;padding: 5px;">{{session()->get('locale')=='bn'?'মোট মূল্য':'TOTAL PRICE'}}</th>
</tr>
@foreach($carts as $cart)
<tr class="itemlist_{{ $cart->id }}  carted-item">
  <td style="text-align: center;width:15%;"><img src="{{asset($cart->product->fi())}}" style="max-height: 50px;max-width: 100px;" alt=""></td>
  <td style="">{{ $cart->product->title }}<br> @if($cart->color)Color: {{ $cart->color }}@endif &nbsp; @if($cart->size) Size: {{ $cart->size }} @endif 
  @if(Auth::check() && Auth::id()==1 || Auth::id()==21)
    @if($hasPromotion =App\Models\CouponItem::where('type','promotion')->where('product_id',$cart->product->id)->where('date_to', '>=', date('Y-m-d'))->first())
        @if($promotion =App\Models\Coupon::find($hasPromotion->coupon_id))
        <p class="buypromotion" style="margin: 0;color: red;font-weight: 600;font-size: 14px;">Buy {{number_format($promotion->minimum_shopping,0)}} Quantity Safe  {{number_format($promotion->discount,0)}}% OFF </p>
        
        @endif
    @endif
    @endif
  </td>
  <td>BDT {{ number_format($cart->product->offerPrice(),0)}} <span style="color: #bf203d;">X</span> {{ $cart->quantity }}</td>
  <td style="text-align: center;font-size: 14px;">BDT <span class="itemTotalPrice_{{ $cart->id }}">{{ number_format($cart->quantity * $cart->product->offerPrice(),0) }}</span></td>
</tr>

@endforeach


</tbody>

</table>