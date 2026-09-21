<div class="single-left1">
	 <div class="blg-img">
   <a href="{{route('blogView',$post->slug?:'no-title')}}"><img src="{{asset($post->image())}}" alt=" " class="img-responsive img-fluid" />
   <div class="bl-top">
	 <h4>{{$post->created_at->format('d M')}} </h4>
  </div></a>
 </div>
 
   <div class="btom-cont">
	 <h5 class="card-title"><a href="{{route('blogView',$post->slug?:'no-title')}}">{{$post->name}}</a></h5>
	 <ul class="admin-post">
	   <li>
	   	@if($post->user)
	     <a href="{{route('blogAuthor',[$post->user->id,Str::slug($post->user->name)])}}">
	    @else
	     <a href="javascript:void(0)">
	    @endif
		 	<span class="fa fa-user"></span> Posted by {{$post->user?$post->user->name:'No Author'}} 
		 </a>
	   </li>
	   <li>
		 <a href="{{route('blogView',$post->slug?:'no-title')}}"><span class="fa fa-comments-o"></span>Comments ({{$post->postComments->count()}}) </a>
	   </li>
	 </ul>
	 <p class="">{{$post->short_description}}</p>
	 <a href="{{route('blogView',$post->slug?:'no-title')}}" class="btn btn-style btn-primary mt-4">Read More </a>
	
   </div>
 </div>