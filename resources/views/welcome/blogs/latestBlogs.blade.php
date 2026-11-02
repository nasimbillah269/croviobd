@extends(general()->theme.'.layouts.app') @section('title')
@php
    // posts table stores the page meta description in seo_desc
    $seoTitle = $page->seo_title ?: $page->name;
    $seoDesc = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode(
        $page->seo_desc ?: ($page->short_description ?: ($page->description ?: general()->meta_description))
    )))), 158, '…');
@endphp
<title>{{$seoTitle}} - {{general()->title}}</title>
@endsection @section('SEO')
<meta name="description" content="{{$seoDesc}}" />
<meta name="keywords" content="{{$page->seo_keyword?:general()->meta_keyword}}" />
<meta property="og:title" content="{{$seoTitle}}" />
<meta property="og:description" content="{{$seoDesc}}" />
<meta property="og:image" content="{{asset($page->imageFile ? $page->image() : general()->logo())}}" />
<meta property="og:url" content="{{route('pageView',$page->slug?:'no-title')}}" />
@endsection @push('css')
<style>
    .MyBannerAd img {
    max-width: 100%;
    }
</style>
@endpush 

@section('contents')

@include(general()->theme.'.blogs.includes.blogList',['title'=>'Blog Posts'])

@endsection @push('js') @endpush
