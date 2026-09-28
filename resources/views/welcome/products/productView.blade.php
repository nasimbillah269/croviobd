@extends(general()->theme.'.layouts.app') @section('title')
<title>{{$product->name}} - {{general()->title}}</title>
@endsection @section('SEO')
        <meta name="description" content="{!!$product->seo_desc?:$product->name.' '.general()->meta_description!!}" />
        <meta name="keywords" content="{{$product->seo_keyword?:$product->name.' '.general()->meta_keyword}}" />
        <meta property="og:title" content="{{$product->seo_title?:$product->name}}" />
        <meta property="og:description" content="{!!$product->seo_desc?:$product->name.' '.general()->meta_description!!}" />
        <meta property="og:image" content="{{asset($product->image())}}" />
        <meta property="og:url" content="{{route('productView',$product->slug?:Str::slug($product->name))}}" />
@endsection 

@push('css')


<link rel="stylesheet" type="text/css" href="{{asset('magnificjs/xzoom.css')}}" media="all" /> 
<link type="text/css" rel="stylesheet" media="all" href="{{asset('magnificjs/fancybox/source/jquery.fancybox.css')}}" />
<link type="text/css" rel="stylesheet" media="all" href="{{asset('magnificjs/magnific-popup/css/magnific-popup.css')}}" />


<style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    textarea:focus, input:focus{
    outline: none;
    }
    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
    .singleBuyCart {
        background: #ff005e;
        display: block;
        color: white;
        padding: 8px 0px;
    }
    
    .singleBuyCart:hover {
        color: white;
        box-shadow: 5px 7px 5px #ccc;
    }
    a.singleaddCart:hover{
        color: white;
    }
    .productVariationBox{
        margin-top: 8px;
        margin-bottom: 20px;
    }
    .variationGroup{
        margin-bottom: 18px;
    }
    .variationGroup label{
        display:block;
        font-weight: 600;
        font-size: 13px;
        letter-spacing: .3px;
        text-transform: uppercase;
        color: #555;
        margin-bottom: 10px;
    }
    .variationOptions{
        display:flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
    }
    .variationOption{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        position: relative;
        min-width: 42px;
        height: 42px;
        padding: 0 14px;
        border: 1px solid #e2e2e2;
        border-radius: 6px;
        cursor: pointer;
        background: #fff;
        color: #333;
        font-size: 14px;
        font-weight: 500;
        user-select: none;
        transition: all .15s ease-in-out;
        box-shadow: 0 1px 2px rgba(0,0,0,.04);
    }
    .variationOption:hover{
        border-color: #ff005e;
        color: #ff005e;
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0,0,0,.08);
    }
    .variationOption.active{
        border-color: #ff005e;
        background: #fff0f5;
        color: #ff005e;
        box-shadow: 0 0 0 1px #ff005e inset;
    }
    .variationOption.variationColor{
        width: 36px;
        height: 36px;
        min-width: 36px;
        padding: 0;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 0 0 1px #e2e2e2, 0 1px 3px rgba(0,0,0,.08);
    }
    .variationOption.variationColor:hover{
        transform: translateY(-1px);
        box-shadow: 0 0 0 1px #ff005e, 0 3px 6px rgba(0,0,0,.12);
    }
    .variationOption.variationColor.active{
        box-shadow: 0 0 0 2px #ff005e, 0 2px 5px rgba(0,0,0,.12);
    }
    .variationOption.variationColor.active::after{
        content: "\2713";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        color: #fff;
        mix-blend-mode: difference;
        font-size: 15px;
        font-weight: 700;
    }
    .variationOption.variationImage{
        width: 54px;
        height: 54px;
        padding: 3px;
        border-radius: 8px;
    }
    .variationOption.variationImage img{
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 5px;
    }
    .variationMessage{
        font-size: 13px;
        padding: 8px 12px;
        margin-bottom: 12px;
        border-radius: 6px;
        background: #fdecec;
        border: 1px solid #f5c2c2;
    }
    .variationOption.variationDisabled{
        opacity: 0.35;
        text-decoration: line-through;
    }
    .variationSelectedName{
        font-weight: 400;
        color: #555;
    }
    .addcartDisabled{
        opacity: 0.5;
        pointer-events: none;
    }
</style>


@endpush 

@section('contents')

@php $mainProduct = $product; @endphp

<div class="singleProductPage">
    <div class="container">
        <div class="products-section">
            <div class="categoryheader">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="categoryblog-lists">
                            
                            <li><a href="{{route('index')}}">Home</a>/</li>
                            @foreach($product->productCategories as $i=>$ctg)
                            <li><a href="{{route('productCategory',$ctg->slug)}}">{{$ctg->name}}</a>/</li>
                            @endforeach
                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container" >
                <div class="row">
                    <div class="col-md-6">
                        <div class="xzoom-container">
                           
                          
                          <img class="xzoom4" id="xzoom-fancy" src="{{asset($product->image())}}" xoriginal="{{asset($product->image())}}" />
                          
                            <div class="xzoom-thumbs">
                                <a href="{{asset($product->image())}}"><img class="xzoom-gallery4"  src="{{asset($product->image())}}"  xpreview="{{asset($product->image())}}" title="The description goes here"></a>
                                @foreach($product->galleryFiles as $gall)
                                    <a href="{{asset($gall->image())}}"><img class="xzoom-gallery4"  src="{{asset($gall->image())}}" title="The description goes here"></a>
                                @endforeach
                                @if($product->variation_status && $product->productAttibutes->count() > 0)
                                @foreach(collect($product->variationSkuMap())->pluck('image')->filter()->unique() as $variantImage)
                                    <a href="{{$variantImage}}"><img class="xzoom-gallery4"  src="{{$variantImage}}" title="{{$product->name}}"></a>
                                @endforeach
                                @endif
                          </div>
                          

                          <!--<img class="xzoom4" id="xzoom-fancy" src="{{asset($product->image())}}" xoriginal="{{asset($product->image())}}" />-->
                      
                              
                         
                          
                         
                          
                        </div>
                        <!--<div class="alertdiv">-->
                        <!--    @if($product->stockStatus()==false)-->
                        <!--    <img src="{{asset('batikrom/images/stockOut.png')}}">-->
                        <!--    @endif-->
                        <!--</div>-->
                        <!--<img src="{{asset($product->image())}}"  alt="{{$product->name}}" style="max-width:100%;" />-->
                    </div>

                    <div class="col-md-6">
                        <div class="products-process">
                            @if(Auth::check())
                                
                                @if(Auth::user()->admin)
                                    <a href="{{route('admin.productsEdit',$product->id)}}" target="_blank" class="btn btn-sm btn-success" >Edit</a>
                                @endif
                                
                            @endif
                            <h3 class="singleProducttitle">{{$product->name}}</h3>

                     
                            
                            <h5 class="singlPrice"
                            data-currency="{{general()->currency}}"
                            data-currency-position="{{general()->currency_position}}"
                            data-decimal="{{general()->currency_decimal}}"
                            data-base-price="{{$product->final_price}}">
                            @if($product->regular_price > $product->final_price)
                            <del>{{priceFullFormat($product->regular_price)}}</del>
                            @endif
                            {{priceFullFormat($product->final_price)}}
                            </h5>

                               @if($product->stockStatus()==false) @else
                            <p class="stockAvailable">
                                Available In Stock: <span class="stockAvailableQty">{{$product->quantity}}</span>
                            </p>
                            @endif
                            
                            <p class="single-excerpt">
                                {!!$product->short_description!!}
                            </p>
                            
                            
                            <!-- <div class="cuponBox">-->
                            <!--        <p>-->
                                    
                            <!--        <span style="color: #000; font-weight: bold">300</span> টাকার বেশি প্রোডাক্ট ক্রয় করলে পেয়ে যাচ্ছেন   <span style="color: red; font-weight: bold" >     20</span> টাকা ডিসকাউন্ট কুপন-->
                                    
                                    
                            <!--        </p>-->
                            <!--          <div class="copy-container">-->
                            <!--              <input type="text" class="form-control" id="textInput" placeholder="Eid20">-->
                            <!--              <button class="btn copy-btn" id="copyBtn">Copy </button>-->
                            <!--            </div>-->
                            <!--</div>-->
                            
                            
                            <form action="" class="addToCartProduct_{{$product->id}}">

                            @if($product->variation_status && $product->productAttibutes->count() > 0)
                            <div class="productVariationBox"
                            data-product-id="{{$product->id}}"
                            data-base-price="{{$product->final_price}}"
                            data-base-qty="{{$product->quantity}}">
                                @foreach($product->productAttibutes->unique('reff_id') as $group)
                                @if($group->attribute)
                                @php
                                    $isColorGroup = $group->attribute->view==2 || str_contains(strtolower($group->attribute->name),'colo');
                                @endphp
                                <div class="variationGroup" data-color-group="{{$isColorGroup?1:0}}">
                                    <label>{{ucfirst($group->attribute->name)}}: <span class="variationSelectedName"></span></label>
                                    <div class="variationOptions">
                                        @foreach($product->productAttibutes->where('reff_id',$group->reff_id) as $item)
                                        @if($item->attributeItem)
                                        <span class="variationOption @if($isColorGroup) variationColor @elseif($group->attribute->view==3) variationImage @else variationText @endif"
                                        data-item-id="{{$item->attributeItem->id}}"
                                        data-name="{{$item->attributeItem->name}}"
                                        title="{{$item->attributeItem->name}}"
                                        @if($isColorGroup)
                                        style="background-color: {{$item->value_1?:($item->attributeItem->icon?:strtolower($item->attributeItem->name))}};"
                                        @endif
                                        >
                                        @if($isColorGroup)
                                        @elseif($group->attribute->view==3)
                                        <img src="{{asset($item->image())}}" alt="{{$item->attributeItem->name}}">
                                        @else
                                        {{$item->attributeItem->name}}
                                        @endif
                                        </span>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                <div class="variationMessage text-danger" style="display:none;"></div>
                                <input type="hidden" name="sku_id" class="variationSkuIdInput" value="">
                            </div>
                            @endif


                            <div class="qountity-plus">
                                <span class="MessageQty{{$product->id}}"></span>
                                @if($product->stockStatus()==false)
                                <a href="javascript:void(0)" class="btn addcartbutton" style="color: #f00; padding: 5px 45px;">STOCK OUT</a>
                                @else
                                <div class="row">
                                    <div class="col-md-12 col-7">
                                        <div class="quantity">
                                            <input type="button" value="-" class="qtyminus Quantityminus" data-min="{{$product->productMinQty()}}" data-id="{{$product->id}}" field="quantity" style="height: 40px;" />
                                            @if($hCart =hasCart(request()->cookie('carts'),$product->id))
                                            <input type="number"  name="quantity" value="{{$hCart->quantity}}" class="qty qty_{{$product->id}}" style="height: 40px;" />
                                            @else
                                            <input type="number"  name="quantity" value="{{$product->productMinQty()}}" class="qty qty_{{$product->id}}" style="height: 40px;" />
                                            @endif
                                            <input type="button" value="+" class="qtyplus Quantityplus" data-max="{{$product->productMaxQty()}}" data-id="{{$product->id}}" field="quantity" style="height: 40px;" />
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-5">
                                        <a href="javascript:void(0)" class="btn singleBuyCart addCartShow_{{$product->id}}" data-id="{{$product->id}}" data-url="{{route('addToCart',[$product->id,'orderNow'=>true])}}">
                                            <img src="{{asset('medies/loading.gif')}}"style="display:none;position: absolute;left: 20px;top: 5px;">
                                            <span>Buy Now</span>
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <a href="javascript:void(0)" class="btn singleaddCart" data-id="{{$product->id}}" onclick="addToCart({{ json_encode($product) }})" data-url="{{route('addToCart',$product->id)}}" >
                                            <img src="{{asset('medies/loading.gif')}}" style="display:none;position: absolute;left: 20px;top: 5px;">
                                            <span>Add to Cart</span>
                                        </a>
                                    </div>
                                </div>
                                @endif
                                <span class="MessageSuccess{{$product->id}}" style="margin-top: 10px;display: block;"></span>
                            </div>
                            </form>

                            @if($mainProduct->variation_status && $mainProduct->productAttibutes->count() > 0)
                            <script>
                            (function(){
                                var variationMap = @json($mainProduct->variationSkuMap());
                                var productId = "{{ $mainProduct->id }}";
                                var box = document.querySelector('.productVariationBox[data-product-id="'+productId+'"]');
                                if(!box) return;

                                var groups = box.querySelectorAll('.variationGroup');
                                var skuInput = box.querySelector('.variationSkuIdInput');
                                var msgEl = box.querySelector('.variationMessage');
                                var priceEl = document.querySelector('.singlPrice');
                                var stockEl = document.querySelector('.stockAvailableQty');
                                var qtyPlus = document.querySelector('.qtyplus.Quantityplus[data-id="'+productId+'"]');
                                var qtyInput = document.querySelector('.qty_'+productId);
                                var addButtons = document.querySelectorAll('.singleaddCart[data-id="'+productId+'"], .singleBuyCart[data-id="'+productId+'"]');

                                var currency = priceEl ? priceEl.getAttribute('data-currency') : '';
                                var currencyPos = priceEl ? priceEl.getAttribute('data-currency-position') : '0';
                                var decimals = priceEl ? parseInt(priceEl.getAttribute('data-decimal')) : 0;

                                function formatPrice(amount){
                                    var fixed = Number(amount).toFixed(decimals);
                                    var parts = fixed.split('.');
                                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                    var formatted = parts.join('.');
                                    return currencyPos=='0' ? (currency+' '+formatted) : (formatted+' '+currency);
                                }

                                function setAddButtonsEnabled(enabled){
                                    addButtons.forEach(function(b){
                                        b.classList.toggle('addcartDisabled', !enabled);
                                    });
                                }

                                var skuList = [];
                                for(var k in variationMap){ skuList.push(variationMap[k]); }

                                groups = Array.prototype.slice.call(groups);

                                function activeId(g){
                                    var sel = g.querySelector('.variationOption.active');
                                    return sel ? sel.getAttribute('data-item-id') : null;
                                }

                                //SKU contains every selected item of the other groups
                                function compatible(sku, exceptGroup){
                                    return groups.every(function(g){
                                        if(g===exceptGroup) return true;
                                        var id = activeId(g);
                                        return !id || sku.items.indexOf(id)!==-1;
                                    });
                                }

                                //Disable options not available with current selection,
                                //hide a group (e.g. Size) when the selected Color has no item of it
                                function refreshAvailability(){
                                    for(var pass=0; pass<groups.length; pass++){
                                        var changed = false;
                                        groups.forEach(function(g){
                                            var options = g.querySelectorAll('.variationOption');
                                            var candidates = skuList.filter(function(sku){ return compatible(sku, g); });
                                            var groupNeeded = false;
                                            options.forEach(function(opt){
                                                var id = opt.getAttribute('data-item-id');
                                                var available = candidates.some(function(sku){ return sku.items.indexOf(id)!==-1; });
                                                if(available) groupNeeded = true;
                                                opt.classList.toggle('variationDisabled', !available);
                                                if(!available && opt.classList.contains('active')){
                                                    opt.classList.remove('active');
                                                    changed = true;
                                                }
                                            });
                                            g.style.display = groupNeeded ? '' : 'none';
                                        });
                                        if(!changed) break;
                                    }
                                }

                                function currentSelection(){
                                    var ids = [];
                                    var missing = false;
                                    groups.forEach(function(g){
                                        var id = activeId(g);
                                        if(id){ ids.push(id); }
                                        else if(g.style.display!=='none'){ missing = true; }
                                    });
                                    var combo = null;
                                    if(ids.length){
                                        combo = skuList.filter(function(sku){
                                            return sku.items.length===ids.length && ids.every(function(id){ return sku.items.indexOf(id)!==-1; });
                                        })[0] || null;
                                    }
                                    return {combo:combo, missing:missing};
                                }

                                var mainImg = document.getElementById('xzoom-fancy');
                                var defaultImage = mainImg ? mainImg.getAttribute('src') : '';

                                function setMainImage(url){
                                    if(!mainImg || mainImg.getAttribute('src')===url) return;
                                    mainImg.setAttribute('src', url);
                                    mainImg.setAttribute('xoriginal', url);
                                    document.querySelectorAll('.xzoom-thumbs .xzoom-gallery4').forEach(function(t){
                                        t.classList.toggle('xactive', t.parentNode.getAttribute('href')===url);
                                    });
                                }

                                function updateSelectedNames(){
                                    groups.forEach(function(g){
                                        var sel = g.querySelector('.variationOption.active');
                                        var nameEl = g.querySelector('.variationSelectedName');
                                        if(nameEl){ nameEl.textContent = sel ? sel.getAttribute('data-name') : ''; }
                                    });
                                }

                                function updateImage(){
                                    var combo = currentSelection().combo;
                                    if(combo && combo.image){ setMainImage(combo.image); return; }

                                    var colorGroup = box.querySelector('.variationGroup[data-color-group="1"]');
                                    var colorId = colorGroup ? activeId(colorGroup) : null;
                                    if(!colorId) return;
                                    for(var i=0; i<skuList.length; i++){
                                        if(skuList[i].image && skuList[i].items.indexOf(colorId)!==-1){ setMainImage(skuList[i].image); return; }
                                    }
                                    setMainImage(defaultImage);
                                }

                                function updateUI(){
                                    refreshAvailability();

                                    var sel = currentSelection();
                                    var combo = sel.combo;

                                    updateSelectedNames();
                                    updateImage();

                                    if(combo){
                                        skuInput.value = combo.sku_id;
                                        msgEl.style.display='none';

                                        if(priceEl){ priceEl.innerHTML = formatPrice(combo.price); }
                                        if(stockEl){ stockEl.textContent = combo.quantity; }
                                        if(qtyPlus){ qtyPlus.setAttribute('data-max', combo.quantity); }
                                        if(qtyInput && parseInt(qtyInput.value) > combo.quantity){
                                            qtyInput.value = combo.quantity > 0 ? combo.quantity : 1;
                                        }

                                        if(combo.quantity > 0){
                                            setAddButtonsEnabled(true);
                                        }else{
                                            setAddButtonsEnabled(false);
                                            msgEl.textContent = 'This option is out of stock';
                                            msgEl.style.display = 'block';
                                        }
                                    }else if(sel.missing){
                                        skuInput.value='';
                                        msgEl.style.display='none';
                                        setAddButtonsEnabled(false);
                                    }else{
                                        skuInput.value='';
                                        msgEl.textContent = 'This combination is not available';
                                        msgEl.style.display='block';
                                        setAddButtonsEnabled(false);
                                    }
                                }

                                groups.forEach(function(g){
                                    var options = g.querySelectorAll('.variationOption');
                                    if(options.length===1){
                                        options[0].classList.add('active');
                                    }
                                    options.forEach(function(opt){
                                        opt.addEventListener('click', function(){
                                            //Unavailable option clicked: start fresh from this option
                                            if(opt.classList.contains('variationDisabled')){
                                                groups.forEach(function(other){
                                                    if(other!==g){
                                                        other.querySelectorAll('.variationOption').forEach(function(o){ o.classList.remove('active'); });
                                                    }
                                                });
                                            }
                                            options.forEach(function(o){ o.classList.remove('active'); });
                                            opt.classList.add('active');
                                            updateUI();
                                        });
                                    });
                                });

                                updateUI();
                            })();
                            </script>
                            @endif


                            <hr />
                            
                            <div class="proudctVeiwDescription">
                                <h6>Product Description</h6>
                                <p>
                                    {!!$product->description!!}
                                </p>
                            </div>
                            
                            {{--<p class="categorysingle"><b>Category:</b> 
                            @foreach($product->productCategories as $i=>$ctg)
                            {{$i==0?'':'-'}} {{$ctg->name}} 
                            @endforeach
                            </p>--}}
                            
                         
                            
                            {{--<div class="singleSocial">
                                <ul>
                                    <li>
                                        <a href="#" style="background: #3a579a;"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                    </li>
                                    <li>
                                        <a href="#" style="background: #0da8e3;"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                    </li>
                                    
                                    <li>
                                        <a href="#" style="background-image: linear-gradient(#4560ca, #ff5745,#ffd057);"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                                    </li>
                                    <li>
                                        <a href="#" style="background: #1c9a11;"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                                    </li>
                                </ul>
                            </div>--}}
                            
                        </div>
                    </div>
                </div>
            </div>

            {{--<div class="description-parts">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <p class="nav-link Productdescription active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true" style="cursor: pointer; margin: 0;">
                            Additional Information
                        </p>
                    </li>
                    <li class="nav-item">
                        <p class="nav-link Productdescription" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false" style="cursor: pointer; margin: 0;">
                            Reviews
                        </p>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent" style="overflow: auto;">
                    <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="productnoticelist">
                            <p>
                                No Description is available
                            </p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                        <div class="productnoticelist">
                            <p>
                                No Review
                            </p>
                        </div>
                    </div>
                </div>
            </div>--}}


            
        </div>
    </div>
    
    <div class="singleRelated">
        <div class="container">
            
        <div class="section-title" style="margin-bottom: 0">
            <h3>Recommended for you</h3>

        </div>
        
        <div class="slider-one product-grids">
                    @foreach($relatedProducts as $product)
                    <div class="slick-box">
                        
                      @include(general()->theme.'.products.includes.productCard1')
                    
                    
                    </div>
                    @endforeach
                    
                </div>
        </div>
    </div>
</div>

@endsection 

@push('js') 
  <script type="text/javascript" src="{{asset('magnificjs/xzoom.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('magnificjs/fancybox/source/jquery.fancybox.js')}}"></script>
  <script type="text/javascript" src="{{asset('magnificjs/magnific-popup/js/magnific-popup.js')}}"></script>  


<script>

window.dataLayer = window.dataLayer || [];

window.dataLayer.push({
    event: "page_view",

    page: {
        page_title: "{{ $product->name }} - {{ general()->title }}",
        page_location: window.location.href,
        page_type: "product detail"
    }
    
});


// View Item Event
window.dataLayer.push({
    event: "view_item",

    ecommerce: {
        currency: "{{ general()->currency }}",
        value: {{ $product->offerPrice() }},

        items: [
            {
                item_id: "{{ $product->id }}",
                item_name: "{{ $product->name }}",
                affiliation: "{{ general()->title }}",
                discount: {{ $product->discount ?? 0 }},
                index: 0,
                item_variant: "{{ $product->variant ?? '' }}",
                price: {{ $product->offerPrice() }},
                quantity: 1,
                sku: "{{ $product->sku ?? '' }}"
            }
        ]
    }
});
console.log('view item event');

function addToCart(product) {
    window.dataLayer = window.dataLayer || [];

    let qty = parseInt($('#quantity').val(), 10) || 1;

    window.dataLayer.push({
        event: "add_to_cart",

        ecommerce: {
            currency: "{{ general()->currency }}",
            value: Number(product.final_price),

            items: [
                {
                    item_id: product.id,
                    item_name: product.name,
                    affiliation: "{{ general()->title }}",
                    discount: product.discount || 0,
                    index: 0,
                    item_variant: product.variant || "",
                    price: Number(product.final_price),
                    quantity: qty,
                    sku: product.sku || ""
                }
            ]
        }
    });
    
    console.log('ad to card event')
}


</script>


	<script>

    $(document).ready(function() {

        $('.xzoom4, .xzoom-gallery4').xzoom({tint: '#006699', Xoffset: 15,zoomWidth: 200,zoomHeight:200,position: 'lens',  sourceClass: 'xzoom-hidden'});

        //Integration with hammer.js
        var isTouchSupported = 'ontouchstart' in window;

        if (isTouchSupported) {
            //If touch device
            $('.xzoom4').each(function(){
                var xzoom = $(this).data('xzoom');
                xzoom.eventunbind();
            });
            

        $('.xzoom4').each(function() {
            var xzoom = $(this).data('xzoom');
            $(this).hammer().on("tap", function(event) {
                event.pageX = event.gesture.center.pageX;
                event.pageY = event.gesture.center.pageY;
                var s = 1, ls;

                xzoom.eventmove = function(element) {
                    element.hammer().on('drag', function(event) {
                        event.pageX = event.gesture.center.pageX;
                        event.pageY = event.gesture.center.pageY;
                        xzoom.movezoom(event);
                        event.gesture.preventDefault();
                    });
                }

                var counter = 0;
                xzoom.eventclick = function(element) {
                    element.hammer().on('tap', function() {
                        counter++;
                        if (counter == 1) setTimeout(openfancy,100);
                        event.gesture.preventDefault();
                    });
                }

                function openfancy() {
                    if (counter == 2) {
                        xzoom.closezoom();
                        $.fancybox.open(xzoom.gallery().cgallery);
                    } else {
                        xzoom.closezoom();
                    }
                    counter = 0;
                }
            xzoom.openzoom(event);
            });
        });
        
        

        } else {
            //If not touch device

            //Integration with fancybox plugin
            $('#xzoom-fancy').bind('click', function(event) {
                var xzoom = $(this).data('xzoom');
                xzoom.closezoom();
                $.fancybox.open(xzoom.gallery().cgallery, {padding: 0, helpers: {overlay: {locked: false}}});
                event.preventDefault();
            });
           
            //Integration with magnific popup plugin
            // $('#xzoom-magnific').bind('click', function(event) {
            //     var xzoom = $(this).data('xzoom');
            //     xzoom.closezoom();
            //     var gallery = xzoom.gallery().cgallery;
            //     var i, images = new Array();
            //     for (i in gallery) {
            //         images[i] = {src: gallery[i]};
            //     }
            //     $.magnificPopup.open({items: images, type:'image', gallery: {enabled: true}});
            //     event.preventDefault();
            // });
        }
    });

	</script>

@endpush