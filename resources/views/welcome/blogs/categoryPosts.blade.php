@extends(general()->theme.'.layouts.app') @section('title')
<title>{{general()->title}} | {{general()->subtitle}}</title>
@endsection @section('SEO')
<meta name="description" content="{!!general()->meta_dsc!!}" />
<meta name="keywords" content="{{general()->meta_key}}" />
<meta property="og:title" content="{{general()->name}}" />
<meta property="og:description" content="{!!general()->meta_dsc!!}" />
<meta property="og:image" content="{!!general()->meta_dsc!!}" />
<meta property="og:url" content="{{route('index')}}" />
@endsection @push('css')
<style>
    .MyBannerAd img {
    max-width: 100%;
    }
</style>
@endpush 

@section('contents')

 <section class="w3l-about-breadcrumb">
     <div class="breadcrumb-bg breadcrumb-bg-about py-5">
         <div class="container py-lg-5 py-md-3">
             <h2 class="title">{{$category->name}} </h2>
         </div>
     </div>
 </section>
 <section class="w3l-blog-single">
	 <div class="single blog py-5">
	   <div class="container py-lg-5 py-md-4 py-2">
		 <div class="d-grid grid-colunm-2">
		   <!-- left side blog post content -->
		   <div class="single-left">

		   	 @foreach($posts as $post)
			 @include(general()->theme.'.blogs.includes.blogGrid')
			 @endforeach

			 <!-- pagination -->
			 {{$posts->links(general()->theme.'.blogs.pagination')}}

		   </div>
		   <!-- left side blog post content -->
  
		   <!-- right side bar -->
		   <div class="right-side-bar">
		   	@include(general()->theme.'.blogs.includes.sideBar')
		   </div>
		   <!-- //right side bar -->
		 </div>
	   </div>
	 </div>
   </section>


@endsection @push('js') @endpush