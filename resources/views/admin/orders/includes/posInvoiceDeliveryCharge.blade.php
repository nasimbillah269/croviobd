<div class="modal-header">
 <h4 class="modal-title" id="myModalLabel1">Delivery Charge</h4>
 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
   <span aria-hidden="true">&times; </span>
 </button>
</div>
<div class="modal-body">
    <div class="input-group">
        <input type="number" class="form-control deliveryCharge" value="{{$order->shipping_charge?:''}}" placeholder="Enter Discount">
    </div>
</div>
<div class="modal-footer">
 	<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
 	<button type="button" class="btn grey btn-success deliveryChargeSubmit" data-type="discount">Submit</button>
</div>