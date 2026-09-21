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


<div class="row" style="background: #e7e7e7;margin: 0;">
    <div class="col-md-12" style="background: white;border-right: 1px solid #c6c6c6;border-bottom: 1px solid #c6c6c6;">
        <p style="margin: 0;cursor: pointer;padding: 5px;">
           <a style="text-decoration: none;color: gray;font-size: 14px;" href="javascript:void(0)">All Brands</a> 
        </p>
    </div>
</div>

<div style="border-top: 2px solid #c6c6c6;">
    <div class="row" style="margin:0;">
        <div class="col-md-12" style="margin-top: 10px;padding:0;">
            <div class="productsdivctg">
                <div class="postsAuto">
                <div class="dataLastPage" data-lastpage="{{$brands->lastPage()}}" data-nowpage="1"></div>
                    @include(App\Models\General::first()->theme.'.products.includes.brandsAll')
                </div>

                <div class="text-center mt-2" style="display:none;">
                    <div class="loader"><i class="fa fa-spin fa-spinner"></i></div>
                </div>
            </div>
            
        </div>
    </div>
</div>
    
@endsection
@push('js')
@endpush