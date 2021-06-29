@extends('frontend.layouts.app_plain')
@section('title', 'User Register')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="height: 100vh">
        <div class="col-md-8">
            <div class="card p-4 shadow auth__form">
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        <h4 class="text-primary">Register</h4>
                        <p class="text-muted">Create a New Account</p>
                        @csrf
                        <div class="form-group">
                            <label for="name" class="font-weight-bold">Name</label>
                            <input type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name')}}" autocomplete="name" autofocus placeholder="Name" required>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}" autocomplete="email" autofocus placeholder="Email" required>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{old('phone')}}" placeholder="Phone Number" autocomplete="phone" required>

                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{old('password')}}" placeholder="Create Password" autocomplete="current-password" required>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="Confirm Password" placeholder="Confirm Password" required>
                        </div>

                        <div class="form-group mt-4">
                            <div class="d-flex justify-content-between">
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}">
                                        <h6 class="font-weight-bold pt-2">
                                            {{ __('Already have an account?') }}
                                        </h6>
                                    </a>
                                @endif

                                <button type="submit" class="auth__btn__theme">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
