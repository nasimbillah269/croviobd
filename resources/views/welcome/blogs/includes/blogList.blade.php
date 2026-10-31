@include(general()->theme.'.blogs.includes.blogStyle')

<div class="blog-page">
	<section class="blog-hero">
		<div class="container">
			<ul class="blog-crumb">
				<li><a href="{{route('index')}}">Home</a></li>
				<li>Blog</li>
				@if(isset($crumb))<li>{{$crumb}}</li>@endif
			</ul>
			<h1>{{$title}}</h1>
			<p>{{isset($subtitle)?$subtitle:'Tips, news and guides from our team'}}</p>
		</div>
	</section>

	<div class="container">
		<div class="blog-toolbar">
			<div class="blog-chips">
				@foreach(App\Models\Attribute::where('type',6)->where('status','active')->where('parent_id',null)->orderBy('name')->limit(6)->get() as $ctg)
				<a href="{{route('blogCategory',$ctg->slug)}}" class="blog-chip {{isset($category) && $category->id==$ctg->id?'active':''}}">{{$ctg->name}}</a>
				@endforeach
			</div>
			<form class="blog-search" action="{{route('blogSearch')}}">
				<input type="search" name="search" placeholder="Search posts..." value="{{request('search')}}" required />
				<button type="submit"><i class="fa fa-search"></i></button>
			</form>
		</div>

		@if($posts->count())
		<div class="blog-grid">
			@foreach($posts as $post)
			@include(general()->theme.'.blogs.includes.blogGrid')
			@endforeach
		</div>

		<div class="blog-pagination">
			{{$posts->links(general()->theme.'.blogs.pagination')}}
		</div>
		@else
		<div class="blog-empty">
			<i class="fa fa-file-text-o"></i>
			No posts found.
		</div>
		@endif
	</div>
</div>
