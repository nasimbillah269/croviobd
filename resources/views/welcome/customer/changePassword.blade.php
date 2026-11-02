@extends(App\Models\General::first()->theme.'.layouts.app')
@section('title')
<title>Change Password | {{App\Models\General::first()->title}} | {{App\Models\General::first()->subtitle}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
<meta name="keywords" content="{{App\Models\General::latest()->first()->meta_key}}">
<meta property="og:title" content="{{App\Models\General::latest()->first()->name}}">
<meta property="og:description" content="{!!App\Models\General::latest()->first()->meta_dsc!!}">
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
            @include(App\Models\General::first()->theme.'.customer.includes.sidebar')
        </div>
        <div class="col-lg-9 usermainbody">

            @include(App\Models\General::first()->theme.'.alerts')

            <div class="cd-card" style="max-width:640px;">
                <div class="cd-card-head">
                    <div>
                        <h4>Change Password</h4>
                        <small>Use at least 8 characters for a strong password</small>
                    </div>
                </div>
                <form method="POST" action="{{route('customer.changePasswordUpdate')}}">
                    @csrf
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        @if ($errors->has('current_password'))<p class="cd-error">{{ $errors->first('current_password') }}</p>@endif
                        <input type="password" id="current_password" class="form-control" name="current_password" placeholder="Enter current password" autocomplete="current-password">
                    </div>
                    <div class="form-group">
                        <label for="password">New Password</label>
                        @if ($errors->has('password'))<p class="cd-error">{{ $errors->first('password') }}</p>@endif
                        <input type="password" id="password" class="form-control" name="password" placeholder="Enter new password" autocomplete="new-password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="Re-enter new password" autocomplete="new-password">
                    </div>
                    <div class="cd-form-actions">
                        <button type="submit" class="btn btn-primary">Update Password</button>
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
@endpush
