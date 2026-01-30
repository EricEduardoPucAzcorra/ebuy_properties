@extends('layouts.auth')

@section('content')
<div class="login-container">

    <div class="login-card">

        {{-- BRAND --}}
        <div class="login-brand">
            <h1>Lalux</h1>
            <span>Development</span>
        </div>

        {{-- SUBTITLE --}}
        <p class="login-subtitle">
            {{ __('auth.Reset Password') }}
        </p>

        {{-- STATUS --}}
        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            {{-- EMAIL --}}
            <div class="form-group">
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="@error('email') is-invalid @enderror"
                       required
                       autofocus>
                <label>{{ __('auth.Email Address') }}</label>

                @error('email')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror
            </div>


            <button type="submit" class="login-btn">
                {{ __('auth.Send Password Reset Link') }}
            </button>
        </form>

        <br>

        <div class="login-options center">
            <a href="{{ route('login') }}" class="login-link">
                 {{ __('auth.login') }}
            </a>
        </div>

    </div>

</div>
@endsection
