@foreach($products as $product)
   <div class="col-6 col-md-4" style="padding: 5px;">
       <div class="productGrid" data-id="{{$product->id}}" data-type="addItem">
           <img src="{{asset($product->image())}}">
           <p>
               {{Str::limit($product->name,18)}} <br> ({{$product->quantity?:0}}) | {{priceFullFormat($product->final_price)}} <br> <span><i class="fa fa-barcode"></i> {{$product->sku_code}}</span>
           </p>
       </div>
   </div>
@endforeach

@if($products->count()==0)
<div class="col-12 col-md-12" style="padding: 5px;text-align: center;">
	<p style="font-size: 25px;font-weight: bold;color: #ff2845;">No Product Found</p>
</div>
@endif