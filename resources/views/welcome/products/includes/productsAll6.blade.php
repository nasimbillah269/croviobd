
@if($products->count() > 0)
<div class="row" style="margin: 0;padding:0;margin-top: -5px;">

    @foreach($products as $product)
    <div class="col-6 col-lg-3 col-sm-3" style="padding: 5px;">
    @include(App\Models\General::first()->theme.'.products.includes.productCard')
    </div>
    @endforeach

</div>

@else
<div class="row" style="margin: 0;padding:0;margin-top: -5px;">

   
    <div class="col-12" style="padding: 5px;">

    <center style="text-align: left; color: gray;">
        <h2 style="font-size: 20px;">No Product Found</h2>
    </center>

    </div>


</div>
@endif
 