@extends(general()->theme.'.layouts.app') @section('title')
<title>{{$category->name}} - {{general()->title}}</title>
@endsection @section('SEO')
        <meta name="description" content="{!!general()->meta_description!!}" />
        <meta name="keywords" content="{{$category->name.' '.general()->meta_keyword}}" />
        <meta property="og:title" content="{{$category->name}}" />
        <meta property="og:description" content="{!!$category->name.' '.general()->meta_description!!}" />
        <meta property="og:image" content="{{asset($category->image())}}" />
        <meta property="og:url" content="{{route('productCategory',$category->slug)}}" />
@endsection @push('css')
@endpush 

@section('contents')

<div class="categoryPage">
    <div class="container">
        <div class="products-section">
            <div class="featured-products">
                
                <div class="mobileCtgFillerHead">
                    <p>Shop Proudct</p>
                      <!-- Filter Button -->
                    <button class="btn btn-primary" data-toggle="modal" data-target="#filterModal">
                     Filters <i class="fa fa-filter" aria-hidden="true"></i>
                    </button>
                </div>
            

                  <!-- Filter Sidebar Modal -->
                 <div class="modal right fade" id="filterModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                
                        <div class="modal-header">
                          <h5 class="modal-title">Category Filters</h5>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                
                        <div class="modal-body">
                            
                          <!-- Sort By -->
                          <div class="ctgFillterList">
                            <p><strong>Sort by</strong></p>
                            <ul class="list-unstyled">
                              <li><label><input type="radio" value="all" name="short_by" > All</label></li>
                              <li><label><input type="radio" value="low_to_high" name="short_by"> Low to High</label></li>
                              <li><label><input type="radio" value="high_to_low" name="short_by"> High to Low</label></li>
                              <li><label><input type="radio" value="top_selling" name="short_by"> Top Selling</label></li>
                            </ul>
                          </div>
                
                          <!-- Price Range -->
                          <div class="ctgFillterList priceFilter">
                            <p><strong>Price</strong></p>
                            <div class="slider-container">
                              <div class="track"></div>
                              <div class="range"></div>
                              <div class="slider">
                                <input type="range" min="0" max="0" name="min_price" value="0" class="minRange">
                                <input type="range" min="0" max="{{$topPrice}}" name="max_price" value="{{$topPrice}}" class="maxRange">
                              </div>
                            </div>
                            <div class="d-flex justify-content-between" style="margin-top: 25px;" >
                              <span class="minValue">৳ 0</span>
                              <span class="maxValue">৳ {{$topPrice}}</span>
                            </div>
                        </div>
                
                          <!-- Offers -->
                          <div class="ctgFillterList">
                            <p><strong>Offers</strong></p>
                            <ul class="list-unstyled">
                              <li><label><input type="radio" name="offer" value="best_sale"> Best Price</label></li>
                              <li><label><input type="radio" name="offer" value="discount"> Discount</label></li>
                            </ul>
                          </div>
                          
                        </div>
                      </div>
                    </div>
                  </div>
                                
                
                <div class="productCategoryPage">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="categoryFillterSidebar">
                                <div class="ctgFillterList">
                                    <p>Sort by</p>
                                    <ul>
                                        <li>
                                           <div class="form-check">
                                              <input class="form-check-input" type="radio" value="all" name="short_by" id="short_by_all">
                                              <label class="form-check-label" for="short_by_all">
                                                All
                                              </label>
                                            </div>
                                        </li>
                                        <li>
                                           <div class="form-check">
                                              <input class="form-check-input" type="radio" value="low_to_high" name="short_by" id="short_by_low">
                                              <label class="form-check-label" for="short_by_low">
                                                Low to high
                                              </label>
                                            </div>
                                        </li>
                                        <li>
                                           <div class="form-check">
                                              <input class="form-check-input" type="radio" value="high_to_low" name="short_by" id="short_by_high">
                                              <label class="form-check-label" for="short_by_high">
                                                High to Low  
                                              </label>
                                            </div>
                                        </li>
                                        <li>
                                           <div class="form-check">
                                              <input class="form-check-input" type="radio" value="best_selling" name="short_by" id="short_by_sell">
                                              <label class="form-check-label" for="short_by_sell">
                                               Top Selling
                                              </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="ctgFillterList priceFilter">
                                    <p>Price</p>
                                    <div class="slider-container">
                                      <div class="track"></div>
                                      <div class="range"></div>
                                      <div class="slider">
                                        <input type="range" min="0" max="0" name="min_price" value="0" class="minRange">
                                        <input type="range" min="0" max="{{$topPrice}}" name="max_price" value="{{$topPrice}}" class="maxRange">
                                      </div>
                                    </div>
                                    <div class="pRangeValue d-flex align-items-center justify-content-between" style="margin-top: 25px;">
                                     <span class="minValue fw-bold">৳ 0</span> 
                                     <span class="maxValue fw-bold">৳ {{$topPrice}}</span> 
                                    </div>
                                </div>
                                <div class="ctgFillterList">
                                    <p>Offers</p>
                                    <ul>
                                        <li>
                                           <div class="form-check">
                                              <input class="form-check-input" value="best_sale" type="radio" name="offer" id="offer1">
                                              <label class="form-check-label" for="offer1">
                                                Best Sale
                                              </label>
                                            </div>
                                        </li>
                                        <li>
                                           <div class="form-check">
                                              <input class="form-check-input" value="discount_sale" type="radio" name="offer" id="offer2">
                                              <label class="form-check-label" for="offer2">
                                               Discount
                                              </label>
                                            </div>
                                        </li>
                                     
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="product-grids categoryProductGrid">
                                <p class="categorytitle">Result for <span>{{$category->name}}</span> (<span class="totalCount">{{$products->total()}}</span>)</p>
                                <div class="row productRow products-load">
                                    @foreach($products as $product)
                                        <div class="col-md-4 col-6">
                                            @include(general()->theme.'.products.includes.productCard1')
                                        </div>
                                    @endforeach
                                     
                                </div>
                                <div class="">
                                        <span style="display: block;text-align: center;"><img class="loadMoreProduct" data-page="{{$products->lastPage()}}"  src="{{asset('medies/loading.gif')}}" ></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
  
            </div>
        </div>
    </div>
</div>

@endsection @push('js')
<script>
    
        $(window).on("load", function () {
            $(".skeleton").fadeOut(500, function () {
                // $(this).remove();
            });
        });
        
    $(function () {

        // ===============================
        // Helpers
        // ===============================
        function getFilters(page = 1) {
            return {
                short_by: $('input[name="short_by"]:checked').val() || 'all',
                min_price: $('.minRange').val() || 0,
                max_price: $('.maxRange').val() || 5000,
                offer: $('input[name="offer"]:checked').val() || '',
                page: page
            };
        }
    
        let page        = 1;
        let loading     = false;
        const $loader   = $('.loadMoreProduct');
        const baseUrl   = "{{ route('productCategory',$category->slug) }}";
    
        // ===============================
        // Apply Filter (full refresh)
        // ===============================
        function applyFilter() {
            page = 1;                       // reset page count
            loading = false;
    
            let filters = getFilters(page);
    
            $.ajax({
                url: baseUrl,
                type: 'GET',
                data: filters,
                beforeSend: function () {
                    $('.products-load').addClass('loading');
                },
                success: function (res) {
                    // Replace product grid
                    $('.products-load').html(res.html);
    
                    // Update total pages and loader state
                    $loader
                        .show()
                        .attr('data-page', res.page_total); // backend should return page_total
                    $('.totalCount').text(res.total);
    
                    // Rebind scroll for fresh list
                    $(window).off('scroll.loadMore').on('scroll.loadMore', scrollHandler);
    
                    lazyLoadImages('.lazy', 'skeleton');
                    $('.skeleton').fadeOut(500);
                },
                complete: function () {
                    $('.products-load').removeClass('loading');
                },
                error: function (err) {
                    console.error(err);
                }
            });
        }
    
        // ===============================
        // Price slider (works for both desktop & modal)
        // ===============================
        $('.priceFilter').each(function () {
            const $container = $(this);
            const $minRange = $container.find('.minRange');
            const $maxRange = $container.find('.maxRange');
            const $minValue = $container.find('.minValue');
            const $maxValue = $container.find('.maxValue');
    
            function updateRange() {
                let min = parseInt($minRange.val(), 10);
                let max = parseInt($maxRange.val(), 10);
    
                if (min > max) {
                    if ($(this).hasClass('minRange')) $minRange.val(max);
                    else $maxRange.val(min);
                    min = parseInt($minRange.val(), 10);
                    max = parseInt($maxRange.val(), 10);
                }
    
                $minValue.text('৳ ' + min);
                $maxValue.text('৳ ' + max);
    
                const maxAttr = parseInt($maxRange.attr('max'), 10);
                const percentMin = (min / maxAttr) * 100;
                const percentMax = (max / maxAttr) * 100;
                $container.find('.range')
                          .css({ left: percentMin + '%', width: (percentMax - percentMin) + '%' });
            }
    
            $minRange.on('input', updateRange);
            $maxRange.on('input', updateRange);
            updateRange();
        });
    
        // ===============================
        // Infinite Scroll
        // ===============================
        function isLoaderVisible() {
            const loaderTop = $loader.offset().top;
            const viewBottom = $(window).scrollTop() + $(window).height();
            return loaderTop < viewBottom;
        }
    
        function loadMore() {
            const totalPage = parseInt($loader.data('page'), 10);
    
            if (loading || page >= totalPage) return;
    
            loading = true;
            page++;
    
            $.ajax({
                url: baseUrl,
                type: 'GET',
                data: getFilters(page),
                success: function (res) {
                    $('.products-load').append(res.html);
    
                    // Update total pages if backend provides it
                    if (res.page_total) $loader.attr('data-page', res.page_total);
    
                    if (page >= parseInt($loader.data('page'), 10) || !res.hasMore) {
                        $loader.hide();
                        $(window).off('scroll.loadMore');
                    }
    
                    loading = false;
                    lazyLoadImages('.lazy', 'skeleton');
                    $('.skeleton').fadeOut(300);
                },
                error: function () {
                    loading = false;
                }
            });
        }
    
        function scrollHandler() {
            if (isLoaderVisible()) loadMore();
        }
    
        // attach on page load
        $(window).on('scroll.loadMore', scrollHandler);
        if (isLoaderVisible()) loadMore();
    
        // ===============================
        // Filter change events
        // ===============================
        $(document).on('change',
            'input[name="short_by"], input[name="offer"], .minRange, .maxRange',
            function () {
                applyFilter();
            }
        );
    
    });
    
        
</script>

@endpush