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
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
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
									 	<h4 class="card-title">Mail Setting</h4>
								 	</div>
	                 <div class="card-content">
	                     <div class="card-body">
	                         <div class="row">
	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="mail_from_address">Mail Form Address </label>
	                                     <input type="text" name="mail_from_address" value="{{ $general->mail_from_address }}" placeholder="Mail From Address" class="form-control {{$errors->has('mail_from_address')?'error':''}}" />
	                                     @if ($errors->has('mail_from_address'))
																	    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('mail_from_address') }}</p>
																	    	@endif
	                                 </div>
	                             </div>
	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="mail_from_name">Mail Form Name</label>
	                                     <input type="text" name="mail_from_name" value="{{ $general->mail_from_name }}" placeholder="Mail From Name" class="form-control {{$errors->has('mail_from_name')?'error':''}}">
																    	@if ($errors->has('mail_from_name'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('mail_from_name') }}</p>
																    	@endif
	                                 </div>
	                             </div>
	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                     <label for="mail_driver">Mail Driver</label>
	                                     <select name="mail_driver" class="form-control {{$errors->has('mail_driver')?'error':''}}">
																	    		<option value="smtp" {{$general->mail_driver=='smtp'?'selected':''}}>SMTP</option>
																	    		<option value="mailgun" {{$general->mail_driver=='mailgun'?'selected':''}}>Mailgun</option>
																	    		<option value="sendmail" {{$general->mail_driver=='sendmail'?'selected':''}}>Sendmail</option>
																	    		<option value="mail" {{$general->mail_driver=='mail'?'selected':''}}>Mail</option>
																	    	</select>
																    	@if ($errors->has('mail_driver'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('mail_driver') }}</p>
																    	@endif
	                                 </div>
	                             </div>
	                              <div class="col-xl-6 col-lg-6 col-md-12">
	                                  <div class="form-group">
	                                     <label for="mail_host">Mail Host</label>
	                                     <input type="text" name="mail_host" value="{{ $general->mail_host }}" placeholder="Mail Host" class="form-control {{$errors->has('mail_host')?'error':''}}">
																    	@if ($errors->has('mail_host'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('mail_host') }}</p>
																    	@endif
	                                 </div>
	                             </div>

	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                  <div class="form-group">
	                                     <label for="mail_port">Mail Port</label>
	                                     <input type="text" name="mail_port" value="{{ $general->mail_port }}" placeholder="Mail Port" class="form-control {{$errors->has('mail_port')?'error':''}}">
																    	@if ($errors->has('mail_port'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('mail_port') }}</p>
																    	@endif
	                                 </div>
	                             </div>
	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                 <div class="form-group">
	                                    <label for="mail_encryption">Mail Encryption</label>
	                                    <select  name="mail_encryption" class="form-control {{$errors->has('mail_encryption')?'error':''}}">
																    		<option value="tls" {{$general->mail_encryption=='tls'?'selected':''}}>TLS</option>
																    		<option value="ssl" {{$general->mail_encryption=='ssl'?'selected':''}}>SSL</option>
																    		<option value="" {{$general->mail_encryption==null?'selected':''}}>Null</option>
																    	</select>
																    	@if ($errors->has('mail_encryption'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('mail_encryption') }}</p>
																    	@endif
	                                 </div>
	                             </div>
	                             <div class="col-xl-6 col-lg-6 col-md-12">
	                                  <div class="form-group">
	                                     <label for="mail_username">Mail Username</label>
	                                     <input type="text" name="mail_username" value="{{ $general->mail_username }}" placeholder="Mail Username  " class="form-control {{$errors->has('mail_username')?'error':''}}">
																    	@if ($errors->has('mail_username'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('mail_username') }}</p>
																    	@endif
	                                 </div>
	                             </div>
	                             <div class="col-xl-6 col-lg-6 col-md-12">

	                                  <div class="form-group">
	                                     	<label for="mail_password">Mail Password</label>
	                                      <div class="input-group">
																				 <input type="password" name="mail_password" value="{{$general->mail_password}}" placeholder="Mail Password" class="form-control password {{$errors->has('mail_password')?'error':''}}">
																				 <div class="input-group-append">
																					 <span class="input-group-text showPassword"><i class="fa fa-eye-slash"></i></span>
																				 </div>
																			 </div>
																    	@if ($errors->has('mail_password'))
																    	<p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('mail_password') }}</p>
																    	@endif
	                                 </div>

	                             </div>

	                             
	                             <div class="col-xl-6 col-lg-6 col-md-12">

	                                 <div class="form-group">
                                    	<label for="mail_status">Mail Status</label>
											               	<div class="custom-control custom-checkbox">
											                 <input type="checkbox" class="custom-control-input"  name="mail_status" id="mail_status"  {{$general->mail_status?'checked':''}}/>
											                 <label class="custom-control-label" for="mail_status">Active <small>(Mail System Active)</small></label>
											               </div>
	                                 </div>

	                             </div>
	                             <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                                   <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save
                                      changes </button>
                               </div>
	                         </div>
	                         <div class="row">
	                         		<div class="col-md-12">
	                         			<div class="card" style="padding: 15px;border: 2px solid #ff7e93;">
		                         			 <h4>Instruction</h4>
													         <p class="text-danger">
													         Please be carefull when you are configuring SMTP. For incorrect configuration you will get error at the time of order place, new registration, sending newsletter.
													       		</p>
																</div>
	                         		</div>
	                         		<div class="col-md-6">
	                         			<p>For Non-SSL</p>
										        		<table class="table table-bordered table-striped table-responsive">
																   <tbody>
																     <tr>
																       <td >Select 'sendmail' for Mail Driver if you face any issue after configuring 'smtp' as Mail Driver</td>
																     </tr>
																     <tr>
																       <td>Set Mail Host according to your server Mail Client Manual Settings</td>
																     </tr>
																     <tr>
																       <td >Set Mail port as '587'</td>
																     </tr>
																     <tr>
																       <td>Set Mail Encryption as 'ssl' if you face issue with 'tls'</td>
																     </tr>
																   </tbody>
																 </table>
	                         		</div>

												        <div class="col-md-6">
												        		<p>For SSL</p>
												        		<table class="table table-bordered table-striped table-responsive">
																		   <tbody>
																		     <tr>
																		       <td >Select 'sendmail' for Mail Driver if you face any issue after configuring 'smtp' as Mail Driver</td>
																		     </tr>
																		     <tr>
																		       <td>Set Mail Host according to your server Mail Client Manual Settings</td>
																		     </tr>
																		     <tr>
																		       <td >Set Mail port as '465'</td>
																		     </tr>
																		     <tr>
																		       <td>Set Mail Encryption as 'ssl'</td>
																		     </tr>
																		   </tbody>
																		 </table>
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