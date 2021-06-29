@extends('frontend.layouts.app')
@section('title', "Wallet")

@section('content')
    <div class="wallet">
        <div class="card shadow bg-primary text-white my__card">
            <div class="card-body">
                <div class="mb-4">
                    <p class="text-uppercase">Balance</p>
                    <h4>{{$user->wallet->amount ? number_format($user->wallet->amount, 2) : '-'}} <span>MMK</span></h4>
                </div>
                <div class="mb-4">
                    <p class="text-uppercase">Account Number</p>
                    <h5>{{$user->wallet->account_number}}</h5>
                </div>
                <div>
                    <p class="text-uppercase">{{$user->name}}</p>
                </div>
            </div>
        </div>
    </div>
@endsection