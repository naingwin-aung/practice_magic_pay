@extends('backend.layouts.app')
@section('title', 'Wallets')
@section('wallet-active', 'mm-active')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-wallet icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>Wallets</div>
            </div>
        </div>
    </div>
    <div class="content__width">
        <div class="content py-3 table__width">
            <div class="card">
                <div class="card-body">
                    <table class="table datatable table-bordered">
                        <thead>
                            <tr class="bg-light">
                                <th class="no-sort">Account Person</th>
                                <th>Account Number</th>
                                <th>Amount MMK</th>
                                <th>Created_at</th>
                                <th>Updated_at</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/admin/wallet/datatable/ssd",
                columns : [
                    {
                        data: "account_person",
                        name: "account_person",
                        searchable: false
                    },
                    {
                        data: "account_number",
                        name: "account_number",
                    },
                    {
                        data: "amount",
                        name: "amount",
                        searchable: false
                    },
                    {
                        data: "created_at",
                        name: "created_at",
                        searchable: false
                    },
                    {
                        data: "updated_at",
                        name: "updated_at",
                        searchable: false
                    },
                ],
                order : [
                    [ 4, "desc" ]
                ],
                columnDefs: [ 
                    {
                        targets: 'no-sort',
                        sortable: false,
                        searchable: false
                    } 
                ]
            });
        } );
    </script>
@endsection