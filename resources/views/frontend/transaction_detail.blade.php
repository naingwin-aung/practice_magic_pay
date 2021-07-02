@extends('frontend.layouts.app')
@section('title', "Transaction Detail")

@section('content')
    <div class="transaction__detail mb-5">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="{{asset('img/checked.png')}}" alt="Transaction">
                </div>
                @if (session('transfer-success'))
                 <div class="alert alert-success text-center" role="alert">
                    {{session('transfer-success')}}
                  </div>
                @endif
                @if ($transaction->type == 1)
                    <h5 class="text-center text-success">+{{number_format($transaction->amount, 2)}} MMK</h5>
                @elseif($transaction->type == 2)
                    <h5 class="text-center text-danger">-{{number_format($transaction->amount, 2)}} MMK</h5>
                @endif
               <hr>
               <div class="d-flex justify-content-between">
                   <p class="mb-0 text-muted">Trx ID : </p>
                   <p class="mb-0">{{$transaction->trx_id}}</p>
               </div>
                    <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Reference No : </p>
                    <p class="mb-0">{{$transaction->ref_no}}</p>
                </div>
                    <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Type : </p>
                    <p class="mb-0 {{$transaction->type == 1 ? 'badge-pill badge-success' : 'badge-pill badge-danger'}}">{{$transaction->type == 1 ? 'Income' : 'Expense'}}</p>
                </div>
                    <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Amount : </p>
                    <p class="mb-0 {{$transaction->type == 1 ? 'text-success' : 'text-danger'}}">{{number_format($transaction->amount, 2)}} MMK</p>
                </div>
                    <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Date and Time : </p>
                    <p class="mb-0">{{$transaction->created_at->format('H:i:s')}} -{{$transaction->created_at->toFormattedDateString()}}</p>
                </div>
                    <hr>
                <div class="d-flex justify-content-between mb-0">
                    @if ($transaction->type == 1)
                        <p class="mb-0">From : </p>
                    @else
                        <p class="mb-0">To : </p>
                    @endif
                        <p class="mb-0">{{$transaction->source ? $transaction->source->name : ''}}</p>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="mb-0">Phone : </p>
                    <p class="mb-0">{{$transaction->source ? $transaction->source->phone : ''}}</p>
                </div>

                @if ($transaction->description)
                    <hr>
                    <p class="mb-0 text-muted">Description :</p>
                    <p class="mb-0">{{$transaction->description}}</p>
                @endif
            </div>
        </div>
    </div>
@endsection