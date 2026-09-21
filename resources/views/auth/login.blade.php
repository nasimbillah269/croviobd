@extends(general()->theme.'.layouts.app') @section('title')
<title>{{general()->title}} | {{general()->subtitle}}</title>
@endsection @section('SEO')
<meta name="description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta property="og:title" content="{{general()->meta_title}}" />
<meta property="og:description" content="{!!general()->meta_description!!}" />
<meta property="og:image" content="{!!general()->meta_description!!}" />
<meta property="og:url" content="{{route('login')}}" />
@endsection @push('css')
@endpush 

@section('contents')

<div class="lostregis">
	<div class="lostpassheader">
		<h3>My Account</h3>
		<p>Log-In</p>
	</div>
	<div class="container">
	    <div class="row">
	        <div class="col-md-3"></div>
	        <div class="col-md-6">
	            <div class="login-part">
            		<h4>LOGIN</h4>
            		<form class="form-horizontal form-simple" action="{{route('login')}}" method="post">
                        @csrf
                        <div>
                            @if($errors->has('username'))
                                <span style="color:red;display: block;">{{ $errors->first('username') }}</span>
                            @endif
                            @if($errors->has('password'))
                                <span style="color:red;display: block;">{{ $errors->first('password') }}</span>
                            @endif
                            @if (session('loginfail'))
                            <span style="color:red;display: block;">{{ session('loginfail') }}</span>
                            @endif
                            @if (session('loginfailP'))
                            <span style="color:red;display: block;">{{ session('loginfailP') }}</span>
                            @endif
                        </div>
                        
            			<label for="email">
            				Username or email address *
            			</label>
            			<div class="form-group form-group-section">
            			    <input type="text" value="{{old('username')}}" name="username" class="form-control control-section" placeholder="Email Address" required="" />
            			</div>
            
            			<label for="password">
            				Password *
            			</label>
            			<div class="form-group form-group-section">
            			    <input type="password" class="form-control control-section" id="password" name="password" placeholder="Enter Password" required="" />
            			</div>
            			
                        {{--
            			<div class="media">
            				<input type="checkbox">
            				<div class="media-body">
            					<p>Remember Me</p>
            				</div>
            			</div>
            			--}}
            			
            			<div>
            				<button type="submit" class="btn submitbutton">LOG IN</button>
            			</div>
            		</form>
            		
                    <div class="row">
                        <div class="col-6">
                            <a href="{{route('forgotPassword')}}">Lost your password?</a>
                        </div>
                        <div class="col-6" style="text-align: end;">
                            <a href="{{route('register')}}">Not Any Account? <span>Sign-Up</span></a>
                        </div>
                    </div>
            		
        		</div>
	        </div>
	        <div class="col-md-3"></div>
	    </div>

	</div>
</div>

@endsection @push('js') @endpush