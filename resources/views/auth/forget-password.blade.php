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

		<form  method="POST" action="{{route('forgotPassword')}}">
            @csrf
            @include(App\Models\General::first()->theme.'.alerts')
            
			<label for="email">
				 Email*
			</label>
			<div class="form-group form-group-section">
			    <input type="email" name="email" value="{{old('email')}}" class="form-control control-section" placeholder="Enter Your Email" required="">
			    @if($errors->has('email'))
                    <span style="color:red;display: block;">{{ $errors->first('email') }}</span>
                @endif
			</div>
			<div>
				<button type="submit" class="btn submitbutton">RESET PASSWORD</button>
			</div>
		</form>
	</div>
</div>

@endsection @push('js') @endpush