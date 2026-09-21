@extends('admin.layouts.app')
@section('title')
<title>Supplier Profile - {{general()->title}} | {{general()->subtitle}}</title>
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
     <h3 class="content-header-title mb-0">Supplier Profile</h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">Supplier Profile</li>
         </ol>
       </div>
     </div>
    </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
       
       <a class="btn btn-outline-primary" href="{{route('admin.supplierUsers')}}">
       		Back
       	</a>
       	<a class="btn btn-outline-primary" href="{{route('admin.supplierUsersEdit',$user->id)}}">
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
                        <h4 class="card-title">Profile:</h4>
                    </div>
                    <div class="card-content">
                         <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderedless">
                                        <tr>
                                            <th style="width: 150px;border-top: 0px solid #98A4B8;background: #e4e4e4;">Profile</th>
                                            <td style="border-top: 0px solid #98A4B8;text-align: right;padding:0;">
                                                @isset(json_decode(myrole()->permission, true)['suppliers']['update'])
                                                <button class="btn  btn-primary" style="padding: 5px 10px;" data-toggle="modal" data-target="#supplierUpdate"><i class="fa fa-edit"></i> Edit</button>
                                                @endisset
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 150px;">Name</th>
                                            <td >: {{$user->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Mobile</th>
                                            <td>: {{$user->mobile}}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>: {{$user->email}}</td>
                                        </tr>
                                        <tr>
                                            <th>Gender</th>
                                            <td>: {{$user->gender}}</td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td>: {{$user->fullAddress()}}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>: 
                                                @if($user->status)
                                                <span class="badge badge-success">Active </span>
                                                @else
                                                <span class="badge badge-danger">Inactive </span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderedless">
                                        <tr>
                                            <th style="width: 150px;border-top: 0px solid #98A4B8;background: #e4e4e4;">Purchase</th>
                                            <td style="border-top: 0px solid #98A4B8;"></td>
                                        </tr>
                                        <tr>
                                            <th>Total</th>
                                            <td>: {{priceFullFormat($reports['purchase_total'])}}</td>
                                        </tr>
                                        <tr>
                                            <th>Due</th>
                                            <td>: {{priceFullFormat($reports['purchase_due'])}}</td>
                                        </tr>
                                        <tr>
                                            <th>Advance</th>
                                            <td>: {{priceFullFormat($reports['purchase_advence'])}}</td>
                                        </tr>
                                        <tr>
                                            <th style="width: 150px;border-top: 0px solid #98A4B8;background: #ffc7c7;">Return</th>
                                            <td style="border-top: 0px solid #98A4B8;"></td>
                                        </tr>
                                        <tr>
                                            <th>Total</th>
                                            <td>: {{$reports['return_total']}}</td>
                                        </tr>
                                        <tr>
                                            <th>Due</th>
                                            <td>: {{$reports['return_due']}}</td>
                                        </tr>
                                        <tr>
                                            <th>Advance</th>
                                            <td>: {{$reports['return_advence']}}</td>
                                        </tr>
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
</div>

 <!-- Modal -->
 <div class="modal fade text-left" id="supplierUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
        <form action="{{route('admin.supplierUsersEdit',$user->id)}}" method="post" enctype="multipart/form-data">
            @csrf
       <div class="modal-header">
         <h4 class="modal-title" id="myModalLabel1">Add Suppliers</h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times; </span>
         </button>
       </div>
       <div class="modal-body">
            
            
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
                     <label for="name">User Name* </label>
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
                     <input type="text" class="form-control {{$errors->has('first_name')?'error':''}}" name="first_name" placeholder="Enter First Name" value="{{$user->first_name?:old('first_name')}}"  />
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
         <div class="col-xl-12 col-lg-12 col-md-12">
             <div class="form-group">
                 <div class="controls">
                     <label for="company_name">Company Name</label>
                     <input type="text" class="form-control {{$errors->has('company_name')?'error':''}}" name="company_name" placeholder="Enter Company Name" value="{{$user->company_name?:old('company_name')}}"  />
                    @if ($errors->has('company_name'))
                    <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('company_name') }}</p>
                    @endif
                 </div>
             </div>
         </div>
         <div class="col-xl-6 col-lg-6 col-md-12 ">
             <div class="form-group">
                 <div class="controls">
                     <label for="mobile">Mobile*</label>
                     <input type="text" class="form-control {{$errors->has('mobile')?'error':''}}" name="mobile"  placeholder="Enter Mobile" value="{{$user->mobile?:old('mobile')}}" required="" />
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
                 @if ($errors->has('division'))
                    <p style="color: red;margin: 0;font-size: 10px;">{{ $errors->first('division') }}</p>
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
         <div class="col-xl-6 col-lg-6 col-md-12 ">
             <div class="form-group">
                <label for="helpInputTop">User Status</label>
                <div class="custom-control custom-checkbox">
                 <input type="checkbox" class="custom-control-input"  name="status" id="status"  {{$user->status?'checked':''}}/>
                 <label class="custom-control-label" for="status">User Active</label>
               </div>
             </div>
         </div>
     </div>


       </div>
       <div class="modal-footer">
         <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close </button>
         <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Update Supplier</button>
       </div>
       </form>
     </div>
   </div>
 </div>

@endsection
@push('js')



@endpush