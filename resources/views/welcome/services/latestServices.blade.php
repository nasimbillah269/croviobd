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
             <h2 class="title">{{$page->name}}</h2>
         </div>
     </div>
 </section>


 <section class="wthree-row py-5 about-main" id="about">
     <div class="container w3l-news py-lg-5 py-md-4 py-2">
        
     	    <div class="row mt-sm-5 mt-4">

     	        @foreach($services as $service)
                 <div class="col-lg-3 col-md-6">
                     @include(general()->theme.'.services.includes.serviceGrid')
                 </div>
                @endforeach

             </div>

            <!-- pagination -->
			 {{$services->links(general()->theme.'.services.pagination')}}
     </div>
 </section>


@endsection
@push('js') @endpush