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
             <h2 class="title">{{$post->name}}</h2>
         </div>
     </div>
 </section>

 <section class="w3l-blog-single">
	 <div class="single blog py-5">
	   <div class="container py-lg-5 py-md-4 py-2">
		 <div class="d-grid grid-colunm-2">
		   <!-- left side blog post content -->
		   <div class="single-left">

		   	 
		   	 <div class="single-left1">
                         <div class="blg-img">
                             <img src="{{asset($post->image())}}" alt="{{$post->name}}" class="img-fluid img-fluid" />
                             <div class="bl-top">
                                 <h4>{{$post->created_at->format('d M')}}</h4>
                             </div>
                         </div>

                         <div class="btom-cont1 pt-4 mt-md-2">
                             <ul class="admin-post">
                                 <li>
                                    @if($post->user)
                                     <a href="{{route('blogAuthor',[$post->user->id,Str::slug($post->user->name)])}}">
                                    @else
                                     <a href="javascript:void(0)">
                                    @endif
                                        <span class="fa fa-user"></span> Posted by {{$post->user?$post->user->name:'No Author'}} </a>
                                 </li>
                                 <li>
                                     <a href="javascript:void(0)"><span class="fa fa-comments-o"></span>Comments ({{$post->postComments->count()}}) </a>
                                 </li>
                             </ul>
                             <div class="short-description">
                             	{!!$post->short_description!!}
                             </div>
                             <div class="content-description">
                             	{!!$post->description!!}
                             </div>
                             

                             <ul class="share-post my-md-5 my-4">
                                 <li>
                                     <h4 class="side-title mr-sm-4 mr-2">Share this post : </h4>
                                 </li>
                                 <li>
                                     <a href="#link" class="facebook" title="Facebook">
                                         <span class="fa fa-facebook" aria-hidden="true"></span>
                                     </a>
                                 </li>
                                 <li>
                                     <a href="#link" class="twitter" title="Twitter">
                                         <span class="fa fa-twitter" aria-hidden="true"></span>
                                     </a>
                                 </li>
                                 <li>
                                     <a href="#link" class="instagram" title="Instagram">
                                         <span class="fa fa-instagram" aria-hidden="true"></span>
                                     </a>
                                 </li>
                             </ul>
                         </div>
                     </div>
                     <nav class="post-navigation row">
                         <div class="post-prev col-6">
                             <a href="#url" rel="prev">
                                 <h5><span class="fa fa-arrow-left"></span> Prev Post  </h5>
                             </a>
                         </div>
                         <div class="post-next col-6 text-right">

                             <a href="#url" rel="next">
                                 <h5> Next Post  <span class="fa fa-arrow-right"></span></h5>
                             </a>
                         </div>
                     </nav>
                     <div class="comments">
                         <h3 class="post-content-title">Comments </h3>
                         <div class="media mt-5 bod-1">
                             <div class="img-circle">
                                 <img src="assets/images/team1.jpg" class="img-fluid" alt="..." />
                             </div>
                             <div class="media-body">
                                 <div class="medi-top mb-2">
                                     <a href="#URL" class="name mt-0">Johnson smith </a>
                                     <span>14 Sep, 2020  </span>
                                 </div>
                                 <p>Cras sit amet nibh ______, in gravida nulla. Nulla ___ metus scelerisque ante
                                    sollicitudin. ____ purus tempus viverra turpis. _____ nunc ac in vulputate
                                    ____, in vulputate at, viverra ______, nunc ac. </p>
                                 <a href="#reply" class="rep mt-3">Reply </a>
                                 <div class="media mt-4 bod-2">
                                     <a class="img-circle img-circle-sm" href="#">
                                         <img src="assets/images/team2.jpg" class="img-fluid" alt="..." />
                                     </a>
                                     <div class="media-body">
                                         <div class="medi-top mb-2">
                                             <a href="#URL" class="name mt-0">Alexander </a>
                                             <span>14 Sep, 2020  </span>
                                         </div>
                                         <p>Cras sit amet nibh ______, in gravida nulla. Nulla ___ metus scelerisque ante
                                            sollicitudin. Cras purus ____, vestibulum at. </p>
                                         <a href="#reply" class="rep mt-3">Reply </a>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <div class="media bod-3">
                             <div class="img-circle">
                                 <img src="assets/images/team3.jpg" class="img-fluid" alt="..." />
                             </div>
                             <div class="media-body">
                                 <div class="medi-top mb-2">
                                     <a href="#URL" class="name mt-0">Elizabeth </a>
                                     <span>14 Sep, 2020  </span>
                                 </div>
                                 <p>Cras sit amet nibh ______, in gravida nulla. Nulla ___ metus scelerisque ante
                                    sollicitudin. ____ purus
                                    odio, in vulputate __, viverra turpis, nunc ac. </p>
                                 <a href="#reply" class="rep mt-3">Reply </a>
                             </div>
                         </div>


                     </div>

                     <div class="testi-top mt-5 pt-4">

                         <h3 class="post-content-title">Leave a message </h3>
                         <div class="form-commets mt-4">
                             <form action="#" method="post">
                                 <div class="media-form">
                                     <input type="text" name="Name" required="Name" placeholder="Your Name" />
                                     <input type="email" name="Email" required="Email" placeholder="Your Email" />
                                 </div>
                                 <textarea name="Message" required="" placeholder="Write your comments here"></textarea>
                                 <div class="text-right">
                                     <button class="btn btn-primary btn-style" type="submit">Post comment </button>
                                 </div>

                             </form>
                         </div>
                     </div>



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