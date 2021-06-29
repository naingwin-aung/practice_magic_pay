@extends('frontend.layouts.app')
@section('title', "Transfer")

@section('content')
    <div class="transfer">
        <div class="card shadow">
            <div class="card-body">
                @include('frontend.layouts.flash')

                <div class="my-3">
                    <p class="mb-1">From - <span class="text-uppercase">{{$user->name}}</span></p>
                    <p class="mb-1 text-muted">{{$user->phone}}</p>
                </div>

                <hr>

                <div>
                    <form action="{{route('transfer.confirm')}}" method="GET" id="transfer" autocomplete="off">
                        <div class="form-group mb-3">
                            <label for="phone">To <span class="to_account_info text-success"></span><span class="to_account_fail text-danger"></span></label>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control to_phone" name="to_phone" value="{{old('to_phone')}}" placeholder="Enter Transfer Phone Number">
                                <div class="input-group-append">
                                    <button class="btn btn-primary verify-btn" type="button"><i class="fas fa-check-circle"></i></button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="amount">Amount (MMK)</label>
                            <input type="number" class="form-control" name="amount" placeholder="Enter Amount (MMK)" value="{{old('amount')}}">
                        </div>

                        <div class="form-group mb-3">   
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control" placeholder="Message"></textarea>
                        </div>

                        <button class="btn btn-primary float-right">Continue</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\ConfirmTransferRequest', '#transfer') !!}

    <script>
        $(document).ready(function(){
            $('.verify-btn').on('click', function() {
                let phone = $('.to_phone').val();
                $.ajax({
                    url : '/to-account-verfiy?phone=' + phone,
                    type : 'GET',
                    success : function(res) {
                        if(res.status == 'success') {
                            $('.to_account_fail').text('')
                            $('.to_account_info').text('('+res.data.name+')')
                        }

                        if(res.status == 'fail') {
                            $('.to_account_info').text('')
                            $('.to_account_fail').text(''+res.message+'')
                            $('.to_phone').addClass('is-invalid')
                        }
                    }
                })
            }); 
        })
    </script>
@endsection