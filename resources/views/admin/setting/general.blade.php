@extends('admin.layouts.app')
@section('title')
<title>{{ucfirst($type)}} Setting - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@push('css')
<style type="text/css">

</style>
@endpush
@section('contents')


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
     <h3 class="content-header-title mb-0">{{ucfirst($type)}} Setting</h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">{{ucfirst($type)}} Setting</li>
         </ol>
       </div>
     </div>
   </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
       	<a class="btn btn-outline-primary reloadPage" href="javascript:void(0)">
       		<i class="fa-solid fa-rotate"></i>
       	</a>
     </div>
   </div>
</div>
 
 <div class="content-body">

 	@include('admin.alerts')
 	<form action="{{route('admin.setitngUpdate',$type)}}" method="post" enctype="multipart/form-data">
   @csrf
 	<!-- Basic Elements start -->
	 <section class="basic-elements">
	     <div class="row">
	         <div class="col-md-12">

	             <div class="card">
	             	<div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
									 	<h4 class="card-title">General Info</h4>
								 	</div>
	                 <div class="card-content">
	                     <div class="card-body">
	                     	
	                         <div class="row">
	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="title">Website Title </label>
	                                     <input type="text" name="title" value="{{ $general->title }}" placeholder="Website Title" class="form-control {{$errors->has('title')?'error':''}}" />
	                                     @if ($errors->has('title'))
																	    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('title') }}</p>
																	    	@endif
	                                 </div>
	                             </div>
	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="subtitle">Website Subtitle</label>
	                                     <input type="text" name="subtitle" value="{{ $general->subtitle }}" placeholder="Website subtitle" class="form-control {{$errors->has('subtitle')?'error':''}}">
																    	@if ($errors->has('subtitle'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('subtitle') }}</p>
																    	@endif
	                                 </div>
	                             </div>
	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="mobile">Mobile Number</label>
	                                     <input type="text" name="mobile" value="{{ $general->mobile }}" placeholder="Website mobile" class="form-control {{$errors->has('mobile')?'error':''}}">
																    	@if ($errors->has('mobile'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('mobile') }}</p>
																    	@endif
	                                 </div>
	                             </div>
	                              <div class="col-xl-6 col-lg-6 col-md-12">
	                                  <div class="form-group">
	                                     <label for="email">Email Address</label>
	                                     <input type="text" name="email" value="{{ $general->email }}" placeholder="Website email" class="form-control {{$errors->has('email')?'error':''}}">
																    	@if ($errors->has('email'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('email') }}</p>
																    	@endif
	                                 </div>
	                             </div>

	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="address_one">Address line 1</label>
	                                     <textarea name="address_one"  placeholder="Address Line 1" class="form-control  {{$errors->has('address_one')?'error':''}}">{{ $general->address_one}}</textarea>
																    	@if ($errors->has('address_one'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('address_one') }}</p>
																    	@endif
	                                 </div>
	                             </div>
	                             
	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="address_two">Address line 2</label>
	                                      <textarea name="address_two"  placeholder="Address Line 1" class="form-control {{$errors->has('address_two')?'error':''}}">{{ $general->address_two}}</textarea>
																    	@if ($errors->has('address_two'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('address_two') }}</p>
																    	@endif
	                                 </div>
	                             </div>
	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="favicon">Favicon</label>
	                                      <input  type="file" name="favicon"  class="form-control {{$errors->has('favicon')?'error':''}}">
																    	@if ($errors->has('favicon'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('favicon') }}</p>
																    	@endif
	                                 </div>
	                             </div>
	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                    <img src="{{asset($general->favicon())}}" style="max-width: 60px;">
	                                    @if($general->favicon)
	                                    <a href="{{route('admin.setting','favicon')}}" style="color:red;" onclick="return confirm('Are You Want To Delete?')"><i class="fa fa-trash"></i></a>
	                                    @endif
	                                 </div>
	                             </div>
	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                    <label for="helpInputTop">Logo</label>
	                                    <input type="file" name="logo"  class="form-control {{$errors->has('logo')?'error':''}}">
																    	@if ($errors->has('logo'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('logo') }}</p>
																    	@endif
	                                 </div>
	                             </div>
	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                   <img src="{{asset($general->logo())}}" style="max-width: 150px;">
	                                   @isset(json_decode(Auth::user()->permission->permission, true)['appsSetting']['general'])
	                                   @if($general->logo)
	                                   <a href="{{route('admin.setting','logo')}}" style="color:red;" onclick="return confirm('Are You Want To Delete?')"><i class="fa fa-trash"></i></a>
	                                   @endif
	                                   @endisset
	                                 </div>
	                             </div>
	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="currency">Finance Currency</label>
	                                     
	                                     <div class="input-group">
	                                     <input type="text" name="currency" value="{{ $general->currency }}" placeholder="Finanmce Currency" class="form-control {{$errors->has('currency')?'error':''}}" />
	                                     <select class="form-control" name="currency_decimal">
	                                     		<option value="0" {{$general->currency_decimal==0?'selected':''}} >0 Decimal</option>
	                                     		<option value="1" {{$general->currency_decimal==1?'selected':''}} >0.0 Decimal</option>
	                                     		<option value="2" {{$general->currency_decimal==2?'selected':''}} >0.00 Decimal</option>
	                                     </select>
	                                     <select class="form-control" name="currency_position">
	                                     		<option value="0" {{$general->currency_position==0?'selected':''}} >Left Position</option>
	                                     		<option value="1" {{$general->currency_position==1?'selected':''}} >Right Position</option>
	                                     </select>
	                                     </div>
	                                     @if ($errors->has('currency'))
																	    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('currency') }}</p>
																	    	@endif
	                                 </div>
	                             </div>
	                             
	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                    <label for="website">Website Url</label>
	                                    <input type="text" name="website" value="{{ $general->website }}" placeholder="Website website"  class="form-control {{$errors->has('website')?'error':''}}">
																    	@if ($errors->has('website'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('website') }}</p>
																    	@endif
	                                 </div>
	                             </div>

	                             <div class="col-xl-12 col-lg-12 col-md-12">
	                                 <div class="form-group">
	                                    <label for="copyright_text">Footer Copyright Text</label>
	                                    <input type="text" name="copyright_text" value="{{ $general->copyright_text }}" placeholder="Website copyright_text"  class="form-control {{$errors->has('copyright_text')?'error':''}}">
																    	@if ($errors->has('copyright_text'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('copyright_text') }}</p>
																    	@endif
	                                 </div>
	                             </div>

	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                             		<label for="helpInputTop">Comming Soon Mode</label>
	                                 <div class="form-group">
                                    	
                                    	<div class="custom-control custom-checkbox">
											                <input type="checkbox" class="custom-control-input" name="commingsoon_mode" id="commingsoon_mode" {{$general->commingsoon_mode?'checked':''}} />
											                <label class="custom-control-label" for="commingsoon_mode">Active <small>(Show Your website maintenance Mode)</small></label>
											               	</div>
	                                 </div>

	                             </div>

	                         </div>

	                     </div>
	                 </div>
	             </div>

	             <div class="card">
	             		<div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
									 	<h4 class="card-title">SEO Optimize</h4>
								 	</div>
	                 <div class="card-content">
	                     <div class="card-body">
	                         <div class="row">

	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="meta_author">Meta Author</label>
	                                     <input type="text" name="meta_author" value="{{ $general->meta_author }}" placeholder="Meta Author" class="form-control {{$errors->has('meta_author')?'error':''}}" />
	                                     @if ($errors->has('meta_author'))
																	    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('meta_author') }}</p>
																	    	@endif
	                                 </div>
	                             </div>

	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="meta_title">Meta title <small>(Max: 60 L)</small></label>
	                                     <input type="text" name="meta_title" value="{{ $general->meta_title }}" placeholder="Meta title" class="form-control {{$errors->has('meta_title')?'error':''}}">
																    	@if ($errors->has('meta_title'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('meta_title') }}</p>
																    	@endif
	                                 </div>
	                             </div>

	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="meta_keyword">Meta keyword </label>
	                                     <textarea name="meta_keyword"  placeholder="Meta keyword" class="form-control  {{$errors->has('meta_keyword')?'error':''}}">{{ $general->meta_keyword}}</textarea>
																    	@if ($errors->has('meta_keyword'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('meta_keyword') }}</p>
																    	@endif
	                                 </div>
	                             </div>

	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="meta_description">Meta Description <small>(Max: 160 L)</small></label>
	                                     <textarea name="meta_description"  placeholder="Meta Description" class="form-control  {{$errors->has('meta_description')?'error':''}}">{{ $general->meta_description}}</textarea>
																    	@if ($errors->has('meta_description'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('meta_description') }}</p>
																    	@endif
	                                 </div>
	                             </div>

	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="script_head">Script tag Head</label>
	                                     <textarea name="script_head"  placeholder="Script tag Head" class="form-control  {{$errors->has('script_head')?'error':''}}">{{ $general->script_head}}</textarea>
																    	@if ($errors->has('script_head'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('script_head') }}</p>
																    	@endif
	                                 </div>
	                             </div>

	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="script_body">Script tag Body</label>
	                                     <textarea name="script_body"  placeholder="Script tag Body" class="form-control  {{$errors->has('script_body')?'error':''}}">{{ $general->script_body}}</textarea>
																    	@if ($errors->has('script_body'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('script_body') }}</p>
																    	@endif
	                                 </div>
	                             </div>

	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="custom_css">Style CSS</label>
	                                     <textarea name="custom_css"  placeholder="Custom Css write here..." class="form-control  {{$errors->has('custom_css')?'error':''}}">{{ $general->custom_css}}</textarea>
																    	@if ($errors->has('custom_css'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('custom_css') }}</p>
																    	@endif
	                                 </div>
	                             </div>

	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="custom_js">Script js</label>
	                                     <textarea name="custom_js"  placeholder="Custom Script js write here..." class="form-control  {{$errors->has('custom_js')?'error':''}}">{{ $general->custom_js}}</textarea>
																    	@if ($errors->has('custom_js'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('custom_js') }}</p>
																    	@endif
	                                 </div>
	                             </div>
	                             
	                         </div>
	                     </div>
	                 </div>

	             </div>



	             <div class="card">
								 	 <div class="card-content">
	                    <div class="card-body">
	                       <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                               <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save
                                  changes </button>
                           </div>
                        </div>
                     	</div>
                    </div>
                  </div>
	         </div>
	     </div>
	 </section>
	 <!-- Basic Inputs end -->
	</form>
</div>



@endsection
@push('js')

@endpush