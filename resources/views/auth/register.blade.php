@extends(general()->theme.'.layouts.app') @section('title')
<title>Create Account | {{general()->title}}</title>
@endsection @section('SEO')
<meta name="description" content="Create your {{general()->title}} account to track orders and check out faster." />
<meta property="og:title" content="Create Account | {{general()->title}}" />
<meta property="og:image" content="{{asset(general()->logo())}}" />
<meta property="og:url" content="{{route('register')}}" />
@endsection @push('css')
@include('auth.includes.authStyle')
@endpush

@section('contents')

<div class="au-page">
    <div class="container">
        <div class="au-shell">
            @include('auth.includes.authBrand', ['heading' => 'Join '.general()->title, 'tagline' => 'Create a free account in less than a minute.'])

            <div class="au-form">
                <h1>Create account</h1>
                <p class="au-sub">Already have an account? <a href="{{route('login')}}">Log in</a></p>

                @include(App\Models\General::first()->theme.'.alerts')

                <form action="{{route('register')}}" method="post" class="au-submit-guard">
                    @csrf
                    <div class="au-field">
                        <label for="name">Username</label>
                        <div class="au-input {{ $errors->has('name') ? 'has-error' : '' }}">
                            <i class="fa fa-user"></i>
                            <input type="text" id="name" name="name" value="{{old('name')}}" placeholder="Choose a username" autocomplete="username" required autofocus>
                        </div>
                        @if($errors->has('name'))
                        <div class="au-error"><i class="fa fa-circle-exclamation"></i> {{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="au-field">
                        <label for="email">Email address</label>
                        <div class="au-input {{ $errors->has('email') ? 'has-error' : '' }}">
                            <i class="fa fa-envelope"></i>
                            <input type="email" id="email" name="email" value="{{old('email')}}" placeholder="you@example.com" autocomplete="email" required>
                        </div>
                        @if($errors->has('email'))
                        <div class="au-error"><i class="fa fa-circle-exclamation"></i> {{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="au-field">
                        <label for="password">Password</label>
                        <div class="au-input {{ $errors->has('password') ? 'has-error' : '' }}">
                            <i class="fa fa-lock"></i>
                            <input type="password" id="password" name="password" placeholder="At least 5 characters" autocomplete="new-password" minlength="5" required>
                            <button type="button" class="au-eye" data-target="password" aria-label="Show password"><i class="fa fa-eye"></i></button>
                        </div>
                        @if($errors->has('password'))
                        <div class="au-error"><i class="fa fa-circle-exclamation"></i> {{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <p class="au-note">
                        Your personal data will be used to support your experience on this website, to manage your account, and for other purposes described in our
                        <a href="{{route('pageView','privacy-policy')}}" class="au-link">Privacy Policy</a>.
                    </p>

                    <button type="submit" class="au-submit">Create account <i class="fa fa-arrow-right"></i></button>
                </form>

                <p class="au-switch">Already a member? <a href="{{route('login')}}" class="au-link">Log in</a></p>
            </div>
        </div>
    </div>
</div>

@endsection @push('js')
@include('auth.includes.authScript')
@endpush
