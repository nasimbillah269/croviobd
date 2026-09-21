<form class="cat-subcat-form-"  action="{{ route('addToCart',$product) }}" method="post" style="min-height:300px;">
@csrf    
<div>
    <div class="product-cart-info">
      <p style="margin: 0;font-size: 20px;font-weight: bold;color: #34364b;" class="singletitle"> {{ $product->title }}</p>
      <p style="margin: 0;font-size: 12px;margin: 10px 0;">
        <span>
          <i class="fa fa-star" style="color:#ffc107;"></i>
          <i class="fa fa-star" style="color:#ffc107;"></i>
          <i class="fa fa-star" style="color:#ffc107;"></i>
          <i class="fa fa-star" style="color:#ffc107;"></i>
          <i class="fa fa-star" style="color:#ffc107;"></i>
        </span>
        <span>{{$ratings}} {{session()->get('locale')=='bn'?'রেটিং':'Ratings'}}</span>
        <span style="font-weight: bold;">{{session()->get('locale')=='bn'?'ব্র্যান্ড':'Brand'}}: </span>
        @if($brand = App\Models\ProductBrand::find($product->brand_id))
        <span>{{$brand->title}}</span>
        @else
        <span>{{session()->get('locale')=='bn'?'ব্র্যান্ড নাই':'No Brand'}}  </span>
        @endif
        
      </p>
    
      </div>

       @if($product->hasPriceVariation())
        <div class="hasPriceVariationDiv">
          @include(App\Models\General::first()->theme.'.products.includes.variationSku')
        </div>
        @else

        <p style="margin: 0;font-weight: bold;color: #34364b;font-size: 20px;">
        
          {{App\Models\General::first()->currency}}  {{number_format($product->offerPrice(),0)}} Only/=
            
            @if($product->discount > 0) 
            <del style="color: #29a7d9;font-size: 16px;"> 
            {{number_format($product->sale_price,0)}} 
            </del> 
            @endif
            
            @if($product->sale_price==$product->offerPrice()) @else
            @if($product->discount_type =='percent' && $product->discount > 0)
    
                    <span style="margin-left: 10px;color: #ffc107;">
                        {{number_format($product->discount,0)}} % OFF
                    </span>
    
            @elseif($product->discount_type =='flat' && $product->discount > 0)
    
                    <span style="margin-left: 10px;color: #ffc107;">
                        {{number_format(100-($product->final_price*100/$product->sale_price),0)}} % OFF
                    </span>
    
            @endif
            
            @endif
        </p>
        @endif

    
      
     
      @if($product->emi_status)
      <div>
          <a href="#" class="badge" style="background: #fceb28;font-size: 16px;color: black;" target="_blank">EMI Available</a>
         <label style="cursor:pointer;"> <input type="checkbox" name="statusEmi" > Yes Got It. </label>
      </div>
      @endif
     


   

   {{--  @if($product->skuSizes()->count() > 0)
      <div class="row" style="margin: 0;">
       <div class="col-3" style="padding: 0;">
         <p>Size</p>
       </div>
       <div class="col-9" style="padding: 0;">
        <div class="product-attr product-size">
          <ul>
            @foreach($product->skuSizes()->orderBy('id')->get() as $skuSize)

            @if($size = $skuSize->size)
            <li>

              <label id="brandcheckid" class="sizeIDValue {{ $loop->first and $size->hasSkuColor($product) ? 'selected' : '' }}

                    @foreach($size->skuColorIds($product->id) as $cid)
                     colorid-{{ $cid }}
                     @endforeach

                " data-id="{{$skuSize->size_id}}" style="margin-right:3px;">
                  <input class="radio" type="radio" style="display:none;" name="size" id="" value="{{ $skuSize->size_id }}"  required {{ ($loop->first and $size->hasSkuColor($product)) ? 'checked' : '' }}  >
                <span style="margin-left: 0;">{{ $skuSize->size ? $skuSize->size->title : '' }}  

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
--}}

    
    @if(Auth::check() && Auth::id()==1 || Auth::id()==21)
    @if($hasPromotion)
    <div class="row" style="margin: 0;">
       <div class="col-3" style="padding: 0;">
         <p>Promotions</p>
       </div>
       <div class="col-9" style="padding: 0;">
        @if($promotion =App\Models\Coupon::find($hasPromotion->coupon_id))
        <p class="buypromotion">Buy {{number_format($promotion->minimum_shopping,0)}} Quantity Safe  {{number_format($promotion->discount,0)}}% OFF </p>
        @endif
        </div>
    </div>
    @endif
    @endif
    <p class="short-excerpt">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam, temporibus, aliquid inventore molestiae pariatur quos ipsam eaque ut error facilis quas labore eos dolorem, a vitae. Placeat quidem ipsam illum.
    </p>
    <div class="row" style="margin: 0;border-top: 1px solid #d7d7d7;padding: 5px 0;">
      <div class="col-5 col-md-2" style="padding: 0;">
        <p style="margin: 0;color: #34364b;">
        @if(session()->get('locale')=='bn')
        পরিমাণ
        @else
        Quantity
        @endif    
        <br>
          <!--<span class="text-help" style="font-size: 11px;">Minimum Order Quantity: {{ $product->min_order_quantity }}</span>-->
        </p>
      </div>
      <div class="col-7 col-md-10">
        <div>
        @if($product->hasPriceVariation())
          <div class="plusminusdiv" style="text-align: center;min-height: 38px;width: 150px;margin:1px;">
            <div style="" id="decrease" data-min="{{number_format($product->minQty(),0,'.','')}}">-</div>
            <input style="" id="number" value="{{number_format($product->minQty(),0,'.','')}}" readonly type="number" name="qnt">
            <div id="increase" data-max="{{number_format($product->skuPrices()->first()->stock_quantity,0,'.','')}}" >+
            </div>
          </div>
        @else
        <div class="plusminusdiv" style="text-align: center;min-height: 38px;width: 150px;margin:1px;">
            <div style="" id="decrease" data-min="{{number_format($product->minQty(),0,'.','')}}">-</div>
            <input style="" id="number" value="{{number_format($product->minQty(),0,'.','')}}" readonly type="number" name="qnt">
            <div id="increase" data-max="{{number_format($product->maxQty(),0,'.','')}}" >+
            </div>
          </div>
        @endif
        </div>
      </div>
    </div>
    
    
    <div style="margin:10px 0;padding: 5px 0;">
        
        @if($product->hasPriceVariation())
            
            <div class="row" style="margin: 0;">
                <div class="col-6 col-xl-5" style="padding: 2px;">
                  <button id="submitBtn" type="submit"><i class="fa fa-shopping-cart"></i>
                  
                  @if(session()->get('locale')=='bn')
                  কার্টে যোগ করুন
                    @else
                    ADD TO CART
                    @endif
        
                  </button>
                </div>
                <div class="col-6 col-xl-5" style="padding: 2px;">
                    <button id="submitBtn2" name="orderNow" value="on" type="submit"><i class="fa fa-shopping-basket"></i> 
                    
                     @if(session()->get('locale')=='bn')
                    এখনই কিনুন
                    @else
                   ORDER NOW
                    @endif
        
                    </button>
                </div>
             </div>
            
            
        @else

        @if($product->stock_out_limit >=  $product->productStock())
        <div style="text-align:center;">
        <span style="font-weight: bold;color: red;font-size: 25px;">Out Of Stock</span>
      </div>
        @else
       
      <div class="row" style="margin: 0;">
        <div class="col-6 col-xl-5" style="padding: 2px;">
          <button id="submitBtn" type="submit"><i class="fa fa-shopping-cart"></i>
          
          @if(session()->get('locale')=='bn')
          কার্টে যোগ করুন
            @else
            ADD TO CART
            @endif

          </button>
        </div>
        <div class="col-6 col-xl-5" style="padding: 2px;">
            <button id="submitBtn2" name="orderNow" value="on" type="submit"><i class="fa fa-shopping-basket"></i> 
            
             @if(session()->get('locale')=='bn')
            এখনই কিনুন
            @else
           ORDER NOW
            @endif

            </button>
        </div>
      </div>
      @endif
      
      @endif
    </div>

  <div class="cartmessage">
    @include(App\Models\General::first()->theme.'.alerts')
  </div>
  
    
</div>


</form>