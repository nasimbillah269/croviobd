@extends(general()->theme.'.layouts.app') @section('title')
<title>{{general()->title}} | {{general()->subtitle}}</title>
@endsection @section('SEO')
<meta name="description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta property="og:title" content="{{general()->meta_title}}" />
<meta property="og:description" content="{!!general()->meta_description!!}" />
<meta property="og:image" content="{!!general()->meta_description!!}" />
<meta property="og:url" content="{{route('index')}}" />
@endsection @push('css')
@endpush 

@section('contents')

<div class="lostpass">
	<div class="lostpassheader">
		<h3>My Account</h3>
		<p>Sign Up</p>
	</div>
	<div class="container">
		<p>
			Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.
		</p>

		<form class="form-horizontal form-simple" action="{{route('resetPasswordCheck')}}" method="post">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                @if($errors->has('name'))
                    <span style="color:red;display: block;">{{ $errors->first('name') }}</span>
                @endif
                @if($errors->has('password'))
                    <span style="color:red;display: block;">{{ $errors->first('password') }}</span>
                @endif
                @include(App\Models\General::first()->theme.'.alerts')
            </div>
            
			<label for="verifycode">
				 Verify Code*
			</label>
			<div class="form-group form-group-section">
			    <input type="number" class="form-control form-control-lg" value="{{$r->code?:old('verifycode')}}" name="verifycode" placeholder="Enter Verifycode" autocomplete="off" required="" />
			    @if($errors->has('verifycode'))
                    <span style="color:red;display: block;">{{ $errors->first('verifycode') }}</span>
                @endif
			</div>
			<label for="password">
				 Password *
			</label>
			<div class="form-group form-group-section">
			    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Enter Password" required="" />
			 @if($errors->has('password'))
                    <span style="color:red;display: block;">{{ $errors->first('password') }}</span>
                @endif
			</div>
			<div>
				<button type="submit" class="btn submitbutton">RESET PASSWORD</button>
			</div>
		</form>
	</div>
</div>



@endsection @push('js') @endpush