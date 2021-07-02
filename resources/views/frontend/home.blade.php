@extends('frontend.layouts.app')
@section('title', "Magic Pay")

@section('content')
    <div class="home">
        <div class="profile text-center mb-3">
            <img src="https://ui-avatars.com/api/?background=5842e3&color=fff&name={{$user->name}}&size=60" alt="">
            <h5 class="mt-2">{{$user->name}}</h5>
            <p class="text-muted">{{$user->wallet ? number_format($user->wallet->amount, 2) : '-'}} MMK</p>
        </div>

        <div class="row mb-3 shortcut__box">
            <div class="col-6">
                <div class="card shadow">
                    <div class="card-body px-2 py-3 text-center">
                        <img src="{{asset('/img/qr-code-scan.png')}}" alt="Scan" class="mr-2">
                        <span>Scan & Pay</span>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <a href="{{route('receiveQR')}}">
                    <div class="card shadow">
                        <div class="card-body px-2 py-3 text-center">
                            <img src="{{asset('/img/qr-code.png')}}" alt="Scan" class="mr-2">
                            <span>Receive QR</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="card shadow function__box">
            <div class="card-body pr-0">
                <a href="{{route('transfer')}}">
                    <div class="d-flex justify-content-between mb-3">
                        <span><img src="{{asset('img/transfer.png')}}" alt="Transfer" class="mr-3">Transfer</span>
                        <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                    </div>
                </a>
                <hr>
    
                <a href="{{route('wallet')}}">
                    <div class="d-flex justify-content-between">
                        <span><img src="{{asset('img/wallet.png')}}" alt="Transfer" class="mr-3">Wallet</span>
                        <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                    </div>
                </a>
                <hr>

                <a href="{{route('transaction')}}">
                    <div class="d-flex justify-content-between">
                        <span><img src="{{asset('img/transaction.png')}}" alt="Transfer" class="mr-3">Transaction</span>
                        <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection