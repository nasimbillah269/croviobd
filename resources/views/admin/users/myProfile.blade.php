@extends('admin.layouts.app')
@section('title')
<title>My Profile {{general()->title}} | {{general()->subtitle}}</title>
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/users.min.css')}}" />
<style type="text/css">
.ProfileImage{
        max-width: 64px;
        max-height: 64px;
    }
</style>
@endpush
@section('contents')


<div class="content-body">
	<div id="user-profile">

     @include('admin.alerts')
    
     <div class="row">
     		<div class="col-md-7">

     				<div class="card">
			     		<div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
							 	<h4 class="card-title">My Profile</h4>
						 	</div>
			        <div class="card-content">
			          <div class="card-body">

			          	<form action="{{route('admin.myProfileUpdate')}}" method="post" enctype="multipart/form-data">
						 	@csrf
						 	<div class="media">
                                 <a href="javascript: void(0);">
                                     <img src="{{asset($user->image())}}" class="ProfileImage rounded mr-75" alt="profile image" />
                                 </a>
                                 <div class="media-body mt-75">
                                     <div class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">
                                         <label class="btn btn-sm btn-primary ml-50 mb-50 mb-sm-0 cursor-pointer" for="account-upload">Upload new photo </label>
                                         <input type="file" name="image" id="account-upload" hidden="" />
                                         
                                         @if($user->imageFile)
                                         <a href="{{route('admin.mediesDelete',$user->imageFile->id)}}" class="mediaDelete btn btn-sm btn-secondary ml-50">Reset </a>
                                         @endif
                                        
                                     </div>
                                        @if ($errors->has('image'))
                                            <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('image') }}</p>
                                        @endif
                                     <p class="text-muted ml-75 mt-50"><small>Allowed JPG, GIF or PNG. Max size of 800kB</small></p>
                                 </div>
                             </div>
                            
						     <div class="row">
						         <div class="col-xl-6 col-lg-6 col-md-12">
						             <div class="form-group">
						                 <div class="controls">
						                     <label for="name">Name* </label>
						                     <input type="text" class="form-control {{$errors->has('name')?'error':''}}" name="name" placeholder="Enter Name" value="{{$user->name?:old('name')}}" required="" />
						                    @if ($errors->has('name'))
						                    <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('name') }}</p>
						                    @endif
						                 </div>
						             </div>
						         </div>
						         <div class="col-xl-6 col-lg-6 col-md-12">
						             <div class="form-group">
						                 <div class="controls">
						                     <label for="email">Email* </label>
						                     <input type="email" class="form-control {{$errors->has('email')?'error':''}}" name="email" placeholder="Enter Email" value="{{$user->email?:old('email')}}" required="">
						                    @if ($errors->has('email'))
						                    <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('email') }}</p>
						                    @endif
						                 </div>
						             </div>
						         </div>
						         <div class="col-xl-6 col-lg-6 col-md-12 ">
						             <div class="form-group">
						                 <div class="controls">
						                     <label for="mobile">Mobile* </label>
						                     <input type="text" class="form-control {{$errors->has('mobile')?'error':''}}" name="mobile"  placeholder="Enter Mobile" value="{{$user->mobile?:old('mobile')}}"/>
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
						         
						         <div class="col-xl-4 col-lg-4 col-md-12">
						             <div class="form-group">
						                 <label for="prefecture">District* </label>
						                 <select class="form-control {{$errors->has('Prefecture')?'error':''}}"  name="prefecture">
						                        <option value="">Select District</option>

							                    @foreach(App\Models\Country::where('type',3)->orderBy('name')->get() as $data)
							                    <option value="{{$data->id}}" {{$data->id==$user->district?'selected':''}}>{{$data->name}}</option>
							                    @endforeach

						                 </select>
						                 @if ($errors->has('prefecture'))
						                    <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('prefecture') }}</p>
						                 @endif
						             </div>
						         </div>
						         <div class="col-xl-4 col-lg-4 col-md-12">
						             <div class="form-group">
						                 <label for="city">Town / City* </label>
						                 <input type="text" class="form-control" name="city" value="{{$user->city_name?:old('city')}}" placeholder="Enter City">
						                 @if ($errors->has('city'))
						                    <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('city') }}</p>
						                 @endif
						             </div>
						         </div>
						         
						        
						         <div class="col-xl-4 col-lg-4 col-md-12">
						             <div class="form-group">
						                 <div class="controls">
						                     <label for="postal_code">Postal Code</label>
						                     <input type="text" class="form-control {{$errors->has('postal_code')?'error':''}}" name="postal_code"  placeholder="Enter Postal Code" value="{{$user->postal_code?:old('postal_code')}}"/>
						                 </div>
						             </div>
						         </div>
						         <div class="col-xl-12 col-lg-12 col-md-12">
						             <div class="form-group">
						                 <div class="controls">
						                     <label for="address">Address Line</label>
						                     <input type="text" class="form-control {{$errors->has('address')?'error':''}}" name="address"  placeholder="Enter Address" value="{{$user->address_line1?:old('address')}}"/>
						                 </div>
						             </div>
						         </div>

						         <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
						             <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save
						                changes </button>
						         </div>
						     </div>
						 </form>
			          </div>
			        </div>
			      </div>
     		</div>
     		<div class="col-md-5">
     				<div class="card">
			     		<div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
							 	<h4 class="card-title">Change Password</h4>
						 	</div>
			        <div class="card-content">
			          <div class="card-body">
		                  <form action="{{route('admin.myProfileChangePassword')}}" method="post" >
						 	@csrf
						     <div class="row">
						         <div class="col-xl-12 col-lg-12 col-md-12">
						            <div class="form-group">
						                <label for="old_password">Old password </label>
						                <div class="input-group">
						                    <input type="password" class="form-control password"  placeholder="Old Password" name="old_password" value="{{old('old_password')}}" required="">
						                    <div class="input-group-append">
						                        <span class="input-group-text showPassword" ><i class="fa fa-eye-slash "></i></span>
						                    </div>
						                </div>
						             </div>
						         </div>
						         <div class="col-xl-12 col-lg-12 col-md-12">
						             <div class="form-group">
						                <label for="password">New Password </label>
						                <input type="password" class="form-control password {{$errors->has('password')?'error':''}}" name="password" placeholder="New password"  required="" />
						                @if ($errors->has('password'))
						                <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('password') }}</p>
						                @endif
						             </div>
						         </div>
						         <div class="col-xl-12 col-lg-12 col-md-12 ">
						             <div class="form-group">
						                <label for="password_confirmation">Confirmed Password </label>
						                <input type="password" class="form-control password {{$errors->has('password_confirmation')?'error':''}}" name="password_confirmation" placeholder="Confirmed password"  required="" />
						                @if ($errors->has('password_confirmation'))
						                <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('password_confirmation') }}</p>
						                @endif
						             </div>
						         </div>

						         <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
						             <button type="submit" class="btn btn-danger mr-sm-1 mb-1 mb-sm-0">Change Password</button>
						         </div>
						     </div>
						 </form>
			          </div>
			        </div>
			      </div>
     		</div>
     </div>
     





 	</div>
 </div>



@endsection
@push('js')

@endpush