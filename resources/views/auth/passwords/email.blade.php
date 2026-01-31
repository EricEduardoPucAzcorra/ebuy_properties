@extends('layouts.auth')

@section('content')
<div class="login-container">

    <div class="login-card">

        <div class="login-brand" style="text-align:center; margin-bottom:10px;">
            <img
                src="{{ asset('images/ebuy_1.png') }}"
                alt="eBuy Properties"
                style="max-width:180px; width:100%;"
            >
        </div>

        <p class="login-subtitle" style="text-align:center;">
            {{ __('auth.Reset Password') }}
        </p>

        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="@error('email') is-invalid @enderror"
                    required
                    autofocus
                    autocomplete="email"
                >
                <label>{{ __('auth.Email Address') }}</label>

                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="login-btn">
                {{ __('auth.Send Password Reset Link') }}
            </button>
        </form>

        <div class="login-options" style="justify-content:center; margin-top:20px;">
            <a href="{{ route('login') }}">
                {{ __('auth.login') }}
            </a>
        </div>

    </div>

</div>
@endsection
