@extends('backend.layouts.app')
@section('title', 'Add Amount')
@section('wallet-active', 'mm-active')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-wallet icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>Add Amount</div>
            </div>
        </div>
    </div>

    <div class="content__width">
        <div class="content py-3 table__width">
            <div class="card">
                <div class="card-body">
                    @include('backend.layouts.flash')
                    <form action="{{route('admin.wallet.add.store')}}" method="POST" id="store">
                        @csrf
                        <div class="form-group">
                            <label for="">User</label>
                            <select name="user_id" id="" class="form-control user_id">
                                <option value="">--- Please Choose ----</option>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}} ({{$user->phone}})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="">Amount</label>
                            <input type="number" name="amount" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Description</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button class="btn btn-secondary mr-2 back-btn">Cancel</button>
                            <button type="submit" class="btn btn-primary">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\AddAmountRequest', '#store') !!}
<script>
    $(document).ready(function() {
        $('.user_id').select2({
            theme: 'bootstrap4',
            placeholder: "--- Please Choose ---",
            allowClear: true
        });
    });
</script>
@endsection