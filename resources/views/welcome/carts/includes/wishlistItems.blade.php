@isset($wlCount)
    @if($wlCount > 0)


		 <table class="table wishlisttable">
		  <thead>
		    <tr>
		      <th scope="col" style="width: 45%"></th>
		      <th scope="col" style="width: 55%; text-align: end;">Product Name</th>
		    </tr>
		  </thead>
		  <tbody>
		      
		  @foreach($products as $product)
		    <tr>
		      <td scope="row">
		      <a style="color: #f44336; font-size:18px;" href="javascript:void(0)" class="wishlistCompareUpdate" data-url="{{route('wishlistCompareUpdate',[$product->id,'wishlist'])}}"><i class="fa mr-3 fa-times-circle-o" aria-hidden="true"></i></a>
		      <a href="{{route('productView',$product->slug?:Str::slug($product->name))}}"><img src="{{asset($product->image())}}"></a>
		      </td>
		      <td style="text-align: end;">
		          <a href="{{route('productView',$product->slug?:Str::slug($product->name))}}">{{$product->name}}</a>
		          <br> {{priceFullFormat($product->offerPrice())}}
		      </td>
		    </tr>
		  @endforeach

		  </tbody>
		</table>
        
        {{$products->links('pagination')}}
        
    @else

    <div class="emptycarts">
      <center>
          <p>No Wishlist Product</p>
      </center>
    </div>
    @endif

@endisset