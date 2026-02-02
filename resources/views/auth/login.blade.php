@extends('layouts.auth')

@section('content')
<div class="login-container">

    <div class="login-card" style="padding-top:25px;">

        <div class="login-brand" style="text-align:center; margin-bottom:6px;">
            <img
                src="{{ asset('images/ebuy_1.png') }}"
                alt="eBuy Properties"
                style="max-width:140px; width:100%;"
            >
        </div>

        <p class="login-subtitle" style="text-align:center; margin-bottom:12px;">
            {{ __('auth.login') }}
        </p>

        <a href="{{ route('google.login') }}"
           class="google-btn"
           style="padding:10px 14px; font-size:14px; margin-bottom:12px;">
            <img
                src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                alt="Google"
                style="width:18px; height:18px;"
            >
            {{ __('auth.Login with Google') }}
        </a>

        <div class="divider" style="margin:12px 0;">
            <span>{{ __('auth.or') }}</span>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="@error('email') is-invalid @enderror"
                    required
                >
                <label>{{ __('auth.Email Address') }}</label>

                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input
                    type="password"
                    name="password"
                    class="@error('password') is-invalid @enderror"
                    required
                >
                <label>{{ __('auth.Password') }}</label>

                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="login-options" style="margin-top:6px;">
                <label class="remember">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    {{ __('auth.Remember Me') }}
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        {{ __('auth.Forgot Your Password?') }}
                    </a>
                @endif
            </div>

            <button type="submit" class="login-btn" style="margin-top:10px;">
                {{ __('auth.login') }}
            </button>
        </form>

        <div class="login-options" style="justify-content:center; margin-top:14px;">
            <a href="{{ route('register') }}">
                {{ __('auth.register') }}
            </a>
        </div>

    </div>

</div>
@endsection
