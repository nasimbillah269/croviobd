<div class="modal-content">

<div class="modal-header">
 <h4 class="modal-title GrandTotalsPay" data-amount="{{$order->grand_total}}">Order Payments | {{priceFullFormat($order->grand_total)}}</h4>
 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
   <span aria-hidden="true">&times; </span>
 </button>
</div>
<div class="modal-body">
    <h3>Payment Add</h3>
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
    <hr>
    <h3>Payment List</h3>
    <div class="table-responsive">
       <table class="table table-borderless areaChargeTable">
           <thead>   
               <tr>
                   <th style="min-width: 60px;width: 60px;">SL</th>
                   <th style="min-width: 200px;width: 200px;">Method</th>
                   <th style="min-width: 100px;width: 100px;">Amount</th>
                   <th style="min-width:100px;width:100px;text-align: center;">
                   Action
                   </th>
               </tr>
           </thead>
           
           <tbody>
               
               @foreach($transections as $i=>$transection)
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
               
               <tr>
                   <th>
                      
                   </th>
                   <th style="text-align: right;">
                      Total
                   </th>
                   <th>
                     {{priceFullFormat($transections->sum('amount'))}}
                   </th>
                   <th style="text-align: center;">
                      
                   </th>
               </tr>

           </tbody>
       </table>

   </div>
   
   <div class="row" style="margin:0;">
       <div class="col-md-6">
           <h3>Order:</h3>
           <div>
               <button class="btn btn-block btn-info SubmitCheckOut" data-items="{{$order->items->count()}}">Confirmed Checkout</button>
           </div>
       </div>
       <div class="col-md-6">
           <div class="">
               <h3>Calculate:</h3>
               <table class="table table-bordered">
                   <tr>
                       <th>Bill Pay</th>
                       <td style="padding:1px;"><input type="number" class="form-control form-control-sm TotalPayed TotalPayedUpdate" value="{{$transections->sum('amount')}}" placeholder="0"></td>
                   </tr>
                   <tr>
                       <th>Received Amount</th>
                       <td style="padding:1px;"><input type="number" class="form-control form-control-sm ReceivedAmount TotalPayedUpdate" value="" placeholder="0"></td>
                   </tr>
                   <tr>
                       <th>Change Amount</th>
                       <td style="padding:1px;"><input type="number" class="form-control form-control-sm ChangeAmount" value="" placeholder="0"></td>
                   </tr>
               </table>
           </div>
       </div>
       
   </div>

</div>
<div class="modal-footer">
 <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
</div>
</div>