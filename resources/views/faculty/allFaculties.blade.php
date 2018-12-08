@extends('layouts.bobkar')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase">
            Manage faculties
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-12 pt-1 table-block table-responsive">
                    <table id="facultyList" class="table table-bordered table-striped">
                        <thead>
                        <tr class="text-center">
                            <th>Faculty ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Branch</th>
                            <th>Status</th>
                            <th>View</th>
                            <th>Edit</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#facultyList').DataTable({
                "processing": true,
                "serverSide": true,
                type: "GET",
                data: {
                    button_action: "for_admin"
                },
                "ajax": window.location,
                "columns": [
                    {"data": "faculty_code"},
                    {"data": "name"},
                    {"data": "email"},
                    {"data": "mobile"},
                    {"data": "branch"},
                    {
                        "data": "status",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                data = '<span class="' + data[1] + '">' + data[0] + '</span>';
                            }
                            return data;
                        },
                    },
                    {
                        "data": "view",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                data = '<a href="' + data + '" class="text-info"><i class="fa fa-info-circle"></i></a>';
                            }
                            return data;
                        },
                    },
                    {
                        "data": "edit",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                data = '<a href="' + data + '" class="text-success"><i class="fa fa-edit"></i></a>';
                            }
                            return data;
                        },
                    },
                ],
                "columnDefs": [
                    {"className": "text-center", "targets": -1, orderable: false},
                    {"className": "text-center", "targets": 5, orderable: false},
                    {"className": "text-center", "targets": 6, orderable: false},
                    {"targets": [3, 4, 2], orderable: false}
                ]
            });
        } );
    </script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
@endsection