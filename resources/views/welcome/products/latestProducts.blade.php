@extends(general()->theme.'.layouts.app') @section('title')
<title>{{general()->title}} | {{general()->subtitle}}</title>
@endsection @section('SEO')
<meta name="description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta property="og:title" content="{{general()->meta_title}}" />
<meta property="og:description" content="{!!general()->meta_description!!}" />
<meta property="og:image" content="{!!general()->meta_description!!}" />
<meta property="og:url" content="{{route('index')}}" />
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
                            <li>Latest Products</li>
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
                    <p class="categorytitle">Latest Products</p>
                    <div class="row">
                        @foreach($products as $product)
                        <div class="col-md-3" style="padding: 15px;">
                            @include(general()->theme.'.products.includes.productCard1')
                        </div>
                        @endforeach
                    </div>
                </div>
                
                
                
               
                <div class="pagination-part" style="margin-top: 20px;">
                    
                    {{$products->links('pagination')}}
                     {{--
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                            </li>
                        </ul>
                    </nav>
                     --}}
                </div>
               
            </div>
        </div>
    </div>
</div>

@endsection 

@push('js') 

@endpush