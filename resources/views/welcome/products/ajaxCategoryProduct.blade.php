@foreach($products as $product)
<div class="col-md-4 col-6">
    @include(general()->theme.'.products.includes.productCard1')
</div>
@endforeach