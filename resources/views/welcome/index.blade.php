@extends(general()->theme.'.layouts.app') @section('title')
<title>{{general()->title}} | {{general()->subtitle}}</title>
@endsection @section('SEO')
        <meta name="description" content="{!!general()->meta_description!!}" />
        <meta name="keywords" content="{{general()->meta_keyword}}" />
        <meta property="og:title" content="{{general()->meta_title}}" />
        <meta property="og:description" content="{!!general()->meta_description!!}" />
        <meta property="og:image" content="{{asset(general()->logo())}}" />
        <meta property="og:url" content="{{route('index')}}" />
@endsection @push('css')
<style>
    header.sticky-header:not(.stick) .ctgMenuList {
      display: block;
    }

</style>
@endpush 

@section('contents')

<!--Slider Part Include Start-->
<div class="homeFrist">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @if(menu('Home Category'))
                <div class="homePageCategoryMain">
              
                        <div class="category-wrapper">
                            <!-- Sidebar Heading -->
                            <h3 class="sidebar-title">All Categories</h3>
                
                            <!-- Sidebar Category List -->
                            <ul class="category-list">
                                @foreach(menu('Home Category')->subMenus as $menu)
                                    <li class="category-item">
                                        <a href="{{ asset($menu->munuLink()) }}">
                                            <div class="category-left">
                                                <!-- Skeleton Loader -->
                                                <div class="skeleton skeleton-category"></div>
                            
                                                <!-- Lazy Image -->
                                                <img data-src="{{ asset($menu->munuImg()) }}"
                                                     alt="{{ $menu->munuName() }}"
                                                     class="category-icon lazy lazy-category">
                                                
                                                <span class="category-name">{{ $menu->munuName() }}</span>
                                            </div>
                                            <span class="category-arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                   
                </div>
                @endif
            </div>
            <div class="col-md-9">
                
                @include(general()->theme.'.layouts.slider')
            </div>
        </div>
    </div>
</div>




@if(menu('Home Category'))
<div class="homePageCategoryMains">
    <div class="container">
        <h3>Categories</h3>
        <div class="cgt-slider">
              @foreach(menu('Home Category')->subMenus as $menu)
            <div class="slick-box">
                <div class="homeCtgGrid">
                    <a href="{{asset($menu->munuLink())}}">
                        <img src="{{asset($menu->munuImg())}}" alt="category-blog" />
                        <h5>{{$menu->munuName()}}</h5>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
  





@foreach($homeDatas as $homedata)
    @if($homedata->banner_status)
    <div class="homeBanner">
        <div class="container">
            <div class="homeBannerImg">
                <img src="{{asset($homedata->banner())}}" alt="{{$homedata->name}}" />
            </div>
        </div>
    </div>
    @endif

    <div class="homePageProduct">
    <div class="container">
        <div class="homePageHeader">
            <h4>{{$homedata->name}}</h4>
            <!--<a href="{{route('promotion',$homedata->id)}}" class="viewAllLink">View All</a>-->
        </div>
        <div class="homePageProductLayout">
            <div class="products-slider">
                @foreach($homedata->products() as $product)
                <div class="slick-box">
                    @include(general()->theme.'.products.includes.productCard1')
                </div>
                @endforeach
            </div>
        </div>
    </div>
    </div>

  @endforeach

<div class="homePageProduct">
    <div class="container">
        <div class="homePageHeader">
            <h4>Explore latest</h4>
        </div>
        <div class="homePageProductLayout">
            <div class="row products-load productRow">
                
                @foreach($latestProducts as $product)
                <div class="col-md-3 col-6">
                    @include(general()->theme.'.products.includes.productCard1')
                </div>
                @endforeach
                <div class="col-12">
                    <span style="display: block;text-align: center;"><img class="loadMoreProduct" data-page="{{$latestProducts->lastPage()}}" data-url="{{route('index')}}" src="{{asset('medies/loading.gif')}}" ></span>
                </div>
            </div>
                
        </div>
    </div>
</div>

@endsection 

@push('js') 

<script>

window.dataLayer = window.dataLayer || [];

window.dataLayer.push({
    event: "page_view",

    page: {
        page_title: document.title,
        page_location: window.location.href,
        page_type: "home"
    }
    
});




</script>




<script>

    $(function () {

        const $loader = $('.loadMoreProduct');
        const totalPage = parseInt($loader.data('page'), 1);
        const baseUrl   = $loader.data('url');
    
        let page    = 1;
        let loading = false;
    
        function isLoaderVisible() {
            const loaderTop = $loader.offset().top;
            const viewBottom = $(window).scrollTop() + $(window).height();
            return loaderTop < viewBottom; // top of loader in viewport
        }
    
        function loadMore() {
            if (loading || page >= totalPage) return;
            loading = true;
            page++;
    
            setTimeout(function () {
                $.ajax({
                    url: baseUrl + '?page=' + page,
                    type: 'GET',
                    success: function (res) {
                        $('.products-load .col-12').before(res.html);
    
                        // hide loader if last page reached or server says no more
                        if (page >= totalPage || !res.hasMore) {
                            $loader.hide();
                            $(window).off('scroll.loadMore'); // stop scroll listener
                        }
    
                        loading = false;
                        lazyLoadImages(".lazy", "skeleton");
                        $(".skeleton").fadeOut(300);
                    },
                    error: function () {
                        loading = false;
                    }
                });
            }, 200); // 0.2 sec delay
        }
    
        // Initial check + on scroll
        $(window).on('scroll.loadMore', function () {
            if (isLoaderVisible()) {
                loadMore();
            }
        });
    
        // Also check once in case loader is already visible on page load
        if (isLoaderVisible()) {
            loadMore();
        }
    
    });


// $(function(){
//     const loader = document.querySelector('.loadMoreProduct');
//     let page = 1;
//     let loading = false;

//     const observer = new IntersectionObserver(entries => {
//         entries.forEach(entry => {
//             if (entry.isIntersecting && !loading) {
//                 loading = true;
//                 page++;
                
//                 setTimeout(function () {
                    
//                     $.ajax({
//                         url: loader.dataset.url + '?page=' + page,
//                         type: 'GET',
//                         success: function (res) {
//                             // ধরুন সার্ভার Blade partial বা HTML রিটার্ন করছে
//                             $('.products-load .col-12').before(res.html); 
                            
//                             // যদি আর কোনো ডেটা না থাকে লোডার হাইড করুন
//                             if (!res.hasMore) {
//                                 observer.unobserve(loader);
//                                 $(loader).hide();
//                             }
//                             loading = false;
//                             lazyLoadImages(".lazy", "skeleton");
    
//                             $(".skeleton").fadeOut(300, function () {
//                             });
    
//                         },
//                         error: function(){
//                             loading = false;
//                         }
//                     });
                    
//                 }, 200);
//             }
//         });
//     }, {rootMargin: '0px 0px 100px 0px'}); // 100px আগে থেকে লোড ট্রিগার

//     observer.observe(loader);
// });

</script>




@endpush