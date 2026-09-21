
<div class="row" style="margin: 0;">
    
    @foreach($homedata->products() as $product1)
     <div class="col-md-2 col-6" style="padding:3px;">
         <div class="ProductGridSection">
             @if($homedata->product_type==1)
             <label><input type="checkbox" name="delete[]" value="{{$product1->id}}"> <i class="fa fa-trash text-danger"></i></label>
             @endif
             <div class="ProductGrid">
                 <img src="{{asset($product1->image())}}" >
             </div>
         </div>
     </div>
     @endforeach
</div>