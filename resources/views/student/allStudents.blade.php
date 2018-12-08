@extends('layouts.bobkar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-header text-uppercase">Manage students</div>
                <div class="card-body">
                    <div class="row justify-content-end pr-3 pl-1 pb-0">
                        <a class="student_status nounderline p-2" href="{{route("student.index")}}">All</a>
                        @foreach($student_status as $status)
                        <a class="student_status nounderline p-2" href="{{route("student.index")}}?status={{$status[0]}}">{{$status[0]}}</a>
                        @endforeach
                    </div>
                    <hr class="themed-margin">
                    <div class="col-12 pt-1 table-block" id="student-search-results">
                        <table class="table-striped table table-bordered">
                            <thead>
                            <tr class="text-center">
                                <th>Student No</th>
                                <th>Name</th>
                                <th>Current Course</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Pay Fee</th>
                                <th>More</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
        $(document).ready(function () {
            var table   =   $("#student-search-results table");
            getStudents($('a[href = "'+window.location+'"]').attr("href"), table);
            $('a[href = "'+window.location+'"].student_status').addClass('active');

            $(".student_status").click(function (event) {
                event.preventDefault();
                history.pushState(null, '', $(this).attr("href"));
                $(".student_status").removeClass("active");
                $(this).addClass("active");
                if ( ! $.fn.DataTable.isDataTable(table) ) {
                    getStudents($(this).attr("href"), table);
                }
                else
                {
                    table.DataTable().destroy();
                    table.find("tr:gt(0)").remove();
                    getStudents($(this).attr("href"), table);
                }

            });
        });

        function getStudents(url,table) {
            $(table).DataTable({
                "processing": true,
                "serverSide": true,
                type: "GET",
                data: {
                    button_action: "for_admin"
                },
                "ajax": url,
                "columns": [
                    {"data": "student_no"},
                    {"data": "name"},
                    {"data": "course"},
                    {"data": "mobile"},
                    {"data": "email"},
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
                        "data": "fee_pay",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                data = '<a href="' + data + '" class="table-button table-button-info">Fee Pay</a>';
                            }
                            return data;
                        },
                    },
                    {
                        "data": "view_student",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                data = "<a href='" + data + "' class='text-success'><i class='fa fa-plus-circle'></i></a>"
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
        }
</script>
@endsection