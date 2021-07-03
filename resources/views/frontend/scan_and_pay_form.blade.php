@extends('frontend.layouts.app')
@section('title', "Scan & Pay")

@section('content')
    <div class="scan__pay_form">
        <div class="card shadow">
            <div class="card-body">
                @include('frontend.layouts.flash')

                <div class="my-3">
                    <p class="mb-1">From - <span>{{$user->name}}</span></p>
                    <p class="mb-1 text-muted">{{$user->phone}}</p>
                </div>

                <hr>

                <div>
                    <form action="{{route('scan&pay.confirm')}}" method="GET" id="scan_pay" autocomplete="off">
                        <input type="hidden" name="hash_value" class="hash_value" value="">
                        <input type="hidden" name="to_phone" class="phone" value="{{$to_account->phone}}">

                        <div class="my-3">
                            <p class="mb-1">To - <span>{{$to_account->name}}</span></p>
                            <p class="mb-1 text-muted">{{$to_account->phone}}</p>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="amount">Amount (MMK)</label>
                            <input type="number" class="form-control amount" name="amount" placeholder="Enter Amount (MMK)" value="{{old('amount')}}">
                        </div>

                        <div class="form-group mb-3">   
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control description" placeholder="Message"></textarea>
                        </div>

                        <button class="btn btn-primary float-right submit-btn">Continue</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\ConfirmTransferRequest', '#scan_pay') !!}

    <script>
        $(document).ready(function(){
            $('.submit-btn').on('click', function(e) {
                e.preventDefault();
                let phone = $('.phone').val();
                let amount = $('.amount').val();
                let description = $('.description').val();

                $.ajax({
                    url : `/transfer-hash?to_phone=${phone}&amount=${amount}&description=${description}`,
                    type : 'GET',
                    success : function(res) {
                        if(res.status == 'success') {
                            $('.hash_value').val(res.data);
                            $('#scan_pay').submit();
                        }
                    }
                })
            });
        })
    </script>
@endsection