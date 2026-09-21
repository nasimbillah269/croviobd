 <aside>
   <h3 class="aside-title mb-3">Search </h3>
   @if ($errors->has('search'))
    <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('search') }}</p>
    @endif
   <form class="form-inline search-form" action="{{route('blogSearch')}}">
	 <input class="form-control" type="search" placeholder="Search here" name="search"  required="" />
	 <button class="btn search" type="submit"><span class="fa fa-search"></span></button>
   </form>
 </aside>
 <aside class="posts">
   <h3 class="aside-title">All Categories </h3>
   <ul class="category">
   	@foreach(App\Models\Attribute::where('type',6)->where('status','active')->where('parent_id',null)->orderBy('name')->limit(5)->get() as $ctg)
	 <li><a href="{{route('blogCategory',$ctg->slug)}}"><span class="fa fa-angle-right"></span>{{$ctg->name}}  <label> (11) </label></a></li>
	 @endforeach
   </ul>
 </aside>
 <aside class="posts">
   <h3 class="aside-title">Recent Posts </h3>
   <div class="posts-grids">
   	@foreach(App\Models\Post::where('type',1)->where('status','active')->latest()->limit(5)->get() as $lpost)
	 <div class="posts-grid-inner">
	   <div class="posts-grid-left pr-0">
		 <a href="{{route('blogView',$lpost->slug)}}">
		   <img src="{{asset($lpost->image())}}" alt="{{$lpost->name}}" class="img-responsive " />
		 </a>
	   </div>
	   <div class="posts-grid-right">
		 <h4>
		   <a href="{{route('blogView',$lpost->slug)}}" class="text-bl">{{Str::limit($lpost->name,20)}}</a>
		 </h4>
		 <span class="price"> {{$lpost->created_at->diffForHumans()}} </span>
	   </div>
	 </div>
	 @endforeach

   </div>
 </aside>

 <aside class="posts">
   <h3 class="aside-title">Popular Tags </h3>
   <ul class="tags-list">
   	@foreach(App\Models\Attribute::where('type',7)->where('status','active')->where('parent_id',null)->orderBy('name')->limit(5)->get() as $tag)
	 <li><a href="{{route('blogTag',$tag->slug)}}"> {{$tag->name}} </a></li>
	 @endforeach
   </ul>
 </aside>
 <aside class="posts">
	 <h3 class="aside-title">Advertisement </h3>
	 <a href="javascript:void(0)">
		 <img src="{{asset(assetLink().'/assets/images/testimonials.jpg')}}" alt=" " class="img-fluid radius-image" />
	   </a>
 </aside>