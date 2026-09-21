@extends('admin.layouts.app')
@section('title')
<title>Add User - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@push('css')
<style type="text/css">

</style>
@endpush
@section('contents')


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
     <h3 class="content-header-title mb-0">Add User</h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">Add User</li>
         </ol>
       </div>
     </div>
    </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
       
       <a class="btn btn-outline-primary reloadPage" href="{{route('admin.usersCustomer')}}">
       		Back
       	</a>
       	<a class="btn btn-outline-primary reloadPage" href="javascript:void(0)">
       		<i class="fa-solid fa-rotate"></i>
       	</a>
     </div>
   </div>
</div>
 
	

 <div class="content-body"><!-- Basic Elements start -->
	 <section class="basic-elements">
	     <div class="row">
	         <div class="col-md-12">
	         	@include('admin.alerts')
                 
                 <div class="card">
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                        <h4 class="card-title">New User Register</h4>
                    </div>
                    <div class="card-content">
                         <div class="card-body">

                            <form action="{{route('admin.usersCustomerPost')}}" method="post">
                                @csrf
                            
                                <div class="row">
                                 <div class="col-xl-6 col-lg-6 col-md-12">
                                     <div class="form-group">
                                         <label for="basicInput">User Name *</label>
                                         <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter name" class="form-control {{$errors->has('name')?'error':''}}" required="" />
                                         @if ($errors->has('name'))
                                        <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('name') }}</p>
                                        @endif
                                     </div>
                                 </div>
                                 <div class="col-xl-6 col-lg-6 col-md-12">
                                     <div class="form-group">
                                         <label for="basicInput">User Email *</label>
                                         <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email Address" class="form-control {{$errors->has('email')?'error':''}}" required="" />
                                         @if ($errors->has('email'))
                                        <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('email') }}</p>
                                        @endif
                                     </div>
                                 </div>
                                 <div class="col-xl-6 col-lg-6 col-md-12">
                                     <div class="form-group">
                                         <label for="basicInput">User Mobile</label>
                                         <input type="text" name="mobile" value="{{ old('mobile') }}" placeholder="Enter Mobile Number" class="form-control {{$errors->has('mobile')?'error':''}}"  />
                                         @if ($errors->has('mobile'))
                                        <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('mobile') }}</p>
                                        @endif
                                     </div>
                                 </div>

                                 <div class="col-xl-6 col-lg-6 col-md-12">
                                     <div class="form-group">
                                         <label for="basicInput">Password*</label>
                                         <input type="text" name="password" value="{{old('password')}}" placeholder="Enter Password" class="form-control {{$errors->has('password')?'error':''}}" required="" />
                                         @if ($errors->has('password'))
                                        <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('password') }}</p>
                                        @endif
                                     </div>
                                 </div>
                                  <div class="col-xl-6 col-lg-6 col-md-12 ">
                                     <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                         <input type="checkbox" class="custom-control-input"  name="send_email" id="send_email"  />
                                         <label class="custom-control-label" for="send_email">Send Email</label>
                                       </div>
                                     </div>
                                      <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                         <input type="checkbox" class="custom-control-input"  name="send_sms" id="send_sms"  />
                                         <label class="custom-control-label" for="send_sms">Send SMS</label>
                                       </div>
                                     </div>
                                 </div>

                                 <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
    
                                     <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0"><i class="fa fa-plus"></i> Add User</button>
                                 </div>
                             </div>

                             </form>
                         </div>
                     </div>
                 </div>

	         </div>
	     </div>
	 </section>
	 <!-- Basic Inputs end -->
</div>



@endsection
@push('js')


@endpush