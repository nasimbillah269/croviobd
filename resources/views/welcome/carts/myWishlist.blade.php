@extends(general()->theme.'.layouts.app') @section('title')
<title>My Wishlist | {{general()->title}}</title>
@endsection @section('SEO')
<meta name="description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta property="og:title" content="{{general()->meta_title}}" />
<meta property="og:description" content="{!!general()->meta_description!!}" />
<meta property="og:image" content="{{asset(general()->logo())}}" />
<meta property="og:url" content="{{route('index')}}" />
@endsection @push('css')
@endpush 

@section('contents')

<div class="wl-page">
    <div class="container">
        <div class="viewItemsLists">
            @include(App\Models\General::first()->theme.'.carts.includes.wishlistItems')
        </div>
    </div>
</div>

@endsection @push('js')
<script>
    $(function () {
        $(window).on('load', function () { $('.skeleton').fadeOut(400); });
        // the list is re-rendered after a heart is toggled; load its lazy images again
        $(document).ajaxComplete(function (e, xhr, settings) {
            if (settings.url && settings.url.indexOf('wishlist-compare/update') !== -1) {
                lazyLoadImages('.lazy', 'skeleton');
                $('.skeleton').fadeOut(300);
            }
        });
    });
</script>
@endpush
