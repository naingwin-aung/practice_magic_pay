@extends('frontend.layouts.app')
@section('title', "Receive QR")

@section('content')
    <div class="receive__qr">
        <div class="card">
            <div class="card-body">
                <p class="text-center mb-0">QR Scan to Pay me</p>
                <div class="text-center">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->color(88, 66, 227)->size(200)->generate($user->phone)) !!} ">
                </div>
                <p class="mb-1 text-center font-weight-bold">
                    {{$user->name}}
                </p>
                <p class="mb-1 text-center">
                    {{$user->phone}}
                </p>
            </div>
        </div>
    </div>
@endsection