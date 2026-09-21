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

<div class="lostregis">
	<div class="lostpassheader">
		<h3>My Account</h3>
		<p>Sign-Up</p>
	</div>
	<div class="container">
	    <div class="row">
	        <div class="col-md-3"></div>
	        <div class="col-md-6">
	            <div class="login-part">
            		<h4>REGISTER</h4>
            		@include(App\Models\General::first()->theme.'.alerts')
            		<form action="{{route('register')}}" method="post">
            		    @csrf
            			<label for="name">
            				Username *
            			</label>
            			<div class="form-group form-group-section">
            			    <input type="name" name="name" value="{{old('name')}}" class="form-control control-section" placeholder="" required="">
            			    @if($errors->has('name'))
                                <span style="color:red;display: block;">{{ $errors->first('name') }}</span>
                            @endif
            			</div>
            
            			<label for="email">
            				Email address *
            			</label>
            			<div class="form-group form-group-section">
            			    <input type="email" name="email" value="{{old('email')}}" class="form-control control-section" placeholder="" required="">
            			    @if($errors->has('email'))
                                <span style="color:red;display: block;">{{ $errors->first('email') }}</span>
                            @endif
            			</div>
            
            			<label for="password">
            				Password *
            			</label>
            			<div class="form-group form-group-section">
            			    <input type="password" name="password" value="" class="form-control control-section" placeholder="" required="">
            			    @if($errors->has('password'))
                                <span style="color:red;display: block;">{{ $errors->first('password') }}</span>
                            @endif
            			</div>
            
            			<p>
            				Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our
            			</p>
            			<a href="#">Privacy Policy</a>
            			<div>
            				<button type="submit" class="btn submitbutton">REGISTER</button>
            			</div>
            		</form>
            		
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{route('login')}}">Alrady Have An Account? <span>Log-In</span></a>
                        </div>
                    </div>
            		
        		</div>
	        </div>
	        <div class="col-md-3"></div>
	    </div>

	</div>
</div>

@endsection @push('js') @endpush
