@if($product->variation_status)
<table class="table table-bordered areaChargeTable">
	@if($product->productAttibutes->count() > 0)
	<tr>
		@foreach($product->productAttibutes->unique('reff_id') as $pAttri)
		@if($pAttri->attribute)
		<td>
			<select class="form-control form-control-sm variationItemsValue" name="variationItems[]">
				<option value="">Select {{ucfirst($pAttri->attribute->name)}}</option>
				@foreach($pAttri->attribute->attributeItems as $subAttri)
				@if($subAttri->attributeItem)
				<option value="{{$subAttri->attributeItem->id}}">{{$subAttri->attributeItem->name}}</option>
				@endif
				@endforeach
			</select>
		</td>
		@endif
		@endforeach
		<td style="width:100px;min-width: 100px;">
		<span href="" class="btn btn-primary btn-sm variationItemsAdd"
		data-url="{{route('admin.productsUpdateAjax',['variationItemsAdd',$product->id])}}"
		 style="padding:8px 15px;"><i class="fa fa-plus"></i> Add</span>
		</td>
	</tr>
	
	@else
	<tr>
		<td style="text-align:center;color: red;">No Attribute. Please Add Attribute items</td>
	</tr>
	@endif
</table>

<h4>Variation SKU List</h4>
{!!$attriMessage!!}
<table class="table table-bordered areaChargeTable align-middle">
	<tr>
		<th style="width: 60px;">ID</th>
		<th style="width: 90px;">Image</th>
		<th>Variant</th>
		<th style="width: 130px;">Price</th>
		<th style="width: 130px;">Qty</th>
		<th style="width: 90px;">Action</th>
	</tr>

	@foreach($product->productSkus->unique('sku_id') as $i=>$sku)
	<tr>
		<td>{{$i+1}}</td>
		<td>
			<img src="{{asset($sku->skuImage())}}" style="width: 50px;height: 50px;object-fit: cover;border-radius: 4px;">
			<input type="file" class="variationImageInput" accept="image/*"
			data-sku="{{$sku->sku_id}}"
			data-url="{{route('admin.productsUpdateAjax',['variationItemsImage',$product->id])}}"
			style="width: 90px;margin-top: 4px;font-size: 10px;">
		</td>
		<th>
			@foreach($product->productAttibutes->unique('reff_id') as $pAttri)
			@if($pAttri->attribute)
			@php
				$currentRow = $sku->skuList->firstWhere('reff_id',$pAttri->reff_id);
			@endphp
			<select class="form-control form-control-sm variationEditSelect" style="display:inline-block;width:auto;margin-bottom:4px;"
			data-sku="{{$sku->sku_id}}"
			data-url="{{route('admin.productsUpdateAjax',['variationItemsEdit',$product->id])}}">
				<option value="">Select {{ucfirst($pAttri->attribute->name)}}</option>
				@foreach($pAttri->attribute->attributeItems as $subAttri)
				@if($subAttri->attributeItem)
				<option value="{{$subAttri->attributeItem->id}}" {{$currentRow && $currentRow->parent_id==$subAttri->attributeItem->id?'selected':''}}>{{$subAttri->attributeItem->name}}</option>
				@endif
				@endforeach
			</select>
			@endif
			@endforeach
		</th>
		<td>
			<input type="number" name="price" placeholder="Price" value="{{$sku->value_1}}" class="variationPriceInput"
			data-sku="{{$sku->sku_id}}"
			data-url="{{route('admin.productsUpdateAjax',['variationItemsUpdate',$product->id])}}"
			style="width: 110px;">
		</td>
		<td>
			<input type="number" name="quantity" placeholder="Quantity" value="{{$sku->duration}}" class="variationQtyInput"
			data-sku="{{$sku->sku_id}}"
			data-url="{{route('admin.productsUpdateAjax',['variationItemsUpdate',$product->id])}}"
			style="width: 110px;">
			<br>
			@if($sku->duration > 0)
			<small class="text-success">Stock In</small>
			@else
			<small class="text-danger">Out of Stock</small>
			@endif
		</td>
		<td>
			<span class="badge badge-danger variationItemsDelete" style="cursor:pointer;"
			data-id="{{$sku->sku_id}}"
			data-url="{{route('admin.productsUpdateAjax',['variationItemsDelete',$product->id])}}"
			><i class="fa fa-trash"></i></span>
		</td>
	</tr>
	@endforeach

</table>

@endif