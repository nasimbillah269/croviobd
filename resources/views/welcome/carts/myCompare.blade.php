@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>{{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta name="keywords" content="{{App\Models\General::latest()->first()->meta_key}}">
<meta property="og:title" content="{{App\Models\General::latest()->first()->name}}">
<meta property="og:description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta property="og:image" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
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
    
            @include(App\Models\General::first()->theme.'.carts.includes.compareItems')
    
        </div>
    
    </div>
</div>

    
@endsection
@push('js')
@endpush