

@extends(general()->theme.'.layouts.app') @section('title')
<title>Search Product - {{general()->title}}</title>
@endsection @section('SEO')
    <meta name="description" content="{!!general()->meta_description!!}" />
    <meta name="keywords" content="{{general()->meta_keyword}}" />
    <meta property="og:title" content="{{general()->meta_title}}" />
    <meta property="og:description" content="{!!general()->meta_description!!}" />
    <meta property="og:image" content="{asset(general()->logo())}" />
    <meta property="og:url" content="{{route('productSearch')}}" />
@endsection @push('css')
@endpush 

@section('contents')

<div class="categoryPage">
    <div class="container">
        <div class="products-section">
            <div class="categoryheader">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="categoryblog-lists">
                            <li><a href="{{route('index')}}">Home</a>/</li>
                            <li><a href="javascript:void(0)">Search</a></li>
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <ul class="sortlists">
                            <li>Showing 1–20 of 168 results</li>
                            <li>
                                <select name="" id="">
                                    <option value="">Sort By Name</option>
                                    <option value="">Sort By Name</option>
                                    <option value="">Sort By Name</option>
                                    <option value="">Sort By Name</option>
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="featured-products">
                <div class="product-grids categoryProductGrid">
                    <p class="categorytitle">Search Product</p>
                    <div class="row productRow">
                        @foreach($products as $product)
                        <div class="col-md-3 col-6" >
                            @include(general()->theme.'.products.includes.productCard1')
                        </div>
                    @endforeach
            
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection 

@push('js') 
<script>
    $(window).on("load", function () {
            $(".skeleton").fadeOut(500, function () {
                // $(this).remove();
            });
        });
</script>
@endpush