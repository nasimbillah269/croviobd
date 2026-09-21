@extends(general()->theme.'.layouts.app') @section('title')
<title>{{general()->title}} | {{general()->subtitle}}</title>
@endsection @section('SEO')
        <meta name="description" content="{!!general()->meta_description!!}" />
        <meta name="keywords" content="{{general()->meta_keyword}}" />
        <meta property="og:title" content="{{general()->meta_title}}" />
        <meta property="og:description" content="{!!general()->meta_description!!}" />
        <meta property="og:image" content="{{asset(general()->logo())}}" />
        <meta property="og:url" content="{{route('index')}}" />
@endsection 
@push('css')

<style>
    
    @media only screen and (min-width: 768px) {
      .categoryProductGrid .col-md-2 {
            -ms-flex: 0 0 20%;
            flex: 0 0 20%;
            max-width: 20%;
        }
    }
    
</style>

@endpush 

@section('contents')

<div class="categoryPage">
    <div class="container">

            <div class="featured-products">
                <div class="product-grids categoryProductGrid">
                    <p class="categorytitle">{{$homeData->name}}</p>
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
                                         @if($product->weight_amount && $product->weight_unit)
                                        <span style="display:block;text-align:center;">{{$product->productWeight()}}</span>
                                        @endif
                                          <div class="price">
                                               @if($product->regular_price > $product->offerPrice())
                                            <span class="price-cut">{{priceFullFormat($product->regular_price)}}</span>
                                            @endif
                                           
                                            <span class="new-price"> {{priceFullFormat($product->offerPrice())}}</span>
                                        </div>
                                    </div>
                                    <div class="product-cart-btn">
                                             @if($product->stockStatus()==false)
                                    <a href="javascript:void(0)" class="btn addcartbutton" style="background-color: #ec0927;display:block;"><i class="fa mr-1 fa-times"></i> STOCK OUT</a>
                                    @else
                                    <div class="homeFlexButton homeFlexButton_{{$product->id}}">
                                           @include(general()->theme.'.carts.includes.singleAddToCart')
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
                </div>
                
            </div>
        </div>
    </div>
</div>





@endsection @push('js') @endpush