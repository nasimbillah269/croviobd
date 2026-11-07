@extends(general()->theme.'.layouts.app')
@section('title')
<title>Profile Edit | {{general()->title}} | {{general()->subtitle}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!general()->meta_dsc!!}">
<meta name="keywords" content="{{general()->meta_key}}">
<meta property="og:title" content="{{general()->name}}">
<meta property="og:description" content="{!!general()->meta_dsc!!}">
<meta property="og:image" content="{{asset(general()->logo())}}">
<meta property="og:url" content="{{route('index')}}">
@endsection
@push('css')
@endpush
@section('contents')

<div class="userdashboard">
    <div class="container">
    <div class="row">
        <div class="col-lg-3 usersidebardiv">
            @include(general()->theme.'.customer.includes.sidebar')
        </div>
        <div class="col-lg-9 usermainbody">

            @include(general()->theme.'.alerts')

            <div class="cd-card">
                <div class="cd-card-head">
                    <div>
                        <h4>Edit Profile</h4>
                        <small>Update your personal information and shipping address</small>
                    </div>
                </div>
                <form method="POST" action="{{ route('customer.profileUpdate') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="cd-avatar-upload">
                        <img src="{{asset(Auth::user()->image())}}" alt="{{Auth::user()->name}}" id="cdAvatarPreview">
                        <div style="flex:1;width:100%;">
                            <label for="image" style="margin-bottom:6px;">Profile Photo</label>
                            @if ($errors->has('image'))<p class="cd-error">{{ $errors->first('image') }}</p>@endif
                            <input type="file" id="image" class="form-control" name="image" accept="image/*">
                            <div class="cd-hint">JPG or PNG, a square image looks best.</div>
                        </div>
                    </div>

                    <div class="cd-form-grid">
                        <div class="form-group">
                            <label for="name">Full Name <span style="color:#dc2626;">*</span></label>
                            @if ($errors->has('name'))<p class="cd-error">{{ $errors->first('name') }}</p>@endif
                            <input type="text" id="name" class="form-control" name="name" value="{{Auth::user()->name}}" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <label for="company">Company Name</label>
                            @if ($errors->has('company'))<p class="cd-error">{{ $errors->first('company') }}</p>@endif
                            <input type="text" id="company" class="form-control" name="company" value="{{Auth::user()->company_name?:old('company')}}" placeholder="Company Name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address <span style="color:#dc2626;">*</span></label>
                            @if ($errors->has('email'))<p class="cd-error">{{ $errors->first('email') }}</p>@endif
                            <input type="email" id="email" class="form-control" name="email" value="{{Auth::user()->email}}" placeholder="Email Address" required>
                        </div>
                        <div class="form-group">
                            <label for="mobile">Mobile Number <span style="color:#dc2626;">*</span></label>
                            @if ($errors->has('mobile'))<p class="cd-error">{{ $errors->first('mobile') }}</p>@endif
                            <input type="text" id="mobile" name="mobile" class="form-control" placeholder="Phone Number" value="{{Auth::user()->mobile}}">
                        </div>
                        <div class="form-group cd-full">
                            <label for="address">Address <span style="color:#dc2626;">*</span></label>
                            @if ($errors->has('address'))<p class="cd-error">{{ $errors->first('address') }}</p>@endif
                            <input type="text" id="address" class="form-control" name="address" value="{{Auth::user()->address_line1}}" placeholder="House, Road, Area">
                        </div>
                        <div class="form-group">
                            <label for="prefecture">District <span style="color:#dc2626;">*</span></label>
                            @if ($errors->has('prefecture'))<p class="cd-error">{{ $errors->first('prefecture') }}</p>@endif
                            <select class="form-control" id="prefecture" name="prefecture">
                                <option value="">Select District</option>
                                @foreach(App\Models\Country::where('type',3)->orderBy('name')->get() as $data)
                                <option value="{{$data->id}}" {{Auth::user()->district==$data->id?'selected':''}}>{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="city">Town / City <span style="color:#dc2626;">*</span></label>
                            @if ($errors->has('city'))<p class="cd-error">{{ $errors->first('city') }}</p>@endif
                            <input type="text" id="city" class="form-control" name="city" value="{{Auth::user()->city_name?:old('city')}}" placeholder="Enter City">
                        </div>
                        <div class="form-group">
                            <label for="postal_code">Postal Code <span style="color:#dc2626;">*</span></label>
                            @if ($errors->has('postal_code'))<p class="cd-error">{{ $errors->first('postal_code') }}</p>@endif
                            <input type="text" id="postal_code" class="form-control" name="postal_code" value="{{Auth::user()->postal_code?:old('postal_code')}}" placeholder="Enter Postal Code">
                        </div>
                    </div>

                    <div class="cd-form-actions">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="{{route('customer.dashboard')}}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
    </div>
</div>
@endsection
@push('js')
<script>
    document.getElementById('image').addEventListener('change', function (e) {
        var file = e.target.files && e.target.files[0];
        if (file) document.getElementById('cdAvatarPreview').src = URL.createObjectURL(file);
    });
</script>
@endpush
