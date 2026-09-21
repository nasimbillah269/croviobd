<div class="media">
    <h4 style="margin: 0;">Password Change</h4>
</div>
<hr />

<form action="{{route('admin.usersCustomerUpdate',[$user->id,'change-password'])}}" method="post" enctype="multipart/form-data">

    @csrf

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="form-group">
                <label for="name">Old password </label>
                <div class="input-group">
                    <input type="password" class="form-control password" placeholder="Old Password" name="old_password" value="{{$user->password_show?:old('old_password')}}" required="" />
                    <div class="input-group-append">
                        <span class="input-group-text showPassword"><i class="fa fa-eye-slash"></i></span>
                    </div>
                </div>
                @if ($errors->has('old_password'))
                <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('old_password') }}</p>
                @endif
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="form-group">
                <label for="name">New Password </label>
                <input type="password" class="form-control password {{$errors->has('password')?'error':''}}" name="password" placeholder="New password" required="" />
                @if ($errors->has('password'))
                <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('password') }}</p>
                @endif
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="form-group">
                <label for="name">Confirmed Password </label>
                <input type="password" class="form-control password {{$errors->has('password_confirmation')?'error':''}}" name="password_confirmation" placeholder="Confirmed password" required="" />
                @if ($errors->has('password_confirmation'))
                <p style="color: red; margin: 0; font-size: 10px;">{{ $errors->first('password_confirmation') }}</p>
                @endif
            </div>
        </div>
        @isset(json_decode(Auth::user()->permission->permission, true)['users']['update'])
        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
            <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save changes</button>
        </div>
        @endisset
    </div>
</form>

