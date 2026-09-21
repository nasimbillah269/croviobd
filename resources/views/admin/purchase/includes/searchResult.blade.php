@if($searchProducts)
<ul>
	@foreach($searchProducts as $searchProduct)
	<li data-id="{{$searchProduct->id}}" data-type="addproduct">
		{{$searchProduct->name}} {{$searchProduct->bar_code?'| '.$searchProduct->bar_code:''}}
	</li>
	@endforeach
</ul>
@endif