@extends(general()->theme.'.layouts.app')
@section('title')
<title>{{general()->title}} | {{general()->subtitle}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!general()->meta_dsc!!}">
<meta name="keywords" content="{{general()->meta_key}}">
<meta property="og:title" content="{{general()->name}}">
<meta property="og:description" content="{!!general()->meta_dsc!!}">
<meta property="og:image" content="{{asset(general()->logo())}}">
<meta property="og:url" content="{{route('index')}}">
@endsection
@push('css')


@endpush
@section('contents')

<div class="row" style="background: #e7e7e7;margin: 0;">
    <div class="col-md-12" style="background: white;border-right: 1px solid #c6c6c6;">
        <p style="margin: 0;cursor: pointer;padding: 5px;">
            <a style="text-decoration: none;color: gray;font-size: 14px;" href="{{ route('productBrandView',[$brand,Str::slug($brand->title)]) }}">{{$brand->title}}</a> 
        </p>
    </div>
</div>

<div style="border-top: 1px solid #c6c6c6;padding-top: 10px;">

    <div class="postsAuto">
        <div class="dataLastPage" data-lastpage="{{$products->lastPage()}}" data-nowpage="1" data-url="{{$products->path()}}">
    
        	@include(general()->theme.'.products.includes.productsAll6')
    
        </div>
    
     
             
    <!--<div class="text-center mt-2" style="display:block;">-->
    <!--    <div class="loader"><i class="fa fa-spin fa-spinner"></i></div>-->
    <!--</div>-->

    </div>

</div>

@endsection
@push('js')
@endpush