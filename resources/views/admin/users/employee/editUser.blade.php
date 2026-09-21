@extends('admin.layouts.app')
@section('title')
<title>User Profile - {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@push('css')
<style type="text/css">
    .showPassword {
    right: 0 !important;
    cursor: pointer;
    }
    .ProfileImage{
        max-width: 64px;
        max-height: 64px;
    }
</style>
@endpush
@section('contents')


<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
     <h3 class="content-header-title mb-0">User Profile</h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">User Profile</li>
         </ol>
       </div>
     </div>
    </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
       
       <a class="btn btn-outline-primary" href="{{route('admin.employeeUser')}}">
       		Back
       	</a>
       	<a class="btn btn-outline-primary reloadPage" href="javascript:void(0)">
       		<i class="fa-solid fa-rotate"></i>
       	</a>
     </div>
   </div>
</div>
 
	

 <div class="content-body"><!-- Basic Elements start -->
	 
@include('admin.alerts')
    <!-- account setting page start -->
 <section id="page-account-settings">
     <div class="row">
         <div class="col-md-9">
             <div class="card">
                 <div class="card-content">
                     <div class="card-body">
                        
                        <form action="{{route('admin.employeeUserEdit',$user->id)}}" method="post" enctype="multipart/form-data">

                            @csrf
                            <div class="media">
                                 <a href="javascript: void(0);">
                                     <img src="{{asset($user->image())}}" class="ProfileImage rounded mr-75" alt="profile image" />
                                 </a>
                                 <div class="media-body mt-75">
                                     <div class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">
                                         <label class="btn btn-sm btn-primary ml-50 mb-50 mb-sm-0 cursor-pointer" for="account-upload">Upload new photo </label>
                                         <input type="file" name="image" id="account-upload" hidden="" />
                                         @isset(json_decode(Auth::user()->permission->permission, true)['users']['update'])
                                         @if($user->imageFile)
                                         <a href="{{route('admin.mediesDelete',$user->imageFile->id)}}" class="mediaDelete btn btn-sm btn-secondary ml-50">Reset </a>
                                         @endif
                                         @endisset
                                     </div>
                                        @if ($errors->has('image'))
                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('image') }}</p>
                                        @endif
                                     <p class="text-muted ml-75 mt-50"><small>Allowed JPG, GIF or PNG. Max size of 800kB</small></p>
                                 </div>
                             </div>
                             <hr />

                             <div class="row">
                                 <div class="col-xl-6 col-lg-6 col-md-12">
                                     <div class="form-group">
                                         <div class="controls">
                                             <label for="name">User Name </label>
                                             <input type="text" class="form-control {{$errors->has('name')?'error':''}}"  name="name" placeholder="Enter Name" value="{{$user->name?:old('name')}}" required="" />
                                            @if ($errors->has('name'))
                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('name') }}</p>
                                            @endif
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-xl-6 col-lg-6 col-md-12">
                                     <div class="form-group">
                                         <div class="controls">
                                             <label for="email">Email </label>
                                             <input type="email" class="form-control {{$errors->has('email')?'error':''}}" name="email" placeholder="Enter Email" value="{{$user->email?:old('email')}}" >
                                            @if ($errors->has('email'))
                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('email') }}</p>
                                            @endif
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-xl-6 col-lg-6 col-md-12">
                                     <div class="form-group">
                                         <div class="controls">
                                             <label for="first_name">First Name</label>
                                             <input type="text" class="form-control {{$errors->has('first_name')?'error':''}}" name="first_name" placeholder="Enter First Name" value="{{$user->first_name?:old('first_name')}}" />
                                            @if ($errors->has('first_name'))
                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('first_name') }}</p>
                                            @endif
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-xl-6 col-lg-6 col-md-12">
                                     <div class="form-group">
                                         <div class="controls">
                                             <label for="last_name">Last Name </label>
                                             <input type="text" class="form-control {{$errors->has('last_name')?'error':''}}" name="last_name" placeholder="Enter Last Name" value="{{$user->last_name?:old('last_name')}}" >
                                            @if ($errors->has('last_name'))
                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('last_name') }}</p>
                                            @endif
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-xl-6 col-lg-6 col-md-12 ">
                                     <div class="form-group">
                                         <div class="controls">
                                             <label for="mobile">Mobile* </label>
                                             <input type="text" class="form-control {{$errors->has('mobile')?'error':''}}" name="mobile"  placeholder="Enter Mobile" value="{{$user->mobile?:old('mobile')}}" required=""/>
                                            @if ($errors->has('mobile'))
                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('mobile') }}</p>
                                            @endif
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-xl-6 col-lg-6 col-md-12">
                                     <div class="form-group">
                                         <label for="gender">Gender </label>
                                         <select class="form-control {{$errors->has('gender')?'error':''}}" name="gender">
                                             <option value="">Select Gender</option>
                                             <option value="Male" {{$user->gender=='Male'?'selected':''}}>Male</option>
                                              <option value="Female" {{$user->gender=='Female'?'selected':''}}>Female</option>
                                         </select>
                                        @if ($errors->has('gender'))
                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('gender') }}</p>
                                        @endif
                                     </div>
                                 </div>
                                 
                                  <div class="col-xl-6 col-lg-6 col-md-12">
						             <div class="form-group">
						                 <label for="division">Country* </label>
						                 <input type="text" class="form-control" readonly="" value="Japan">
						             </div>
						         </div>
						         
						         <div class="col-xl-6 col-lg-6 col-md-12">
						             <div class="form-group">
						                 <label for="prefecture">Prefecture* </label>
						                 <select class="form-control {{$errors->has('Prefecture')?'error':''}}" name="prefecture">
						                        <option value="">Select Prefecture</option>

							                    @foreach(App\Models\Country::where('type',2)->where('parent_id',629)->orderBy('name')->get() as $data)
							                    <option value="{{$data->id}}" {{$data->id==$user->district?'selected':''}}>{{$data->name}}</option>
							                    @endforeach

						                 </select>
						                 @if ($errors->has('prefecture'))
						                    <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('prefecture') }}</p>
						                 @endif
						             </div>
						         </div>
						         <div class="col-xl-6 col-lg-6 col-md-12">
						             <div class="form-group">
						                 <label for="city">Town / City* </label>
						                 <input type="text" class="form-control" name="city" value="{{$user->city_name?:old('city')}}" placeholder="Enter City">
						                 @if ($errors->has('city'))
						                    <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('city') }}</p>
						                 @endif
						             </div>
						         </div>
                                 
                                 {{--
                                 <div class="col-xl-6 col-lg-6 col-md-12">
                                     <div class="form-group">
                                         <label for="division">Division </label>
                                         <select id="division" class="form-control {{$errors->has('division')?'error':''}}" name="division">
                                             <option value="">Select Division</option>

                                                @foreach(App\Models\Country::where('type',2)->get() as $data)
                                                <option value="{{$data->id}}" {{$data->id==$user->division?'selected':''}}>{{$data->name}}</option>
                                                @endforeach

                                         </select>
                                         @if ($errors->has('gender'))
                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('gender') }}</p>
                                        @endif
                                     </div>
                                 </div>
                                 <div class="col-xl-6 col-lg-6 col-md-12">
                                     <div class="form-group">
                                         <label for="district">District </label>
                                         <select id="district" class="form-control {{$errors->has('district')?'error':''}}" name="district">
                                            @if($user->division==null)
                                            <option value="">No District</option>
                                            @else
                                            <option value="">Select District</option>
                                            @foreach(App\Models\Country::where('type',3)->where('parent_id',$user->division)->get() as $data)
                                            <option value="{{$data->id}}" {{$data->id==$user->district?'selected':''}}>{{$data->name}}</option>
                                            @endforeach
                                            @endif
                                         </select>
                                        @if ($errors->has('district'))
                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('district') }}</p>
                                        @endif
                                     </div>
                                 </div>
                                 <div class="col-xl-6 col-lg-6 col-md-12">
                                     <div class="form-group">
                                         <label for="city">City </label>
                                         <select id="city" class="form-control {{$errors->has('city')?'error':''}}" name="city">
                                             @if($user->district==null)
                                            <option value="">No City</option>
                                            @else
                                            <option value="">Select City</option>
                                            @foreach(App\Models\Country::where('type',4)->where('parent_id',$user->district)->get() as $data)
                                            <option value="{{$data->id}}" {{$data->id==$user->city?'selected':''}}>{{$data->name}}</option>
                                            @endforeach

                                            @endif
                                         </select>
                                         @if ($errors->has('city'))
                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('city') }}</p>
                                        @endif
                                     </div>
                                 </div>
                                 --}}
                                 <div class="col-xl-6 col-lg-6 col-md-12">
                                     <div class="form-group">
                                         <div class="controls">
                                             <label for="postal_code">Postal Code</label>
                                             <input type="text" class="form-control {{$errors->has('postal_code')?'error':''}}" name="postal_code"  placeholder="Enter Postal Code" value="{{$user->postal_code?:old('postal_code')}}"/>
                                            @if ($errors->has('postal_code'))
                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('postal_code') }}</p>
                                            @endif
                                         </div>
                                     </div>
                                 </div>
                                
                                 <div class="col-xl-12 col-lg-12 col-md-12">
                                     <div class="form-group">
                                         <div class="controls">
                                            <label for="address">Address Line</label>
                                            <input type="text" class="form-control {{$errors->has('address')?'error':''}}" name="address"  placeholder="Enter Address" value="{{$user->address_line1?:old('address')}}"/>
                                            @if ($errors->has('address'))
                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('address') }}</p>
                                            @endif
                                         </div>
                                     </div>
                                 </div>
                                  <div class="col-xl-12 col-lg-12 col-md-12">
                                     <div class="form-group">
                                         <div class="controls">
                                            <label for="designation">Designation</label>
                                            <input type="text" class="form-control {{$errors->has('designation')?'error':''}}" name="designation"  placeholder="Enter Designation" value="{{$user->designation?:old('designation')}}"/>
                                            @if ($errors->has('designation'))
                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('designation') }}</p>
                                            @endif
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-xl-6 col-lg-6 col-md-12 ">
                                     <div class="form-group">
                                        <label for="helpInputTop">User Status</label>
                                        <div class="custom-control custom-checkbox">
                                         <input type="checkbox" class="custom-control-input"  name="status" id="status"  {{$user->status?'checked':''}}/>
                                         <label class="custom-control-label" for="status">User Active</label>
                                       </div>
                                     </div>
                                 </div>

                                 @isset(json_decode(myrole()->permission, true)['employee']['update'])
                                 <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                     <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save
                                        changes </button>
                                 </div>
                                 @endisset
                             </div>
                         </form>


                     </div>
                 </div>
             </div>
         </div>
     </div>
 </section>
 <!-- account setting page end -->


</div>



@endsection
@push('js')



@endpush