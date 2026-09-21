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
                            @if($category->parent)
                            <li><a href="{{route('productCategory',$category->parent->slug)}}">{{$category->parent->name}}</a>/</li>
                            @endif
                            <li><a href="{{route('productCategory',$category->slug)}}">{{$category->name}}</a></li>
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
                <div class="product-grids categoryProductGrid">
                    <p class="categorytitle">{{$category->name}}</p>
                    <div class="row">
                        @foreach($products as $product)
                        <div class="col-md-3" style="padding: 15px;">
                            <div class="product-wrapper aos-init aos-animate" data-aos="fade-up">
                                <div class="product-img">
                                    <a href="{{route('productView',$product->slug?:Str::slug($product->name))}}"><img src="{{asset($product->image())}}" alt="{{$product->name}}" /></a>
                                    <div class="product-cart-items">
                                       
                                        <a href="#" class="cart cart-item">
                                            <span>
                                              <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            </span>
                                        </a>
                                       
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="ratings">
                                        <span>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <div class="product-description">
                                        <a href="{{route('productView',$product->slug?:Str::slug($product->name))}}" class="product-details">{{Str::limit($product->name,15)}} </a>
                                          <div class="price">
                                               @if($product->regular_price > $product->offerPrice())
                                            <span class="price-cut">{{priceFullFormat($product->regular_price)}}</span>
                                            @endif
                                           
                                            <span class="new-price"> {{priceFullFormat($product->offerPrice())}}</span>
                                        </div>
                                    </div>
                                    <div class="product-cart-btn">
                                             @if($product->stockStatus()==false)
                                    <a href="javascript:void(0)" class="btn addcartbutton" style="background-color: #ec0927;"><i class="fa mr-1 fa-times"></i> STOCK OUT</a>
                                    @else
                                    <span class="CMessageSuccess{{$product->id}}" style="display:block;"></span>
                                   <a href="javascript:void(0)" class="btn addcartbutton LoadingaddCartHide_{{$product->id}}" style="background-color: #ffffff00;display:none;"><img src="{{asset('medies/loading.gif')}}"></a>
                                   <!--<a href="javascript:void(0)" class="btn addcartbutton addCartShow_{{$product->id}} singleaddCart  newAddTo" data-id="{{$product->id}}" data-url="{{route('addToCart',$product->id)}}">-->
                                   <!--    <i class="fa mr-1 fa-shopping-cart"></i> ADD TO CART-->
                                       
                                   <!--  </a>-->
                                        <div class="homeFlexButton">
                                    <div class="quantity">
                                        <input type="button" value="-" class="qtyminus Quantityminus" data-min="{{$product->productMinQty()}}" data-id="{{$product->id}}" field="quantity" style="height: 30px;" />
                                        <input   name="quantity" value="{{$product->productMinQty()}}" class="qty qty_{{$product->id}}" style="height: 30px;" />
                                        <input type="button" value="+" class="qtyplus Quantityplus" data-max="{{$product->productMaxQty()}}" data-id="{{$product->id}}" field="quantity" style="height: 30px;" />
                                    </div>
                                     <a href="javascript:void(0)" class="addcartbutton product-btn addCartShow_{{$product->id}} singleaddCart" data-id="{{$product->id}}" data-url="{{route('addToCart',$product->id)}}">
                                            <span>
                                               <i class="fa fa-plus" aria-hidden="true"></i>
                                            </span>
                                            <span class="btn-text">Buy Now</span>
                                        </a>
                                    </div>
                                   @endif
                                        
                                    </div>
                                </div>
                            </div>
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

@push('js') @endpush