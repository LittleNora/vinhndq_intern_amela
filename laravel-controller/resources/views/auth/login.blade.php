@extends('layouts.admin.auth')
@section('page-title', 'Login')
@section('content')
    <p class="login-box-msg">{{ __("Sign in to start your session") }}</p>

    <x-auth-session-status class="mb-4" :status="session('status')"/>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <div class="input-group mb-3">
                <input type="email" name="email" id="email" class="form-control" placeholder="{{ __("Email") }}"
                       value="{{ old('email') ?? '' }}" autofocus>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <span class="text-danger">@error('email') {{ $message }} @enderror</span>
        </div>
        <div class="mb-3">
            <div class="input-group mb-3">
                <input type="password" name="password" id="password" class="form-control" placeholder="{{ __("Password") }}"
                       value="{{ old('password') ?? '' }}"
                >
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <span class="text-danger">@error('password') {{ $message }} @enderror</span>
        </div>
        <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" id="remember_me" name="remember">
                    <label for="remember_me">
                        {{ __('Remember me') }}
                    </label>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Log in') }}
                </button>
            </div>
            <!-- /.col -->
        </div>
    </form>

    @if (Route::has('password.request'))
        <p class="mb-1">
            <a href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
        </p>
    @endif
    <p class="mb-0">
        <a href="{{ route('register') }}" class="text-center">{{ __("Register a new membership") }}</a>
    </p>
@endsection
