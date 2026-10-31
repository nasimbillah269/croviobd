@extends(general()->theme.'.layouts.app') @section('title')
<title>{{general()->title}} | {{general()->subtitle}}</title>
@endsection @section('SEO')
<meta name="description" content="{!!general()->meta_dsc!!}" />
<meta name="keywords" content="{{general()->meta_key}}" />
<meta property="og:title" content="{{general()->name}}" />
<meta property="og:description" content="{!!general()->meta_dsc!!}" />
<meta property="og:image" content="{!!general()->meta_dsc!!}" />
<meta property="og:url" content="{{route('index')}}" />
@endsection @push('css')
<style>
    .MyBannerAd img {
    max-width: 100%;
    }
</style>
@endpush 

@section('contents')

@include(general()->theme.'.blogs.includes.blogList',['title'=>$author->name,'crumb'=>'Author','subtitle'=>'Posts written by '.$author->name])

@endsection @push('js') @endpush
