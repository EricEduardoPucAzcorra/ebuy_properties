@extends('layouts.auth')

@section('content')
<div class="login-container">

    <div class="login-card" style="padding-top:25px;">
        <div class="login-brand" style="text-align:center; margin-bottom:6px;">
            <a href="/">
                <img
                    src="{{ asset('images/ebuy_1.png') }}"
                    alt="eBuy Properties"
                    style="max-width:140px; width:100%;"
                >
            </a>
        </div>

        <p class="login-subtitle" style="text-align:center; margin-bottom:12px;">
            {{ __('auth.register') }}
        </p>

        <a href="{{ route('google.login') }}"
           class="google-btn"
           style="padding:10px 14px; font-size:14px; margin-bottom:12px;">
            <img
                src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                alt="Google"
                style="width:18px; height:18px;"
            >
            {{ __('auth.login_google_register') }}
        </a>

        <div class="divider" style="margin:12px 0;">
            <span>{{ __('auth.or') }}</span>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <input type="text" name="name"
                       value="{{ old('name') }}"
                       class="@error('name') is-invalid @enderror"
                       required>
                <label>{{ __('auth.Name') }}</label>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="email" name="email"
                       value="{{ old('email') }}"
                       class="@error('email') is-invalid @enderror"
                       required>
                <label>{{ __('auth.Email Address') }}</label>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="password" name="password"
                       class="@error('password') is-invalid @enderror"
                       required>
                <label>{{ __('auth.Password') }}</label>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="password" name="password_confirmation" required>
                <label>{{ __('auth.Confirm Password') }}</label>
            </div>

            <button type="submit" class="login-btn" style="margin-top:10px;">
                {{ __('auth.register') }}
            </button>
        </form>

        <div class="login-options" style="justify-content:center; margin-top:14px;">
            <a href="{{ route('login') }}">
                {{ __('auth.login') }}
            </a>
        </div>

    </div>

</div>
@endsection
