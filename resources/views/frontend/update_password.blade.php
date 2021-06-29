@extends('frontend.layouts.app')
@section('title', 'Update Password')

@section('content')
    <div class="update__password mb-5">
        <div class="card p-2 shadow">
            <div class="card-body">

                <div class="text-center">
                    <img src="{{asset('img/update_password.png')}}" alt="Password">
                </div>

                @include('frontend.layouts.flash')

                <form action="{{route('update-password.store')}}" method="POST" id="update-password">
                    @csrf
                    <div class="form-group">
                        <label for="">Current Password</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" value="{{old('current_password')}}">

                        @error('current_password')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">New Password</label>
                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">

                        @error('new_password')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password-confirm">Confirm Password</label>
                        <input id="password-confirm" type="password" name="password_confirmation" class="form-control">
                    </div>


                    <button class="btn btn-primary ml-2 float-right" type="submit">Confirm</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\StoreUpdatePassword', '#update-password') !!}
@endsection