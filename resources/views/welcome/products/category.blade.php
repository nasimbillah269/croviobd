@extends(general()->theme.'.layouts.app') @section('title')
@php
    $seoTitle = $category->seo_title ?: $category->name;
    $seoDesc = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode(
        $category->seo_description ?: ($category->short_description ?: ($category->description ?: 'Shop '.$category->name.' at '.general()->title.'. '.general()->meta_description))
    )))), 158, '…');
@endphp
<title>{{$seoTitle}} - {{general()->title}}</title>
@endsection @section('SEO')
        <meta name="description" content="{{$seoDesc}}" />
        <meta name="keywords" content="{{$category->seo_keyword ?: $category->name.' '.general()->meta_keyword}}" />
        <meta property="og:title" content="{{$seoTitle}}" />
        <meta property="og:description" content="{{$seoDesc}}" />
        <meta property="og:image" content="{{asset($category->image())}}" />
        <meta property="og:url" content="{{route('productCategory',$category->slug)}}" />
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('index')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $category->name, 'item' => route('productCategory', $category->slug)],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endsection @push('css')
<style>
    .productCategoryPage { padding-top: 24px; }
    .cf-panel {
        background: #fff; border: 1px solid #eceef1; border-radius: 12px;
        box-shadow: 0 1px 3px rgba(16,24,40,.06);
        position: sticky; top: 20px; margin-bottom: 24px;
        display: flex; flex-direction: column;
    }
    .cf-head { display: flex; align-items: center; justify-content: space-between; padding: 16px 18px; border-bottom: 1px solid #eceef1; }
    .cf-head h5 { margin: 0; font-size: 16px; font-weight: 700; color: #111827; display: flex; align-items: center; gap: 8px; }
    .cf-head h5 i { color: #ff005e; }
    .cf-clear { background: none; border: 0; padding: 0; font-size: 13px; font-weight: 600; color: #ff005e; cursor: pointer; }
    .cf-clear:hover { text-decoration: underline; }
    .cf-close { display: none; background: #f3f4f6; border: 0; width: 32px; height: 32px; border-radius: 50%; font-size: 22px; line-height: 1; color: #374151; cursor: pointer; }
    .cf-body { padding: 4px 18px; }
    .cf-section { padding: 16px 0 18px; border-bottom: 1px solid #f1f2f4; }
    .cf-section:last-child { border-bottom: 0; }
    .cf-title { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #6b7280; margin: 0 0 12px; }
    .cf-options { display: flex; flex-direction: column; gap: 4px; }
    .cf-option { position: relative; display: block; margin: 0; cursor: pointer; }
    .cf-option input { position: absolute; opacity: 0; pointer-events: none; }
    .cf-option span {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 10px; border-radius: 8px; font-size: 14px; color: #374151; transition: background .15s, color .15s;
    }
    .cf-option span:before {
        content: ''; width: 18px; height: 18px; flex-shrink: 0; border-radius: 50%;
        border: 2px solid #d1d5db; background: #fff; transition: all .15s;
    }
    .cf-check span:before { border-radius: 5px; }
    .cf-option:hover span { background: #f9fafb; }
    .cf-option input:checked + span { background: #fff0f5; color: #ff005e; font-weight: 600; }
    .cf-option input:checked + span:before { border-color: #ff005e; background: #ff005e; box-shadow: inset 0 0 0 3px #fff; }
    .cf-check input:checked + span:before {
        box-shadow: none;
        background: #ff005e url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23fff' stroke-width='2.5' d='M3.5 8.5l3 3 6-7'/%3E%3C/svg%3E") center/12px no-repeat;
    }
    .cf-option input:focus-visible + span { outline: 2px solid #ff005e; outline-offset: 1px; }

    .cf-panel .slider-container { height: 18px; margin: 6px 4px 0; }
    .cf-panel .track, .cf-panel .range { top: 7px; }
    .cf-panel .range { background: #ff005e; }
    .cf-panel .slider { top: 7px; }
    .cf-panel .slider input[type=range] { height: 0; margin: 0; }
    .cf-panel input[type=range]::-webkit-slider-thumb { background: #fff; border: 2px solid #ff005e; box-shadow: 0 1px 4px rgba(0,0,0,.2); height: 20px; width: 20px; margin-top: -10px; }
    .cf-panel input[type=range]::-moz-range-thumb { background: #fff; border: 2px solid #ff005e; box-shadow: 0 1px 4px rgba(0,0,0,.2); height: 16px; width: 16px; }
    .cf-price-values { display: flex; align-items: center; gap: 8px; margin-top: 22px; }
    .cf-price-box { flex: 1; border: 1px solid #e5e7eb; border-radius: 8px; padding: 6px 10px; }
    .cf-price-box small { display: block; font-size: 11px; color: #9ca3af; line-height: 1.2; }
    .cf-price-box span { font-size: 14px; font-weight: 600; color: #111827; }
    .cf-price-dash { color: #9ca3af; }

    .cf-foot { display: none; padding: 14px 18px; border-top: 1px solid #eceef1; }
    .cf-apply { width: 100%; background: #ff005e; color: #fff; border: 0; border-radius: 8px; padding: 12px; font-size: 15px; font-weight: 600; cursor: pointer; }

    .cf-results-bar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 10px; }
    .cf-results-bar .categorytitle { margin: 0; }
    .cf-open {
        display: none; align-items: center; gap: 8px;
        background: #fff; border: 1px solid #d1d5db; border-radius: 8px; padding: 8px 14px;
        font-size: 14px; font-weight: 600; color: #111827; cursor: pointer; white-space: nowrap;
    }
    .cf-open i { color: #ff005e; }
    .cf-badge { background: #ff005e; color: #fff; border-radius: 999px; font-size: 11px; min-width: 18px; height: 18px; padding: 0 5px; display: inline-flex; align-items: center; justify-content: center; }
    .cf-overlay { display: none; }
    .products-load.loading { opacity: .5; pointer-events: none; transition: opacity .2s; }

    @media (max-width: 991.98px) {
        .cf-open { display: inline-flex; }
        .cf-close, .cf-foot { display: block; }
        .cf-panel {
            position: fixed; top: 0; left: 0; bottom: 0; z-index: 1060;
            width: 86%; max-width: 340px; margin: 0; border-radius: 0 14px 14px 0; border: 0;
            transform: translateX(-105%); transition: transform .25s ease;
        }
        .cf-body { flex: 1; overflow-y: auto; }
        .cf-overlay { display: block; position: fixed; inset: 0; z-index: 1055; background: rgba(17,24,39,.45); opacity: 0; visibility: hidden; transition: opacity .25s; }
        body.cf-open-state { overflow: hidden; }
        body.cf-open-state .cf-panel { transform: none; }
        body.cf-open-state .cf-overlay { opacity: 1; visibility: visible; }
        .cf-results-bar { margin-top: 4px; }
    }
</style>
@endpush 

@section('contents')

<div class="categoryPage">
    <div class="container">
        <div class="products-section">
            <div class="featured-products">
                
                <div class="productCategoryPage">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="cf-overlay" data-cf-close></div>
                            <aside class="cf-panel" id="cfPanel" aria-label="Product filters">
                                <div class="cf-head">
                                    <h5><i class="fa fa-sliders"></i> Filters</h5>
                                    <div class="d-flex align-items-center" style="gap:12px;">
                                        <button type="button" class="cf-clear" id="cfClear">Clear all</button>
                                        <button type="button" class="cf-close" data-cf-close aria-label="Close filters">&times;</button>
                                    </div>
                                </div>

                                <div class="cf-body">
                                    <div class="cf-section">
                                        <p class="cf-title">Sort by</p>
                                        <div class="cf-options">
                                            <label class="cf-option"><input type="radio" value="all" name="short_by" checked><span>Newest</span></label>
                                            <label class="cf-option"><input type="radio" value="low_to_high" name="short_by"><span>Price: Low to High</span></label>
                                            <label class="cf-option"><input type="radio" value="high_to_low" name="short_by"><span>Price: High to Low</span></label>
                                            <label class="cf-option"><input type="radio" value="best_selling" name="short_by"><span>Top Selling</span></label>
                                        </div>
                                    </div>

                                    <div class="cf-section priceFilter">
                                        <p class="cf-title">Price range</p>
                                        <div class="slider-container">
                                            <div class="track"></div>
                                            <div class="range"></div>
                                            <div class="slider">
                                                <input type="range" min="0" max="{{ (int) ceil($topPrice) }}" step="1" name="min_price" value="0" class="minRange" aria-label="Minimum price">
                                                <input type="range" min="0" max="{{ (int) ceil($topPrice) }}" step="1" name="max_price" value="{{ (int) ceil($topPrice) }}" class="maxRange" aria-label="Maximum price">
                                            </div>
                                        </div>
                                        <div class="cf-price-values">
                                            <div class="cf-price-box"><small>Min</small><span class="minValue">{{general()->currency}} 0</span></div>
                                            <span class="cf-price-dash">&ndash;</span>
                                            <div class="cf-price-box"><small>Max</small><span class="maxValue">{{general()->currency}} {{ (int) ceil($topPrice) }}</span></div>
                                        </div>
                                    </div>

                                    <div class="cf-section">
                                        <p class="cf-title">Offers</p>
                                        <div class="cf-options">
                                            <label class="cf-option cf-check"><input type="radio" value="best_sale" name="offer"><span>Best Sale</span></label>
                                            <label class="cf-option cf-check"><input type="radio" value="discount" name="offer"><span>On Discount</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="cf-foot">
                                    <button type="button" class="cf-apply" data-cf-close>Show <span class="totalCount">{{$products->total()}}</span> products</button>
                                </div>
                            </aside>
                        </div>
                        <div class="col-lg-9">
                            <div class="product-grids categoryProductGrid">
                                <div class="cf-results-bar">
                                    <h1 class="categorytitle">{{$category->name}} <span style="color:#6b7280;">(<span class="totalCount">{{$products->total()}}</span>)</span></h1>
                                    <button type="button" class="cf-open" id="cfOpen">
                                        <i class="fa fa-sliders"></i> Filters <span class="cf-badge" id="cfBadge" style="display:none;"></span>
                                    </button>
                                </div>
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
        const currency  = @json(general()->currency);
    
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
                    updateBadge();
    
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
    
                $minValue.text(currency + ' ' + min);
                $maxValue.text(currency + ' ' + max);
    
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
    
        // ===============================
        // Filter panel: drawer, clear, active badge
        // ===============================
        const topPrice = parseInt($('.maxRange').attr('max'), 10) || 0;

        function updateBadge() {
            let count = 0;
            if ($('input[name="short_by"]:checked').val() !== 'all') count++;
            if ($('input[name="offer"]:checked').length) count++;
            if (parseInt($('.minRange').val(), 10) > 0 || parseInt($('.maxRange').val(), 10) < topPrice) count++;
            $('#cfBadge').text(count).toggle(count > 0);
        }

        $('#cfOpen').on('click', function () { $('body').addClass('cf-open-state'); });
        $(document).on('click', '[data-cf-close]', function () { $('body').removeClass('cf-open-state'); });
        $(document).on('keydown', function (e) { if (e.key === 'Escape') $('body').removeClass('cf-open-state'); });

        // offers behave like a toggle: clicking the selected one clears it
        $('input[name="offer"]').on('click', function () {
            if ($(this).data('wasChecked')) {
                this.checked = false;
                $(this).data('wasChecked', false).trigger('change');
            } else {
                $('input[name="offer"]').data('wasChecked', false);
                $(this).data('wasChecked', true);
            }
        });

        $('#cfClear').on('click', function () {
            $('input[name="short_by"][value="all"]').prop('checked', true);
            $('input[name="offer"]').prop('checked', false).data('wasChecked', false);
            $('.minRange').val(0);
            $('.maxRange').val(topPrice).trigger('input');
            applyFilter();
        });

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