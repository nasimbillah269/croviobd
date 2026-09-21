@foreach($latestProducts as $product)
<div class="col-md-3 col-6">
    @include(general()->theme.'.products.includes.productCard1')
</div>
@endforeach