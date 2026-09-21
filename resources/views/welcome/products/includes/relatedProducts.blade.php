@if($product->relatedProducts(12)->first())
<div class="row" style="margin: 0;">
  <div class="col-12" style="padding: 0;">
    <div class="productdiv" style="margin:7px 0;">
      <div class="producttitle" style="padding: 5px;">
        <h3 style="margin: 0;font-size: 18px;text-align:left;"> {{session()->get('locale')=='bn'?'সম্পর্কিত আরও  পন্য':'Related Product'}}</h3>
      </div>
      <div class="producttext">
          <div class="owl-carousel owl-theme CategoySlider-owl">
                @foreach($product->relatedProducts(12) as $product)
                    <div class="item">
                       @include(App\Models\General::first()->theme.'.products.includes.productCard')
                    </div>
                @endforeach
            </div>
     
    </div>
  </div>
</div>
</div>
@endif