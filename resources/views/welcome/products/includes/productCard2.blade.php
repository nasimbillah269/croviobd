<div class="card">
    
    <div class="product-imagesection">
        <a class="image-size" href="{{route('productView',$product->slug?:Str::slug($product->name))}}">
            <img src="{{asset($product->image())}}" class="item-img-1" alt="{{$product->name}}" />
        </a>
        
        <div class="alertdiv">
            @if($product->stockStatus()==false)
            <img src="{{asset('batikrom/images/stockOut.png')}}">
            @endif
        </div>
        
        <div class="quickView">
            <a href="javascript:void(0)" class="wishList wishlistCompareUpdate {{$product->isWl()?'active':''}}" data-id="{{$product->id}}" data-url="{{route('wishlistCompareUpdate',[$product->id,'wishlist'])}}"><i class="fa fa-heart"></i></a>
        </div>
    </div>
    
    <div class="card-body">
        <h5 class="card-title"><a href="{{route('productView',$product->slug?:Str::slug($product->name))}}">{{Str::limit($product->name,100)}}</a></h5>
        <span class="weightNet">Weight: {{$product->weight_amount}}{{$product->weight_unit}}</span>
        {{--<div class="starView">
            <ul>
                <li>
                    <a href="#"><i class="fa fa-star-o"></i></a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-star-o"></i></a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-star-o"></i></a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-star-o"></i></a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-star-o"></i></a>
                </li>
            </ul>
        </div>--}}
        <span>
            @if($product->regular_price > $product->offerPrice())
            <del>{{priceFullFormat($product->regular_price)}}</del>
            @endif
            {{priceFullFormat($product->offerPrice())}}</span>
        @if($product->stockStatus()==false)
        <a href="javascript:void(0)" class="btn addcartbutton" style="background-color: #ec0927;"><i class="fa mr-1 fa-times"></i> STOCK OUT</a>
        @else
        <span class="CMessageSuccess{{$product->id}}" style="display:block;"></span>
        <a href="javascript:void(0)" class="btn addcartbutton LoadingaddCartHide_{{$product->id}}" style="background-color: #ffffff00;display:none;"><img src="{{asset('medies/loading.gif')}}"></a>
        <a href="javascript:void(0)" class="btn addcartbutton addCartShow_{{$product->id}} singleaddCart" data-id="{{$product->id}}" data-url="{{route('addToCart',$product->id)}}"><i class="fa mr-1 fa-shopping-cart"></i> ADD TO CART</a>
        
        @endif
    </div>
</div>