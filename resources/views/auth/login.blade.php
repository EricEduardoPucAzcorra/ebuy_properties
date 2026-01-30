@extends('layouts.auth')

@section('content')
<div class="login-container">

    <div class="login-card">

        <div class="login-brand">
            <h1>Lalux</h1>
            <span>Development</span>
        </div>

        <p class="login-subtitle">
            {{ __('auth.login') }}
        </p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="@error('email') is-invalid @enderror"
                       required>
                <label>{{ __('auth.Email Address') }}</label>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <input type="password"
                       name="password"
                       class="@error('password') is-invalid @enderror"
                       required>
                <label>{{ __('auth.Password') }}</label>

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="login-options">
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

            <button type="submit" class="login-btn">
                {{ __('auth.login') }}
            </button>
        </form>

    </div>

</div>
@endsection
