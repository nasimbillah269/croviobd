<div class="blog-box">
	<h3>Search</h3>
	@if ($errors->has('search'))
	<p style="color: red; margin: 0 0 8px; font-size: 12px;">{{ $errors->first('search') }}</p>
	@endif
	<form class="blog-search" action="{{route('blogSearch')}}">
		<input type="search" placeholder="Search posts..." name="search" required />
		<button type="submit"><i class="fa fa-search"></i></button>
	</form>
</div>

<div class="blog-box">
	<h3>Categories</h3>
	<ul class="blog-cat-list">
		@foreach(App\Models\Attribute::where('type',6)->where('status','active')->where('parent_id',null)->orderBy('name')->limit(8)->get() as $ctg)
		<li><a href="{{route('blogCategory',$ctg->slug)}}"><i class="fa fa-angle-right"></i>{{$ctg->name}}</a></li>
		@endforeach
	</ul>
</div>

<div class="blog-box">
	<h3>Recent Posts</h3>
	@foreach(App\Models\Post::where('type',1)->where('status','active')->latest()->limit(5)->get() as $lpost)
	<div class="blog-recent">
		<a href="{{route('blogView',$lpost->slug?:'no-title')}}"><img src="{{asset($lpost->image())}}" alt="{{$lpost->name}}" loading="lazy" /></a>
		<div>
			<h4><a href="{{route('blogView',$lpost->slug?:'no-title')}}">{{$lpost->name}}</a></h4>
			<small><i class="fa fa-clock-o"></i> {{$lpost->created_at->diffForHumans()}}</small>
		</div>
	</div>
	@endforeach
</div>

@php($sideTags = App\Models\Attribute::where('type',7)->where('status','active')->where('parent_id',null)->orderBy('name')->limit(12)->get())
@if($sideTags->count())
<div class="blog-box">
	<h3>Popular Tags</h3>
	<div class="blog-tags">
		@foreach($sideTags as $tag)
		<a href="{{route('blogTag',$tag->slug)}}">{{$tag->name}}</a>
		@endforeach
	</div>
</div>
@endif
