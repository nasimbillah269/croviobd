<div class="table-responsive infoTableHight">
 
 @if($posMessage)
 <p class="AlertMessage" style="color: red;font-weight: bold;">{{$posMessage}}</p>
 @endif

<table class="table table-bordered infoTable">
        <thead>
           <tr>
               <th style="width: 250px;min-width: 250px;">Product</th>
               <th style="width: 130px;min-width: 130px;text-align: center;">Quantity</th>
               <th style="width: 100px;min-width: 100px;text-align: center;">Price</th>
               <th style="width: 100px;min-width: 100px;text-align: center;">Total</th>
               <th style="width: 50px;min-width: 50px;text-align: center;"><i class="fa fa-trash"></i></th>
           </tr>
        </thead>
        <tbody>
           @foreach($order->items as $item)
           <tr>
               <td>
                    {{$item->product_name}} {{$item->product?$item->product->sku_code?'('.$item->product->sku_code.')':'':''}}
                </td>
               <td>
                <div class="input-group">
                    <span class="fa fa-minus ItemMinus {{$item->product?$item->quantity > 1?'ItemMinusClick':'':''}}" data-id="{{$item->id}}" data-type="itemMinus" ></span>
                    <input type="number" class="itemQtyinput" value="{{$item->quantity}}" name="quantity" placeholder="1" data-id="{{$item->id}}" data-type="itemQuantity">
                    <span class="fa fa-plus ItemPlus {{$item->product?$item->product->quantity > $item->quantity?'ItemPlusClick':'':''}}" data-id="{{$item->id}}" data-type="itemPlus"></span>
                </div>
               </td>
               <td style="text-align: center;">{{priceFormat($item->price)}}</td>
               <td style="text-align: center;">{{priceFormat($item->total_price)}}</td>
               <td style="text-align: center;">
                   <span class="fa fa-trash text-danger itemDelete" data-id="{{$item->id}}" data-type="itemdelete" style="cursor:pointer;"></span>
               </td>
           </tr>
           @endforeach
           @if($order->items->count()==0)
           <tr>
               <td colspan="5" style="text-align:center;">
                   <span>No Item</span>
               </td>
           </tr>
           @endif
        </tbody>
   </table>
</div>
<hr>
<div class="MyInforTrash">
   <div class="row">
       <div class="col-md-6">
           <p style="margin: 0;font-size: 25px;font-weight: bold;">Bill: {{priceFullFormat($order->grand_total)}}</p>
           <table class="table table-borderless infoTable">
               <tr>
                   <td>Pay</td>
                   <th style="text-align: right;">{{priceFormat($order->paid_amount)}}</th>
               </tr>
               <tr>
                   <td>Due</td>
                   <th style="text-align: right;">{{priceFormat($order->due_amount)}}</th>
               </tr>
               @if($order->extra_amount)
               <tr>
                   <td>Advence</td>
                   <th style="text-align: right;">{{priceFormat($order->extra_amount)}}</th>
               </tr>
               @endif
           </table>
       </div>
       <div class="col-md-6">
           <table class="table table-borderless infoTable">
               <tr>
                   <td>Sub Total</td>
                   <th style="min-width: 150px;width:150px ;text-align: right;" class="SubTotalsAmount" data-amount="{{$order->total_price}}">{{priceFormat($order->total_price)}}</th>
               </tr>
               <tr>
                   <td>Discount</td>
                   <th style="text-align: right;">
                    <div class="discountInputEdit">
                        <div class="input-group">
                            <input type="number" class="form-control form-control-sm discountInputAmount" data-type="discountAmount" placeholder="0" value="{{$order->discount?:''}}" style="text-align: center;">
                            <select class="form-control form-control-sm discountInputType" data-type="discountType">
                                <option value="Flat" {{$order->discount_type=='Flat'?'selected':''}}>Flat</option>
                                <option value="Percantage" {{$order->discount_type=='Percantage'?'selected':''}}>Percantage</option>
                            </select>
                            <span class="input-group-addon discountInputEditAction" data-type="discountAction" style="padding: 5px 8px;cursor: pointer;"><i class="fa fa-check"></i></span>
                        </div>
                        <span class="DiscountErrorMsg"></span>
                    </div>
                    <div class="discountText">
                        <span class="discountEdit">
                        <i class="fa fa-edit" style="cursor:pointer;"></i>
                        </span>
                        @if($order->discount_type=='Percantage')
                        ({{$order->discount}}%)
                        @endif
                        {{priceFormat($order->discount_price)}}
                    </div>
                    </th>
               </tr>
               <tr>
                   <td>Shipping Charge</td>
                   <th style="text-align: right;">
                    <div class="shippingInputEdit">
                        <div class="input-group">
                            <input type="number" class="form-control form-control-sm shippingInputAmount" placeholder="0" value="{{$order->shipping_charge?:''}}" style="text-align: center;">
                            <span class="input-group-addon shippingInputEditAction" style="padding: 5px 8px;cursor: pointer;"><i class="fa fa-check"></i></span>
                        </div>
                    </div>
                    <div class="shippingText">
                    <span class="shippingEdit" >
                    <i class="fa fa-edit" style="cursor:pointer;"></i>
                    </span>
                    {{priceFormat($order->shipping_charge)}}
                    </div>
                    </th>
               </tr>
               <tr>
                   <td>Tax ({{general()->tax}}%)</td>
                   <th style="text-align: right;">{{priceFormat($order->tax)}}</th>
               </tr>
           </table>
       </div>
   </div>
</div>

