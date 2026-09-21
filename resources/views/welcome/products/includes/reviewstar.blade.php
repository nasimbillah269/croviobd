
@for($i=0;$i < $product->productRating();$i++)
<i class="fa fa-star"></i>
@endfor

@for($i=0;$i < 5-$product->productRating();$i++)
<i class="fa fa-star-o"></i>
@endfor

