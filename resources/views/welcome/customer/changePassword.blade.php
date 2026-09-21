@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>Change Password | {{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
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
                    <p style="font-weight: bold;border-bottom: 1px solid #eaeded;padding: 5px 0;">Change Password</p>
                    <div>
                        <form method="POST" action="{{route('customer.changePasswordUpdate')}}">
                            @csrf
                          <div class="form-group row">
                            <label for="inputPassword" class="col-sm-3 col-form-label">Current password</label>
                            <div class="col-sm-9">
                                @if ($errors->has('current_password'))
                                    <p style="color: red;margin: 0;">{{ $errors->first('current_password') }}</p>
                                  @endif
                              <input type="password" class="form-control" name="current_password" placeholder="Current Password">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="inputPassword" class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-9">
                                 @if ($errors->has('password'))
                                    <p style="color: red;margin: 0;">{{ $errors->first('password') }}</p>
                                  @endif
                              <input type="password" class="form-control" name="password" placeholder="New Password">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="inputPassword" class="col-sm-3 col-form-label">Confirmed</label>
                            <div class="col-sm-9">
                              <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                            </div>
                          </div>
                          <div class="form-group">
                            <button type="submit" class="btn btn-primary">Change Password</button>
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