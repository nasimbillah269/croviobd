<div class="SearchResult">
    <ul>
        @foreach($products as $product)
        <li>
            <a href="{{route('productView',$product->slug?:Str::slug($product->name))}}" >
            <div class="row" style="margin:0;">
                <div class="col-2" style="text-align: center;padding:0px;">
                    <img src="{{asset($product->image())}}" alt="Search Product">
                </div>
                <div class="col-10" style="padding:0px;">
                    <p>{{Str::limit($product->name,60)}}</p>
                </div>
                <!--<div class="col-2" style="text-align: end;padding:0px;">-->
                <!--    <span>{{priceFullFormat($product->final_price)}}</span>-->
                <!--</div>-->
            </div>
            </a>
        </li>
        @endforeach
    </ul>
</div>