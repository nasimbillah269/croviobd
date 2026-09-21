@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>Profile Edit | {{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta name="keywords" content="{{App\Models\General::latest()->first()->meta_key}}">
<meta property="og:title" content="{{App\Models\General::latest()->first()->name}}">
<meta property="og:description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta property="og:image" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta property="og:url" content="{{route('index')}}">
@endsection
@push('css')

<style type="text/css">
    
</style>

@endpush
@section('contents')

<div class="userdashboard">
    <div class="container">
    <div class="row" style="margin:0;">
        <div class="col-lg-3 usersidebardiv">
            @include(App\Models\General::first()->theme.'.customer.includes.sidebar')
            
        </div>
        <div class="col-lg-9 usermainbody">
            <div class="usercontent">
                <div class="myrecentorder">
                    @include(App\Models\General::first()->theme.'.alerts')
                    <p style="font-weight: bold;border-bottom: 1px solid #eaeded;padding: 5px 0;">My Profile Update</p>
                    <div>
                        <form method="POST" action="{{ route('customer.profileUpdate') }}" enctype="multipart/form-data">
                            @csrf
                                  <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">Name*</label>
                                    <div class="col-sm-9">
                                      @if ($errors->has('name'))
                                        <p style="color: red;margin: 0;">{{ $errors->first('name') }}</p>
                                      @endif
                                      <input type="text" class="form-control" name="name" value="{{Auth::user()->name}}"  placeholder="Your Name" required="">
                                    </div>
                                  </div>
                                   <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">Company Name </label>
                                    <div class="col-sm-9">
                                        @if ($errors->has('company'))
                                        <p style="color: red;margin: 0;">{{ $errors->first('company') }}</p>
                                      @endif
                                      <div class="input-group mb-3">
                                           <input type="text" class="form-control" name="company" value="{{Auth::user()->company_name?:old('company')}}" placeholder="Company Name">
                                        </div>
                                    </div>
                                  </div>
                                   <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">Email Name*</label>
                                    <div class="col-sm-9">
                                        @if ($errors->has('email'))
                                        <p style="color: red;margin: 0;">{{ $errors->first('email') }}</p>
                                      @endif
                                      <div class="input-group mb-3">
                                           <input type="email" class="form-control" name="email" value="{{Auth::user()->email}}" placeholder="Email Address" required="">
                                        </div>
                                    </div>
                                  </div>
                                   <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">Mobile Number*</label>
                                    <div class="col-sm-9">
                                        @if ($errors->has('mobile'))
                                        <p style="color: red;margin: 0;">{{ $errors->first('mobile') }}</p>
                                        @endif
                                        <div class="input-group mb-3">
                                          <input type="text" name="mobile" class="form-control" placeholder="Phone Number" value="{{Auth::user()->mobile}}" >
                                        </div>
                                    </div>
                                  </div>
                                  
                                  <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">District*</label>
                                    <div class="col-sm-9">
                                        @if ($errors->has('prefecture'))
                                        <p style="color: red;margin: 0;">{{ $errors->first('prefecture') }}</p>
                                      @endif
                                      <select class="form-control"  name="prefecture" >
                                          <option value="">Select District</option>
                                          @foreach(App\Models\Country::where('type',3)->orderBy('name')->get() as $data)
                                          <option value="{{$data->id}}" {{Auth::user()->district==$data->id?'selected':''}}>{{$data->name}}</option>
                                          @endforeach
                                      </select>
                                    </div>
                                  </div>
                                  
                                  <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">Town / City*</label>
                                    <div class="col-sm-4">
                                        @if ($errors->has('city'))
                                        <p style="color: red;margin: 0;">{{ $errors->first('city') }}</p>
                                      @endif
                                      <input type="text" class="form-control" name="city" value="{{Auth::user()->city_name?:old('city')}}" placeholder="Enter City" >
                                    </div>
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Postal Code*</label>
                                    <div class="col-sm-3">
                                        @if ($errors->has('postal_code'))
                                        <p style="color: red;margin: 0;">{{ $errors->first('postal_code') }}</p>
                                      @endif
                                      <input type="text" class="form-control" name="postal_code" value="{{Auth::user()->postal_code?:old('postal_code')}}" placeholder="Enter Postal Code">
                                    </div>
                                  </div>
                                  
                                 
                                 {{--<div class="form-group row">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">Division*</label>
                                    <div class="col-sm-9">
                                        @if ($errors->has('division'))
                                        <p style="color: red;margin: 0;">{{ $errors->first('division') }}</p>
                                      @endif
                                      <select class="form-control regionname" id="division" name="division">
                                          <option value="">Select Option</option>
                                          @foreach(App\Models\Country::where('type',2)->orderBy('name')->get() as $data)
                                          <option value="{{$data->id}}" {{Auth::user()->division==$data->id?'selected':''}}>{{$data->name}}</option>
                                          @endforeach
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">District*</label>
                                    <div class="col-sm-9">
                                        @if ($errors->has('district'))
                                        <p style="color: red;margin: 0;">{{ $errors->first('district') }}</p>
                                      @endif
                                      <select class="form-control district" id="district" name="district">
                                          <option value="">Select Option</option>
                                          @foreach(App\Models\Country::where('parent_id',Auth::user()->division)->orderBy('name')->get() as $data)
                                          <option value="{{$data->id}}" {{Auth::user()->district==$data->id?'selected':''}}>{{$data->name}}</option>
                                          @endforeach

                                      </select>
                                    </div>
                                  </div>
                                  
                                  <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">City*</label>
                                    <div class="col-sm-9">
                                        @if ($errors->has('city'))
                                        <p style="color: red;margin: 0;">{{ $errors->first('city') }}</p>
                                      @endif
                                      <select class="form-control cityname" id="city" name="city">
                                          <option value="">Select Option</option>

                                          @foreach(App\Models\Country::where('parent_id',Auth::user()->district)->orderBy('name')->get() as $data)
                                          <option value="{{$data->id}}" {{Auth::user()->city==$data->id?'selected':''}}>{{$data->name}}</option>
                                          @endforeach

                                      </select>
                                    </div>
                                  </div>
                                    --}}
                                    
                                  <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">Address*</label>
                                    <div class="col-sm-9">
                                        @if ($errors->has('address'))
                                        <p style="color: red;margin: 0;">{{ $errors->first('address') }}</p>
                                      @endif
                                      <input type="text" class="form-control" name="address" value="{{Auth::user()->address_line1}}"  placeholder="Exp: House Road..">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">Uploard Photo</label>
                                    <div class="col-8 col-sm-7">
                                        @if ($errors->has('image'))
                                        <p style="color: red;margin: 0;">{{ $errors->first('image') }}</p>
                                      @endif
                                      <input type="file" class="form-control" name="image" style="padding: 3px;">
                                    </div>
                                    <div class="col-4 col-sm-2">
                                        <div class="profileImage">
                                           <img src="{{asset(Auth::user()->image())}}" width="100px">
                                        </div>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                     <button type="submit" class="btn btn-primary">Update Profile</button>
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