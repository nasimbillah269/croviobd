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

<div class="container">
    <div class="row" style="background: #e7e7e7;margin: 0;">
        <div class="col-md-12" style="background: white;border: 1px solid #c6c6c6;">
            <p style="margin: 0;cursor: pointer;padding: 5px;">
               <a style="text-decoration: none;color: gray;font-size: 14px;" href="javascript:void(0)">Comparew</a> 
            </p>
        </div>
    </div>
    
    <div style="border-top: 1px solid #c6c6c6;">
    
        <div class="viewItemsLists">
    
            @include(general()->theme.'.carts.includes.compareItems')
    
        </div>
    
    </div>
</div>

    
@endsection
@push('js')
@endpush