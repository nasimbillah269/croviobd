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
    <!--<div class="col-md-3 hidemobiletopsidebar" style="padding: 0;">-->
    <!--    @include(App\Models\General::first()->theme.'.layouts/topsidebarmenu')-->
    <!--</div>-->
    <div class="col-md-12" style="background: white;border-right: 1px solid #c6c6c6;border-bottom: 1px solid #c6c6c6;">
        <p style="margin: 0;cursor: pointer;padding: 5px;">
           <a style="text-decoration: none;color: gray;font-size: 14px;" href="">
            @if(session()->get('locale')=='bn')
            সকল ক্যাটাগোরি
            @else
            All Category
            @endif
                
            </a> 
        </p>
    </div>
</div>

<div style="border-top: 1px solid #c6c6c6;">
    
    <!--Category List-->
    @foreach(App\Models\ProductCategory::where('active', 1)->select(['id','title'])->orderBy('title')->get() as $category)
    <div class="categoryName">
        <h3>
        <a href="{{ route('productCategory',['category',$category,Str::slug($category->title)]) }}"><span class="iconctg">{!! $category->icon !!}</span> 
        @if(session()->get('locale')=='bn')
        {{ $category->meta_title==null?$category->title:$category->meta_title}}
        @else
        {{ $category->title }}
        @endif
        </a>
        </h3>
        <ul>
            
            @foreach($category->subcats as  $subc)
            <li><a href="{{ route('productCategory',['subcategory',$subc,Str::slug($subc->title)]) }}"> 
            @if(session()->get('locale')=='bn')
            {{ $subc->meta_title==null?$subc->title:$subc->meta_title}}
            @else
            {{ $subc->title }}
            @endif
            </a></li>
            @foreach($subc->subsubcats as $ssc)
            <li><a href="{{ route('productCategory',['subcategory',$ssc,Str::slug($ssc->title)]) }}"> 
            @if(session()->get('locale')=='bn')
            {{ $ssc->meta_title==null?$ssc->title:$ssc->meta_title}}
            @else
            {{ $ssc->title }}
            @endif
            </a></li>
            @endforeach
            @endforeach
            
        </ul>
    </div>
    @endforeach
    <!--Category List-->
    <br>

</div>
    
@endsection
@push('js')
@endpush