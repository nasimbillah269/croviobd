<article class="blog-card">
	<a href="{{route('blogView',$post->slug?:'no-title')}}" class="blog-card-img">
		<img src="{{asset($post->image())}}" alt="{{$post->name}}" loading="lazy" />
		<span class="blog-card-date"><b>{{$post->created_at->format('d')}}</b>{{$post->created_at->format('M')}}</span>
	</a>
	<div class="blog-card-body">
		<div class="blog-card-meta">
			@if($post->user)
			<a href="{{route('blogAuthor',[$post->user->id,Str::slug($post->user->name)])}}"><i class="fa fa-user"></i>{{$post->user->name}}</a>
			@else
			<span><i class="fa fa-user"></i>Admin</span>
			@endif
		</div>
		<h3 class="blog-card-title"><a href="{{route('blogView',$post->slug?:'no-title')}}">{{$post->name}}</a></h3>
		<p class="blog-card-text">{{Str::limit(strip_tags($post->short_description),140)}}</p>
		<a href="{{route('blogView',$post->slug?:'no-title')}}" class="blog-card-btn">Read More <i class="fa fa-arrow-right"></i></a>
	</div>
</article>
