<div style="height: 70px;">
<p style="margin: 0;font-weight: bold;color: #34364b;font-size: 20px;">
{{App\Models\General::first()->currency}}
@if($product->skuPrices()->min('new_price') == $product->skuPrices()->max('new_price'))
{{number_format($product->skuPrices()->min('new_price'),0)}}
@else
{{number_format($product->skuPrices()->min('new_price'),0)}} - 
{{number_format($product->skuPrices()->max('new_price'),0)}}
@endif
</p>
@foreach($product->skuPrices()->get() as $skuPrice)
<p class="skuPriseValue skuPriseValueid_{{$skuPrice->color_id}}_{{$skuPrice->size_id}} {{ $loop->first ? 'selected' : '' }}"  data-min="{{number_format($product->minQty(),0,'.','')}}" data-max="{{number_format($skuPrice->stock_quantity,0,'.','')}}" style="margin: 0;font-weight: bold;color: #28a745;font-size: 14px;">BDT  {{ $skuPrice->new_price ? number_format($skuPrice->new_price,0) : '' }}</p>
@endforeach
</div>

@if($product->skuColors->unique('color_id')->count() > 0)



<div class="row" style="margin: 0;">
       <div class="col-3" style="padding: 0;">
         <p>Color</p>
       </div>
       <div class="col-9" style="padding: 0;">
          
           
        <div class="product-attr product-color">
          <ul> 
           @foreach($product->skuColors->unique('color_id') as $color)
              @if($color->color)
                <li style="margin-right: 0;"> 
                <input class="radio" type="radio" style="display:none;" name="size" id="" value="{{$color->color_id}}" required="">
                  <label class="variationcheckid colorIDValue 
                  @foreach($color->color->skuSizeIds($product->id) as $sid)
                  sizeid-{{$sid}}
                  @endforeach
                  " data-imageid=""  data-url="">
                      
                  
                  <span style="margin-left: 0;">
                     
                      <img src="{{asset($color->fi())}}" style="width: 40px;">
                
                  </span>
                  </label>
                  </li>
                @endif
                @endforeach
            </ul>
          </div>
          
        </div>
      </div>
      
@endif


@if($product->skuSizes->unique('size_id')->count() > 0)
     
      <div class="row" style="margin: 0;">
       <div class="col-3" style="padding: 0;">
         <p>Size</p>
       </div>
       <div class="col-9" style="padding: 0;">
        <div class="product-attr product-size">
          <ul>
            @foreach($product->skuSizes->unique('size_id') as $size)
              @if($size->size)  
              <li>
                  <label class="variationcheckid sizeIDValue
                  @foreach($size->size->skuColorIds($product->id) as $cid)
                  colorid-{{$cid}}
                  @endforeach
                  " data-id="{{$size->size->id}}" data-url="{{route('singleProductCheckSKU',[$product->id,'type'=>'size',$size->id])}}" style="margin-right:3px;" >
                    
                    <span style="margin-left: 0;">{{$size->size->title}}</span>
                  
                  </label>

                </li>
                @endif
           @endforeach
          </ul>
        </div>
        
      </div>
    </div>
@endif

