@isset($cpCount)
    @if($cpCount > 0)
<div class="compareTable">
	<table class="table table-bordered">
		<tr>
			<th style="min-width: 200px;">image</th>
			<th style="min-width: 400px;">Prouct Details</th>
			<th style="min-width: 200px;">Prouct Price</th>
			<th style="min-width: 100px;">action</th>
		</tr>
		@foreach($products as $product)
		<tr>
			<td style="text-align:center;">
				<a href="{{route('singleProduct',[$product, Str::slug($product->title)])}}"><img  src="{{ asset($product->fi()) }}" alt="{{$product->title}}"></a>
			</td>
			<td>
				<p style="margin:0;"><b>Title: </b> {{$product->title}}</p>
				<p style="margin:0;"><b>Category: </b>{{$product->cat?$product->cat->title:''}} {{$product->subcat?'- '.$product->subcat->title:''}} {{$product->subsubcat?'- '.$product->subsubcat->title:''}}</p>
				@if($product->brand)
				<p style="margin:0;"><b>Brand: </b> {{$product->brand->title}}</p>
				@endif
				<p style="margin:0;"><b>Rating: </b> 
				<span>
		          <i class="fa fa-star" style="color:#ffc107;"></i>
		          <i class="fa fa-star" style="color:#ffc107;"></i>
		          <i class="fa fa-star" style="color:#ffc107;"></i>
		          <i class="fa fa-star" style="color:#ffc107;"></i>
		          <i class="fa fa-star" style="color:#ffc107;"></i>
		        </span>
		        <span>{{$product->reviews()->count()==0?1:$product->reviews()->count()}}</span>
				</p>
			</td>
			<td>
				<span>
					BDT {{number_format($product->offerPrice(),0)}} 
    
		            @if($product->discount > 0) 
		            <del style="color: #29a7d9;font-size: 16px;"> 
		            {{number_format($product->sale_price,0)}} 
		            </del> 
		            @endif

		        </span>
		        <br>
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
		        
			</td>
			<td>
				<a class="wishlistCompareUpdate" href="javascript:void(0)" data-url="{{ route('wishlistCompareUpdate',[$product,'compare']) }}">Remove</a>
			</td>
		</tr>
		@endforeach
	</table>
    </div>
    @else

<div class="emptycarts">
    <center>
        <p>No Compare Product</p>
    </center>
</div>

@endif

@endisset