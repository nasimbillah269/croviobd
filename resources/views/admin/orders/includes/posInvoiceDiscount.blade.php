<div class="modal-header">
 <h4 class="modal-title" id="myModalLabel1">Discount</h4>
 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
   <span aria-hidden="true">&times; </span>
 </button>
</div>
<div class="modal-body">
    <div class="input-group">
        <select class="form-control discounType" style="max-width: 150px;background: #3f51b5;color: white;">
            <option value="Flat" {{$order->discount_type=='Flat'?'selected':''}}>Flat ({{general()->currency}})</option>
            <option value="Percantage" {{$order->discount_type=='Percantage'?'selected':''}}>Percentage (%)</option>
        </select>
        <input type="number" class="form-control discountAmount" value="{{$order->discount?:''}}" placeholder="Enter Discount">
    </div>
</div>
<div class="modal-footer">
 	<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
 	<button type="button" class="btn grey btn-success DiscoutApply" data-type="discount">Submit</button>
</div>