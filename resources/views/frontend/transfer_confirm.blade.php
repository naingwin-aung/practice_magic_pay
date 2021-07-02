@extends('frontend.layouts.app')
@section('title', "Transfer Confirmation")

@section('content')
    <div class="transfer">
        <div class="card shadow">
            <div class="card-body">

                @include('frontend.layouts.flash')

                <h5 class="text-center">Check Your Transfer</h5>
                <hr>
                <div class="mt-4 mb-3">
                    <p class="mb-1">From - <span class="text-uppercase">{{$from_account->name}}</span></p>
                    <p class="mb-1 text-muted">{{$from_account->phone}}</p>
                </div>

                <div>
                    <form action="{{route('transfer.complete')}}" method="POST" id="confirm-form">
                        @csrf
                        <input type="hidden" name='hash_value' value="{{$hash_value}}">
                        <input type="hidden" name="to_phone" value="{{$to_account->phone}}">
                        <input type="hidden" name="amount" value="{{$amount}}">
                        <input type="hidden" name="description" value="{{$description}}">
                        <div class="form-group mb-3">
                            <label for="" class="mb-0">To</label>
                            <p class="mb-1 text-muted">{{$to_account->name}}</p>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="mb-0">Phone Number</label>
                            <p class="mb-1 text-muted">{{$to_account->phone}}</p>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="mb-0">Amount (MMK)</label>
                            <p class="mb-1 text-muted">{{number_format($amount, 2)}}</p>
                        </div>

                        @if ($description)
                            <div class="form-group mb-3">   
                                <label for="" class="mb-0">Description</label>
                                <p class="mb-1 text-muted">{{$description}}</p>
                            </div>
                        @endif

                        <button class="btn btn-primary float-right confirm-btn">Continue</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.confirm-btn').on('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Please fill your password',
                    icon: 'info',
                    html:'<input type="password" class="form-control password text-center">',
                    showCloseButton: true,
                    showCancelButton: true,
                    reverseButtons : true,
                    confirmButtonText:
                        'Confirm',
                    cancelButtonText:
                        'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let password = $('.password').val();
                        $.ajax({
                            url : '/password-check?password=' + password,
                            type : 'GET',
                            success : function(res) {
                                if(res.status == 'success') {
                                    $('#confirm-form').submit();
                                }
                                if(res.status == 'fail') {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: res.message,
                                    })
                                }
                            }
                        })
                    }
                })
            })
        })
    </script>
@endsection