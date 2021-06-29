@extends('frontend.layouts.app_plain')

@section('title', 'Admin Login')

@section('content')
<div class="d-flex align-items-center login__container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card p-4 shadow auth__form">
                    <div class="card-body">
                        <h4 class="text-primary">Login</h4>
                        <p class="text-muted">Your Admin Account</p>
                        <form method="POST" action="{{ route('admin.login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}" required autocomplete="email" autofocus placeholder="Email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{old('password')}}" required placeholder="Password" autocomplete="current-password">

                                @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
    
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
    
                            <div class="form-group">
                                <button type="submit" class="auth__btn__theme float-right">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
