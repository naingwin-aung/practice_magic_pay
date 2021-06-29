@extends('backend.layouts.app')
@section('title', 'Users')
@section('user-active', 'mm-active')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-user icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>Users</div>
            </div>
        </div>
    </div>
    <div class="pt-3">
        <a href="{{route('admin.user.create')}}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Create Users</a>
    </div>
    <div class="content__width">
        <div class="content py-3 table__width">
            <div class="card">
                <div class="card-body">
                    <table class="table datatable table-bordered">
                        <thead>
                            <tr class="bg-light">
                                <th>Name</th>
                                <th class="no-sort">Email</th>
                                <th class="no-sort">Phone</th>
                                <th class="no-sort">IP</th>
                                <th class="no-sort">User Agent</th>
                                <th>Login_at</th>
                                <th>Created_at</th>
                                <th class="no-sort">Action</th>
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
                ajax: "/admin/user/datatable/ssd",
                columns : [
                    {
                        data: "name",
                        name: "name",
                    },
                    {
                        data: "email",
                        name: "email",
                    },
                    {
                        data: "phone",
                        name: "phone"
                    },
                    {
                        data: "ip",
                        name: "ip",
                    },
                    {
                        data: "user_agent",
                        name: "user_agent",
                    },
                    {
                        data: "login_at",
                        name: "login_at",
                    },
                    {
                        data: "created_at",
                        name: "created_at",
                    },
                    {
                        data: "action",
                        name: "action",
                    },
                ],
                order : [
                    [ 6, "desc" ]
                ],
                columnDefs: [ 
                    {
                        targets: 'no-sort',
                        sortable: false,
                        searchable: false
                    } 
                ]
            });

            $(document).on('click', '.delete', function(e) {
                e.preventDefault();
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure, you want to delete?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : '/admin/user/' + id,
                            type : 'DELETE',
                            success: function() {
                                table.ajax.reload();
                                Toast.fire({
                                    icon: 'success',
                                    title: "Successfully Deleted"
                                })
                            }
                        });
                    }
                })
            })
        } );
    </script>
@endsection