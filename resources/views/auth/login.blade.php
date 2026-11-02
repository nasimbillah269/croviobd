@extends(general()->theme.'.layouts.app') @section('title')
<title>Login | {{general()->title}}</title>
@endsection @section('SEO')
<meta name="description" content="Log in to your {{general()->title}} account to track orders and check out faster." />
<meta property="og:title" content="Login | {{general()->title}}" />
<meta property="og:image" content="{{asset(general()->logo())}}" />
<meta property="og:url" content="{{route('login')}}" />
@endsection @push('css')
@include('auth.includes.authStyle')
@endpush

@section('contents')

<div class="au-page">
    <div class="container">
        <div class="au-shell">
            @include('auth.includes.authBrand', ['heading' => 'Welcome back!', 'tagline' => 'Log in to continue shopping with '.general()->title.'.'])

            <div class="au-form">
                <h1>Log in</h1>
                <p class="au-sub">New here? <a href="{{route('register')}}">Create an account</a></p>

                @if($errors->has('username') || $errors->has('password') || session('loginfail') || session('loginfailP'))
                <div class="au-alert" role="alert">
                    <i class="fa fa-circle-exclamation"></i>
                    <div>
                        @foreach(array_filter([$errors->first('username'), $errors->first('password'), session('loginfail'), session('loginfailP')]) as $message)
                        <div>{{ $message }}</div>
                        @endforeach
                    </div>
                </div>
                @endif

                <form action="{{route('login')}}" method="post" class="au-submit-guard">
                    @csrf
                    <div class="au-field">
                        <label for="username">Email or username</label>
                        <div class="au-input {{ $errors->has('username') || session('loginfail') ? 'has-error' : '' }}">
                            <i class="fa fa-envelope"></i>
                            <input type="text" id="username" name="username" value="{{old('username')}}" placeholder="you@example.com" autocomplete="username" required autofocus>
                        </div>
                    </div>

                    <div class="au-field">
                        <div class="au-label-row">
                            <label for="password">Password</label>
                            <a href="{{route('forgotPassword')}}" class="au-link">Forgot password?</a>
                        </div>
                        <div class="au-input {{ $errors->has('password') || session('loginfailP') ? 'has-error' : '' }}">
                            <i class="fa fa-lock"></i>
                            <input type="password" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
                            <button type="button" class="au-eye" data-target="password" aria-label="Show password"><i class="fa fa-eye"></i></button>
                        </div>
                    </div>

                    <button type="submit" class="au-submit">Log in <i class="fa fa-arrow-right"></i></button>
                </form>

                <p class="au-switch">Don't have an account? <a href="{{route('register')}}" class="au-link">Sign up</a></p>
            </div>
        </div>
    </div>
</div>

@endsection @push('js')
@include('auth.includes.authScript')
@endpush
