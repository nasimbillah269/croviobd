<div class="grids5-info">
 <a href="{{route('serviceView',$service->slug)}}" class="d-block zoom"><img src="{{asset($service->image())}}" alt="" class="img-fluid news-image" /></a>
 <div class="blog-info">
     <h4><a href="{{route('serviceView',$service->slug)}}">{{Str::limit($service->name,25)}}</a></h4>
     <a href="{{route('serviceView',$service->slug)}}" class=" link-style p-0 mt-4">Read More
         <span class="fa fa-chevron-right"></span>
     </a>
 </div>
</div>