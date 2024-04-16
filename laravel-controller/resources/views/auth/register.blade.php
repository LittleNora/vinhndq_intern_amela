@extends('layouts.admin.auth')
@section('page-title', 'Login')
@section('content')
    <p class="login-box-msg">{{ __("Register a new membership") }}</p>

    <x-auth-session-status class="mb-4" :status="session('status')"/>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <div class="input-group mb-3">
                <input type="name" name="name" id="name" class="form-control" placeholder="{{ __("Name") }}"
                       value="{{ old('name') ?? '' }}" autofocus>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
            <span class="text-danger">@error('name') {{ $message }} @enderror</span>
        </div>
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
                <input type="password" name="password" id="password" class="form-control"
                       placeholder="{{ __("Password") }}"
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
        <div class="mb-3">
            <div class="input-group mb-3">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                       placeholder="{{ __("Confirm Password") }}"
                       value="{{ old('password_confirmation') ?? '' }}"
                >
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <span class="text-danger">@error('password_confirmation') {{ $message }} @enderror</span>
        </div>
        <div class="row">
            <!-- /.col -->
            <div class="col-8">
                <a href="{{ route('login') }}" class="text-center">{{ __('Already registered?') }}</a>
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Register') }}
                </button>
            </div>
            <!-- /.col -->
        </div>
    </form>

@endsection


{{--<x-guest-layout>--}}
{{--    <form method="POST" action="{{ route('register') }}">--}}
{{--        @csrf--}}

{{--        <!-- Name -->--}}
{{--        <div>--}}
{{--            <x-input-label for="name" :value="__('Name')" />--}}
{{--            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />--}}
{{--            <x-input-error :messages="$errors->get('name')" class="mt-2" />--}}
{{--        </div>--}}

{{--        <!-- Email Address -->--}}
{{--        <div class="mt-4">--}}
{{--            <x-input-label for="email" :value="__('Email')" />--}}
{{--            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />--}}
{{--            <x-input-error :messages="$errors->get('email')" class="mt-2" />--}}
{{--        </div>--}}

{{--        <!-- Password -->--}}
{{--        <div class="mt-4">--}}
{{--            <x-input-label for="password" :value="__('Password')" />--}}

{{--            <x-text-input id="password" class="block mt-1 w-full"--}}
{{--                            type="password"--}}
{{--                            name="password"--}}
{{--                            required autocomplete="new-password" />--}}

{{--            <x-input-error :messages="$errors->get('password')" class="mt-2" />--}}
{{--        </div>--}}

{{--        <!-- Confirm Password -->--}}
{{--        <div class="mt-4">--}}
{{--            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />--}}

{{--            <x-text-input id="password_confirmation" class="block mt-1 w-full"--}}
{{--                            type="password"--}}
{{--                            name="password_confirmation" required autocomplete="new-password" />--}}

{{--            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />--}}
{{--        </div>--}}

{{--        <div class="flex items-center justify-end mt-4">--}}
{{--            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">--}}
{{--                {{ __('Already registered?') }}--}}
{{--            </a>--}}

{{--            <x-primary-button class="ms-4">--}}
{{--                {{ __('Register') }}--}}
{{--            </x-primary-button>--}}
{{--        </div>--}}
{{--    </form>--}}
{{--</x-guest-layout>--}}
