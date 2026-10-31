@extends(general()->theme.'.layouts.app') @section('title')
<title>{{$post->name}} | {{general()->title}}</title>
@endsection @section('SEO')
<meta name="description" content="{{Str::limit(strip_tags($post->short_description),160)}}" />
<meta name="keywords" content="{{general()->meta_key}}" />
<meta property="og:title" content="{{$post->name}}" />
<meta property="og:description" content="{{Str::limit(strip_tags($post->short_description),160)}}" />
<meta property="og:image" content="{{asset($post->image())}}" />
<meta property="og:url" content="{{route('blogView',$post->slug)}}" />
@endsection @push('css')
@endpush

@section('contents')

@include(general()->theme.'.blogs.includes.blogStyle')

@php
	$shareUrl = urlencode(route('blogView',$post->slug));
	$shareTitle = urlencode($post->name);
	$readTime = max(1, (int) ceil(str_word_count(strip_tags($post->description)) / 200));
@endphp

<div class="blog-page">
	<section class="blog-hero">
		<div class="container">
			<ul class="blog-crumb">
				<li><a href="{{route('index')}}">Home</a></li>
				<li>Blog</li>
				<li>{{Str::limit($post->name,40)}}</li>
			</ul>
			@if($post->postCategories->count())
			<div class="blog-chips" style="margin-bottom:12px;">
				@foreach($post->postCategories as $ctg)
				<a href="{{route('blogCategory',$ctg->slug)}}" class="blog-chip">{{$ctg->name}}</a>
				@endforeach
			</div>
			@endif
			<h1>{{$post->name}}</h1>
			<div class="blog-detail-meta">
				@if($post->user)
				<a href="{{route('blogAuthor',[$post->user->id,Str::slug($post->user->name)])}}"><i class="fa fa-user"></i>{{$post->user->name}}</a>
				@else
				<span><i class="fa fa-user"></i>Admin</span>
				@endif
				<span><i class="fa fa-calendar"></i>{{$post->created_at->format('d M, Y')}}</span>
				<span><i class="fa fa-clock-o"></i>{{$readTime}} min read</span>
			</div>
		</div>
	</section>

	<div class="container">
		<div class="blog-detail-layout">
			<div>
				<article class="blog-box blog-article">
					<img src="{{asset($post->image())}}" alt="{{$post->name}}" class="blog-article-img" />
					<div class="blog-article-body">
						@if($post->short_description)
						<div class="blog-lead">{!!$post->short_description!!}</div>
						@endif

						<div class="blog-content">
							{!!$post->description!!}
						</div>

						<div class="blog-article-footer">
							<div class="blog-tags">
								@if($post->postTags->count())
								<strong>Tags:</strong>
								@foreach($post->Tags as $tag)
								<a href="{{route('blogTag',$tag->slug)}}">{{$tag->name}}</a>
								@endforeach
								@endif
							</div>
							<div class="blog-share">
								<strong>Share:</strong>
								<a class="fb" href="https://www.facebook.com/sharer/sharer.php?u={{$shareUrl}}" target="_blank" rel="noopener" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
								<a class="tw" href="https://twitter.com/intent/tweet?url={{$shareUrl}}&text={{$shareTitle}}" target="_blank" rel="noopener" title="X / Twitter"><i class="fa-brands fa-x-twitter"></i></a>
								<a class="wa" href="https://wa.me/?text={{$shareTitle}}%20{{$shareUrl}}" target="_blank" rel="noopener" title="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
								<a class="in" href="https://www.linkedin.com/sharing/share-offsite/?url={{$shareUrl}}" target="_blank" rel="noopener" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
							</div>
						</div>
					</div>
				</article>

				@if($prevPost || $nextPost)
				<nav class="blog-nav">
					@if($prevPost)
					<a href="{{route('blogView',$prevPost->slug?:'no-title')}}" class="prev">
						<small><i class="fa fa-arrow-left"></i> Previous Post</small>
						<span>{{$prevPost->name}}</span>
					</a>
					@endif
					@if($nextPost)
					<a href="{{route('blogView',$nextPost->slug?:'no-title')}}" class="next">
						<small>Next Post <i class="fa fa-arrow-right"></i></small>
						<span>{{$nextPost->name}}</span>
					</a>
					@endif
				</nav>
				@endif

				@if($relatedPosts->count())
				<div class="blog-related" style="margin-bottom:28px;">
					<h3 class="blog-section-title">You may also like</h3>
					<div class="blog-grid">
						@foreach($relatedPosts as $post_item)
						@include(general()->theme.'.blogs.includes.blogGrid',['post'=>$post_item])
						@endforeach
					</div>
				</div>
				@endif

			</div>

			<aside class="blog-sidebar">
				@include(general()->theme.'.blogs.includes.sideBar')
			</aside>
		</div>
	</div>
</div>

@endsection @push('js') @endpush
