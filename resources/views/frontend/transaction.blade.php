@extends('frontend.layouts.app')
@section('title', "Transaction")

@section('content')
    @if (count($transactions))
        <div class="transaction">
            <div class="infinite-scroll">
                @foreach ($transactions as $transaction)
                    <a href="{{route('transaction.detail', $transaction->id)}}">
                        <div class="card shadow mb-2">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="mb-0 font-weight-bold text-dark">Trx Id : {{$transaction->trx_id}}</h6>
                                    @if ($transaction->type == 1)
                                        <h6 class="mb-0 font-weight-bold text-success">+{{number_format($transaction->amount, 2)}} <small>MMK</small></h6>
                                    @elseif($transaction->type == 2)
                                        <h6 class="mb-0 font-weight-bold text-danger">-{{number_format($transaction->amount, 2)}} <small>MMK</small></h6>
                                    @endif
                                </div>
                                <p class="mb-0 text-muted">
                                    @if ($transaction->type == 1)
                                        From -
                                    @elseif($transaction->type == 2)
                                        To -
                                    @endif
                                    {{$transaction->source ? $transaction->source->name : ''}}
                                </p>
                                <p class="mb-0 text-muted">
                                    {{$transaction->created_at->diffForHumans()}} -
                                    {{$transaction->created_at->toFormattedDateString()}} -
                                    {{$transaction->created_at->format('H:i:s')}}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
                {{$transactions->links()}}  
            </div>
        </div>
    @else
        <h4 class="text-center text-muted">No Transactions</h4>
    @endif
@endsection
@section('scripts')
    <script>
        $('ul.pagination').hide();
        $(function() {
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '<div>loading......</div>',
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });
        });
    </script>
@endsection