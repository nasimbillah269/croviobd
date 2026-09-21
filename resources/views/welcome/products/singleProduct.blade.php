@extends(App\Models\General::first()->theme.'.layouts.app') @section('title')
<title>{{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
@endsection @section('SEO')
<meta name="description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}" />
<meta name="keywords" content="{{App\Models\General::latest()->first()->meta_key}}" />
<meta property="og:title" content="{{App\Models\General::latest()->first()->name}}" />
<meta property="og:description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}" />
<meta property="og:image" content="{!!App\Models\General::latest()->first()->meta_dsc!!}" />
<meta property="og:url" content="{{route('index')}}" />
@endsection @push('css')

<style>
    p.skuPriseValue {
        display: none;
    }
    p.skuPriseValue.selected {
        display: block;
    }
    div.product-attr ul li {
        display: inline-block;
        list-style: none;
    }
    label.sizeIDValue {
        border: 1px solid #cfcfcf;
        border-radius: 3px;
        cursor: pointer;
        text-align: center;
    }
    label.sizeIDValue span {
        min-width: 40px;
        padding: 5px;
        display: inline-block;
    }

    label.colorIDValue {
        border: 1px solid #cfcfcf;
        border-radius: 3px;
        cursor: pointer;
        text-align: center;
    }
    div.product-attr ul li img {
        margin: 2px;
        width: 40px;
    }

    label.variationcheckid.selected {
        border: 1px solid #ff5722;
    }
</style>


@endpush 

@section('contents')
<div class="container">
    

<div class="row" style="background: #e7e7e7; margin: 0;">
    <!--<div class="col-md-3 hidemobiletopsidebar" style="padding: 0;">-->
    <!--@include(App\Models\General::first()->theme.'.layouts/topsidebarmenu')-->
    <!--</div>-->
    <div class="col-md-12" style="background: white; border-right: 1px solid #c6c6c6; border-bottom: 1px solid #c6c6c6;">
        <p style="margin: 0; cursor: pointer; padding: 5px;">
            @if(!$product->cat_id==null && $product->cat)
            <a style="text-decoration: none; color: gray; font-size: 14px;" href="{{ route('productCategory',['category',$product->cat_id,Str::slug($product->cat->title)]) }}">
                @if(session()->get('locale')=='bn') {{$product->cat->meta_title==null?$product->cat->title:$product->cat->meta_title}} @else {{$product->cat->title}} @endif
            </a>
            @endif @if(!$product->subcat_id==null && $product->subcat)
            <i style="font-size: 13px;color: #979797;" class="fa fa-angle-right"></i>
            <a style="text-decoration: none; color: gray; font-size: 14px;" href="{{ route('productCategory',['subcategory',$product->subcat->id,Str::slug($product->subcat->title)]) }}">
                @if(session()->get('locale')=='bn') {{ $product->subcat->meta_title==null?$product->subcat->title:$product->subcat->meta_title}} @else {{$product->subcat->title}} @endif
            </a>
            @endif @if(!$product->subsubcat_id==null && $product->subsubcat)
            <i style="font-size: 13px;color: #979797;" class="fa fa-angle-right"></i>
            <a style="text-decoration: none; color: gray; font-size: 14px;" href="{{ route('productCategory',['subsubcategory',$product->subsubcat->id,Str::slug($product->subsubcat->title)]) }}">
                @if(session()->get('locale')=='bn') {{$product->subsubcat->meta_title==null?$product->subsubcat->title:$product->subsubcat->meta_title}} @else {{$product->subsubcat->title}} @endif
            </a>
            @endif
        </p>
    </div>
</div>

<div class="row">
    <div class="col-lg-3">
        @if(Auth::check()) @if(Auth::user()->admin==true)
        <div>
            <a class="btn btn-sm" href="{{route('admin.productsEdit',$product->id)}}" style="background-color: #29a7d9; border-color: #29a7d9; color: white;">Product Edit</a>
        </div>
        @elseif(Auth::user()->business==true) @if($seller =App\Models\Seller::find($product->seller_id)) @if($seller->user_id==Auth::id())
        <div>
            <a class="btn btn-sm" href="{{route('business.productsEdit',$product->id)}}" style="background-color: #29a7d9; border-color: #29a7d9; color: white;">Product Edit</a>
        </div>
        @endif @endif @endif @endif
    </div>
</div>

<div class="row" style="margin: 0; margin-top: 10px; padding: 0px;">
    <div class="col-lg-5 col-md-4" style="padding: 5px;">
        @include(App\Models\General::first()->theme.'.products.includes.productslider')
    </div>
    <div class="col-lg-7 col-md-8" style="padding: 5px;">
        @include(App\Models\General::first()->theme.'.products.includes.productbasicinfo')
    </div>
</div>

<div style="padding: 0px">
    <style>
        p.nav-link.Productdescription.active {
            border: none !important;
            border-bottom: 2px solid #fceb28 !important;
            font-weight: bold;
        }
    </style>

    <div class="row">
        <div class="col-md-8">
            <div class="productdiv" style="">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <p class="nav-link Productdescription active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true" style="cursor: pointer; margin: 0;">
                            {{session()->get('locale')=='bn'?'পণ্যের বিবরণ':'Product Details'}}
                        </p>
                    </li>
                    <li class="nav-item">
                        <p class="nav-link Productdescription" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false" style="cursor: pointer; margin: 0;">
                            {{session()->get('locale')=='bn'?'স্পেসিফিকেশন':'Specification'}}
                        </p>
                    </li>
                    <li class="nav-item">
                        <p class="nav-link Productdescription" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false" style="cursor: pointer; margin: 0;">
                            {{session()->get('locale')=='bn'?'রিভিউ':'Reviews'}}
                        </p>
                    </li>

                </ul>
                <div class="tab-content" id="myTabContent" style="overflow: auto;">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="productnoticelist">
                            @if($product->description==null)
                            <p style="font-weight: bold;">No Description is available</p>
                            @else
                            <p style="font-weight: bold;">Product Description Of {{ $product->title }}</p>
                            {!!$product->description!!} @endif
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="productnoticelist">
                            @if(!$product->excerpt==null) {!!$product->excerpt!!} @else
                            <p>No Specification</p>
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                        <div class="productnoticelist">
                            @include(App\Models\General::first()->theme.'.products.includes.realatedReview',['review'=>$review, 'ratings'=>$ratings])
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </div>

        <div class="col-lg-4 col-md-4">
             {{--@include(App\Models\General::first()->theme.'.products.includes.importantNote')--}}
            <div class="reviewsidebar">
                @include(App\Models\General::first()->theme.'.products.includes.reviewstar',['ratings'=>$ratings, 'avg_rate'=>$avg_rate])
            </div>
           
        </div>
        
    </div>
    @include(App\Models\General::first()->theme.'.products.includes.relatedProducts')
</div>

</div>
@endsection @push('js')
<script src="http://test.bazaarbangladesh.com/public/js/imgezoom/zoomsl.js"></script>

<script>
    $(document).ready(function () {
        
        $(document).on('change','.payment_method',function(){
            var name  =$(this).val();

            if(name=='bKash'){
                $('.transection').show();
            }else{

              $('.transection').hide();  
            }
            
            
        });
        
        
        $(".CategoySlider-owl").owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            autoplay: true,
            autoplayTimeout: 3000,
            responsive: {
                0: {
                    items: 2,
                },
                600: {
                    items: 3,
                },
                1000: {
                    items: 6,
                },
            },
        });

        $(".block__pic").imagezoomsl({
            zoomrange: [3, 3],
        });
        $(".singleproductgallery a").click(function () {
            var largeImage = $(this).attr("data-full");
            $(".selected").removeClass();
            $(this).addClass("selected");
            $(".full img").hide();
            $(".full img").attr("src", largeImage);
            $(".full img").fadeIn();
        }); // closing the listening on a click
        $(".full img").on("click", function () {
            var modalImage = $(this).attr("src");
            $.fancybox.open(modalImage);
        });
    }); //closing our doc ready
</script>
<script>
    $(document).ready(function () {
        //  a=1234;
        //  b=23;

        $(document).on("click", "#increase", function () {
            var m = $("#increase").attr("data-max");
            var q = $("#number").val();
            q = isNaN(q) ? 1 : q;
            var access = m - q;
            if (access > 0) {
                q++;
                $("#number").val(q);
            }
        });

        $(document).on("click", "#decrease", function () {
            var m = $("#decrease").attr("data-min");
            var q = $("#number").val();
            q = isNaN(q) ? 1 : q;
            var access = q - m;
            if (access > 0) {
                q--;
                $("#number").val(q);
            }
        });
        
        $(document).on("click", ".sizeIDValue", function () {
            $('.sizeIDValue').removeClass('selected');
            $(this).addClass('selected');
        });

        $(document).on("click", ".colorIDValue", function () {
            $('.colorIDValue').removeClass('selected');
            $(this).addClass('selected');
        });



    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        ////////////////////////////////////////////////////

        $("form.cat-subcat-form").submit(function (e) {
            e.preventDefault();
            var alldata = $(this).serialize(); // serialize data
            var url = $(this).attr("action");
            $.ajax({
                url: url,
                method: "POST",
                data: $(this).serialize(),
            })
                .done(function (data) {
                    $(".cartmessage").empty().append('<div style="border: 1px solid #28a745;padding: 5px 10px;">Product Add Success</div>');
                    if (data.btntype == 2) {
                        window.location = "";
                    }
                    $(".totalitemsqty").empty().append(data.cartTotalcount);
                })
                .fail(function () {
                    alert("error");
                });
        });

        $("#submitBtn").click(function () {
            $("#btntype").val(1);
            $("form.cat-subcat-form").trigger("submit");
        });
        $("#submitBtn2").click(function () {
            $("#btntype").val(2);
            $("form.cat-subcat-form").trigger("submit");
        });

        /////////////////////////////////////////////////
    });
</script>

@endpush
