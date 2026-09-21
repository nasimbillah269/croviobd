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

<div class="breadcrumbtitle">
	<ul class="breadcrumb">
		<li><a href="{{route('index')}}">Home</a>  </li>
		@if($type=='subsubcategory')
		    @if($cat->category)
    		<li><a href="{{route('productCategory',['category',$cat->category->id,Str::slug($cat->category->title)])}}">{{$cat->category->title}}</a>  </li>
    		@endif
    		
    		@if($subcct =App\Models\ProductSubcategory::find($cat->subcategory_id))
    		<li><a href="{{route('productCategory',['subcategory',$subcct->id,Str::slug($subcct->title)])}}">{{$subcct->title}}</a>  </li>
    		@endif
    	
    		<li>{{$cat->title}} </li>
		@endif
		
		@if($type=='subcategory')
    		@if($cat->category)
    		<li><a href="{{route('productCategory',['category',$cat->category->id,Str::slug($cat->category->title)])}}">{{$cat->category->title}}</a>  </li>
    		@endif
    		<li>{{$cat->title}}</li>
		@endif
		
		@if($type=='category')
		<li>{{$cat->title}}</li>
		@endif
	</ul>
</div>

<div>
    <div class="row" style="margin:0;">
        <div class="col-md-4 col-xl-3 hidemobiletopsidebar mobile-hide" style="padding:0;">
        	<div class="leftsidebar">
				<div class="filter-price">
					<h2>Categories</h2>
					<ul style="padding-top: 10px;">
					    @foreach(App\Models\ProductCategory::where('active','<>',2)->select(['id','title'])->get() as $ctgN)
						<li><a href="{{route('productCategory',['category',$ctgN->id,Str::slug($ctgN->title)])}}">{{$ctgN->title}}</a>
						@if(App\Models\ProductSubcategory::where('active','<>',2)->where('category_id',$ctgN->id)->select(['id','title'])->count() > 0)
							<ul>
                            @foreach(App\Models\ProductSubcategory::where('active','<>',2)->where('category_id',$ctgN->id)->select(['id','title'])->get() as $sCat)
							<li><a href="{{route('productCategory',['subcategory',$sCat->id,Str::slug($sCat->title)])}}">{{$sCat->title}}</a>
								@if(App\Models\ProductSubsubcategory::where('active','<>',2)->where('subcategory_id',$sCat->id)->select(['id','title'])->count() > 0)
								<ul>
									@foreach(App\Models\ProductSubsubcategory::where('active','<>',2)->where('subcategory_id',$sCat->id)->select(['id','title'])->get() as $ssCat)
    					    		<li><a href="{{route('productCategory',['subsubcategory',$ssCat->id,Str::slug($ssCat->title)])}}">{{$ssCat->title}}</a></li>
    					    		@endforeach
								</ul>
								@endif
							</li>
							@endforeach
						</ul>
						@endif
						</li>
						@endforeach

					</ul>
				</div>
			</div>
        	
        	<!--@if($subCat->count() > 0)-->
         <!--   <div class="CategoryList">-->
         <!--       <h3>-->
         <!--        @if(session()->get('locale')=='bn')-->
         <!--           ফিল্টার করুন-->
         <!--       @else-->
         <!--           Filter By-->
         <!--       @endif-->
         <!--       </h3>-->
         <!--   </div>-->
         <!--   @endif-->
            
        </div>
        

        <div class="col-md-8 col-xl-9 categorydiv">
            <div class="productsdivctg">
            <div class="postsAuto">
                <div class="dataLastPage" data-lastpage="{{$products->lastPage()}}" data-nowpage="1" data-url="{{$products->path()}}"></div>
            
                @include(App\Models\General::first()->theme.'.products.includes.productsAll')
            	
                </div>
        </div> 


        </div>
    </div>

</div>

<!--filter-->

</div>

    
@endsection
@push('js')

<script>
    $(document).ready(function(){
        
         $('.filterboxdiv').click(function(){
            $('#filterboxwidget').animate({"margin-right": '+=300px'});
            });
            $('#filtertboxClose').click(function(){
             $('#filterboxwidget').animate({"margin-right": '-=300px'});
            });
                
    });
</script>

@endpush