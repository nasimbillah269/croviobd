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

<div class="shelfPage">
    <div class="container">
        <h3>COUNTRYWISE SHELF</h3>
        <div class="row">
            @foreach($categories as $category)
            <div class="col-md-2" style="padding: 15px;">
                <a href="{{route('productCategory',$category->slug)}}" class="shelf-blog">
                    <h5>{{$category->name}}</h5>
                    <hr style="width: 50%; border: 1px solid #ccc; margin: 5px auto 10px;">
                    <img src="{{asset($category->image())}}" width="100%" alt="{{$category->name}}">
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection @push('js') @endpush