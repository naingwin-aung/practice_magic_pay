@extends('frontend.layouts.app')
@section('title', 'Profile')

@section('content')
    <div class="account">
        <div class="profile text-center mb-3">
            <img src="https://ui-avatars.com/api/?background=5842e3&color=fff&name={{auth()->user()->name}}&size=60" alt="">
        </div>
        <div class="card shadow mb-3">
            <div class="card-body pr-0">
                <div class="d-flex justify-content-between mb-3">
                    <span>Username</span>
                    <span class="mr-3">{{$user->name}}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span>Phone</span>
                    <span class="mr-3">{{$user->phone}}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Email</span>
                    <span class="mr-3">{{$user->email}}</span>
                </div>
            </div>
        </div>
    
        <div class="card shadow">
            <div class="card-body pr-0">
                <a href="{{route('update-password')}}">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Update Password</span>
                        <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                    </div>
                </a>
                <hr>
    
                <a href="{{ route('logout') }}" class="logout">
                    <div class="d-flex justify-content-between">
                        <span>Logout</span>
                        
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
    
                        <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.logout').on('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: " You want to Logout!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#5842E3',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Logout',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $('#logout-form').submit();
                    }
                })
            })
        })
    </script>
@endsection