@extends('layouts.app-center')

{{-- @section('bodystyle', 'background-image:url("'.config('global.site_banner').'"); background-size:cover; background-position:center;') --}}
{{-- @section('bodystyle', 'background-image:url("/images/banners/23.png"); background-size:cover; background-position:center;') --}}


@section('content')
<div class="row flex-column flex-lg-row vh-100 m-0">
    <div class="col-lg-8 banner-cover px-0 px-lg-2 flex-grow-1" style="background-image: url('{{ config('global.site_banner_login') }}');">
        <img src="/images/wave-divider.png" class="w-100 d-lg-none position-absolute" style="bottom: 0" alt="">
    </div>
    <div class="col-lg-4 bg-white">
        <div class="p-5 m-lg-5 text-center">
            <img src="/images/logo-tecc.png" class="w-40" alt="">
            <h2 class="text-center my-5 text-bold">{{ config('global.site_login_greeting')[rand(0, count(config('global.site_login_greeting'))-1)] }}</h2>
            <form method="POST" action="{{ route('login') }}" class="p-3">
                @csrf

                <div class="form-group text-left">
                    <label for="email" class="text-lg-right">{{ __('E-Mail') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group text-left">
                    <label for="password" class="text-lg-right">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group text-left">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>

                <div class="form-group pt-3 mb-0">
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Login') }}
                    </button>

                    @if (Route::has('password.request'))
                        <a class="btn btn-link d-none" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
