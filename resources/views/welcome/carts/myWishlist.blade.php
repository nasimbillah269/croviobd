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

<div class="wishlist-page">
    <div class="container">
        <h3>My Wishlist</h3>
        
        <div class="viewItemsLists">
    
            @include(App\Models\General::first()->theme.'.carts.includes.wishlistItems')
    
        </div>
    </div>
</div>

@endsection @push('js') @endpush