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
                        <input type="hidden" name="hash_value" class="hash_value" value="">
                        <div class="form-group mb-3">
                            <label for="phone">To <span class="to_account_info text-success"></span><span class="to_account_fail text-danger"></span></label>
                            <div class="input-group">
                                <input type="number" class="form-control to_phone" name="to_phone" value="{{old('to_phone')}}" placeholder="Enter Transfer Phone Number">
                                <div class="input-group-append">
                                    <button class="btn btn-primary verify-btn" type="button"><i class="fas fa-check-circle"></i></button>
                                </div>
                            </div>
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

            $('.submit-btn').on('click', function(e) {
                e.preventDefault();
                let to_phone = $('.to_phone').val();
                let amount = $('.amount').val();
                let description = $('.description').val();

                $.ajax({
                    url : `/transfer-hash?to_phone${to_phone}&amount=${amount}&description=${description}`,
                    type : 'GET',
                    success : function(res) {
                        if(res.status == 'success') {
                            $('.hash_value').val(res.data);
                            $('#transfer').submit();
                        }
                    }
                })
            });
        })
    </script>
@endsection