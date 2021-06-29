@extends('frontend.layouts.app_plain')

@section('title', 'User Login')

@section('content')
<div class="d-flex align-items-center login__container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card p-4 shadow auth__form">
                    <div class="card-body">
                        <h4 class="text-primary">Login</h4>
                        <p class="text-muted">Your Account</p>
                        <form method="POST" action="{{ route('login') }}">
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

                            <div class="form-group auth__password__field">
                                <label for="password">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{old('password')}}" required placeholder="Password" autocomplete="current-password">
                                <i class="fas fa-eye"></i>

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
                                <div class="d-flex justify-content-between"> 
                                    @if (Route::has('password.request'))
                                        <a class="font-weight-bold pt-2" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
    
                                    <button type="submit" class="auth__btn__theme">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="text-center mt-4">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">
                            <h6 class="font-weight-bold">
                                {{ __('Create a New Account') }}
                            </h6>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        const pswrdField = document.querySelector(".auth__form .form-group input[type='password']") ,
        toggleBtn = document.querySelector(".auth__form .form-group i")

        toggleBtn.onclick = () => {
            if(pswrdField.type == "password") {
                pswrdField.type = "text";
                toggleBtn.classList.add('active');
            } else {
                pswrdField.type = "password";
                toggleBtn.classList.remove('active');
            }
        }
    </script>
@endsection