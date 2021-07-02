<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!--Fontawesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!---Date range picker----->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    @yield('extra_css')
</head>
<body>
    <div id="app">
        <!--Header-->
        <div class="header__menu">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-2 text-right">
                            @if (!request()->is('/'))
                                <a href="#" class="back-btn"><i class="fas fa-angle-left"></i></a>
                            @endif
                        </div>
                        <div class="col-8 text-center mt-1">
                            <a href="#">
                                <h3>@yield('title')</h3>
                            </a>
                        </div>
                        <div class="col-2 bell__icon">
                            <a href="">
                                <i class="fas fa-bell"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Main Content-->
        <div class="content">
            <div class="row justify-content-center mx-0">
                <div class="col-md-8">
                    @yield('content')
                </div>
            </div>
        </div>

        <!---Scan Circle--->
        <a href="" class="scan__tab">
            <div class="inside">
                <i class="fas fa-qrcode"></i>
            </div>
        </a>

        <!--Bottom Menu-->
        <div class="bottom__menu">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row text-center">
                        <div class="col-3">
                           <a href="{{route('home')}}">
                            <i class="fas fa-home"></i>
                            <p>Home</p>
                           </a>
                        </div>
                        <div class="col-3">
                            <a href="{{route('wallet')}}">
                                <i class="fas fa-wallet"></i>
                                <p>Wallet</p>
                            </a>
                        </div>
                        <div class="col-3">
                            <a href="{{route('transaction')}}">
                                <i class="fas fa-exchange-alt"></i>
                                <p>Transaction</p>
                            </a>
                        </div>
                        <div class="col-3">
                            <a href="{{route('profile')}}">
                                <i class="fas fa-user"></i>
                                <p>Profile</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="{{asset('js/app.js')}}"></script>
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{asset('frontend/js/jscroll.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@yield('scripts')
<script>
    $(document).ready(function() {
        $('.back-btn').on('click', function() {
            window.history.go(-1);
            return false;
        })

        @if(session('update-password'))
        Toast.fire({
            icon: 'success',
            title: "{{session('update-password')}}"
        })
        @endif
    })
</script>
</body>
</html>
