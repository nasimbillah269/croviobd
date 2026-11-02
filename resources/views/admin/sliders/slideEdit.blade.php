@extends('admin.layouts.app')
@section('title')
<title>Slide Edit - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<style type="text/css">

</style>
@endpush
@section('contents')


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
     <h3 class="content-header-title mb-0">Slide Edit</h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">Slide Edit</li>
         </ol>
       </div>
     </div>
   </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
       	<a class="btn btn-outline-primary" href="{{route('admin.slidersEdit',$slide->parent_id)}}">BACK</a>
       	<a class="btn btn-outline-primary reloadPage" href="javascript:void(0)">
       		<i class="fa-solid fa-rotate"></i>
       	</a>
     </div>
   </div>
</div>
 
	

 <div class="content-body">
 	<!-- Basic Elements start -->
	 <section class="basic-elements">
	 	@include('admin.alerts')
	    <form action="{{route('admin.slideUpdate',$slide->id)}}" method="post" enctype="multipart/form-data">
	    	@csrf
	     <div class="row">

	     		<div class="col-md-8">
			     	<div class="card">
		             	<div class="card-header " style="border-bottom: 1px solid #e3ebf3;">
						 	<h4 class="card-title">Slide Edit</h4>
					 	</div>
		                <div class="card-content">
		                    <div class="card-body">
			                    <div class="form-group">
			                     	<label for="name">Slide Name(*) </label>
			                     	<input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Slider Name" value="{{$slide->name?:old('name')}}" required="" />
			                    	@if ($errors->has('name'))
			                    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('name') }}</p>
			                    	@endif
					             		</div>
					             		<div class="form-group">
														<label for="description">Description </label>
														<textarea name="description" class="form-control {{$errors->has('description')?'error':''}}" placeholder="Enter Description">{!!$slide->description!!}</textarea>
														@if ($errors->has('description'))
														<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('description') }}</p>
														@endif
			             				</div>
					             		<div class="form-group">
					             			<label for="name">User Layer</label>
					             			<select name="uselayer" class="form-control {{$errors->has('description')?'error':''}}">
					             				<option value="1" {{$slide->seo_title=='1'?'selected':''}} >Image layer</option>
					             				<option value="2" {{$slide->seo_title=='2'?'selected':''}} >Solid Color layer</option>
					             			</select>
					             		</div>
					             		<div class="form-group">
					             			<label for="link">Slide Link</label>
					             			<input type="text" id="link" name="link" class="form-control {{$errors->has('link')?'error':''}}" placeholder="Exp: https://croviobd.com/product/... or /product-search" value="{{old('link', $slide->seo_description)}}" />
					             			@if ($errors->has('link'))
					             			<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('link') }}</p>
					             			@endif
					             			<small class="text-muted">Leave empty if the slide should not be clickable.</small>
					             		</div>
					             		<div class="form-group">
					             			<div class="custom-control custom-checkbox">
					             				<input type="checkbox" class="custom-control-input" id="target" name="target" {{$slide->target?'checked':''}} />
					             				<label class="custom-control-label" for="target">Open link in new tab</label>
					             			</div>
					             		</div>

		                    </div>
		                 </div>
			     	</div>
				</div>
				<div class="col-md-4">
					<div class="card">
		             	<div class="card-header " style="border-bottom: 1px solid #e3ebf3;">
						 	<h4 class="card-title">Slide Layer</h4>
					 	</div>
		                <div class="card-content">
		                    <div class="card-body">
		                    	<div class="form-group">
	            					<label for="image">Slide Image</label>
	            					<input type="file" name="image" class="form-control {{$errors->has('image')?'error':''}}" >
	            					@if ($errors->has('image'))
	                              	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('image') }}</p>
	                               	@endif
	                			</div>
	                    		<div class="form-group">
	                				<img src="{{asset($slide->image())}}" style="max-width: 100px;">
	                				@if($slide->imageFile)
	                				<a href="{{route('admin.mediesDelete',$slide->imageFile->id)}}" class="mediaDelete" style="color:red;"><i class="fa fa-trash"></i></a>
	                				@endif
	                			</div>
	                			<div class="form-group">
	            					<label for="banner">Mobile Image</label>
	            					<input type="file" name="banner" id="banner" accept="image/*" class="form-control {{$errors->has('banner')?'error':''}}" >
	            					@if ($errors->has('banner'))
	                              	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('banner') }}</p>
	                               	@endif
	                               	<small class="text-muted">Shown on phones (below 768px). Leave empty to use the main image.</small>
	                			</div>
	                			@if($slide->bannerFile)
	                    		<div class="form-group">
	                				<img src="{{asset($slide->banner())}}" style="max-width: 100px;">
	                				<a href="{{route('admin.mediesDelete',$slide->bannerFile->id)}}" class="mediaDelete" style="color:red;"><i class="fa fa-trash"></i></a>
	                			</div>
	                			@endif
	            				<div class="row">
		                     		<div class="form-group col-6">
		                    			<label for="status">Slider Status</label>
						               	<div class="custom-control custom-checkbox">
							                 <input type="checkbox" class="custom-control-input" id="status" name="status"  {{$slide->status=='active'?'checked':''}}/>
							                 <label class="custom-control-label" for="status">Active</label>
						               </div>
			                        </div> 
			                    </div>
			                    @isset(json_decode(Auth::user()->permission->permission, true)['sliders']['add'])
	                          	<button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save
		                                  changes </button>
		                      @endisset

		                    </div>
		                </div>
		            </div>
				</div>
		    </div>
		</form>
	 </section>
	 <!-- Basic Inputs end -->
</div>



@endsection
@push('js')


@endpush