@extends("layouts.bobkar")

@section("content")
    <!-- Modal -->
    <div class="modal fade" id="editCourseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCourse" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div id="result"></div>
                        <div class="form-group">
                            <label for="course_name" class="col-form-label">Course Name</label>
                            <input type="text" class="form-control" name="course_name" id="course_name">
                        </div>
                        <div class="form-group">
                            <label for="sequence_number">Course Sequence Number</label>
                            <input type="text" name="sequence_number" id="sequence_number" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="duration">Duration <small>(in months)</small></label>
                            <input type="text" name="duration" id="duration" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-form-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option selected disabled hidden>--- Select ---</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Model Ends here-->

    <div class="row justify-content-center">
        <div class="col-md-10 col-12">
            <div class="card">
                <div class="card-header text-uppercase">Manage courses</div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-block table-responsive table-striped col-12">
                            <div class="col-lg-2 col-md-3 pb-3"><a href="{{ route('program.index') }}" class="nounderline"><h4 class="text-muted"><i class="fa fa-arrow-left"></i> back</h4></a></div>
                            <b>Program name :-</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$program->program_name}}
                            <br><br>
                            <table class="table table-bordered" id="course-list">
                                <thead>
                                    <tr>
                                        <th>Sequence Number</th>
                                        <th>Course Name</th>
                                        <th>Duration<small> (in months)</small></th>
                                        <th>Status</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(count($program->course))
                                @foreach($program->course as $course)
                                    <tr>
                                        <td>{{$course->sequence_number}}</td>
                                        <td>{{$course->course_name}}</td>
                                        <td>{{$course->duration}}</td>
                                        <td class="{{$courseStatus[$course->status][1]}}">{{$courseStatus[$course->status][0]}}</td>
                                        <td><a href="{{ route('course.edit',[$program->id,$course->id]) }}" class="nounderline text-info"><i class="fa fa-edit"></i></a></td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr><td class="text-center text-muted" colspan="100%">No Courses found...</td></tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            //Ajax loading course details on modal
            $("#course-list a").click(function (event) {
                var courseStatus    =   {!! json_encode($courseStatus) !!}
                event.preventDefault();
                $.ajax({
                    url:        $(this).attr("href"),
                    method:     "GET",
                    dataType:   "JSON",
                    beforeSend: function () {
                        $(".message-box").show().html("Loading...");
                    },
                    success:    function (result) {
                        if(result.error)
                            $(".message-box").html(result["error"]);
                        else
                        {
                            $(".message-box").html("").hide();
                            $("#editCourse").attr("action",result['update-url']);
                            $("#editCourse #course_name").attr("value",result['course']['course_name']);
                            $("#editCourse #sequence_number").attr("value",result['course']['sequence_number']);
                            $("#editCourse #duration").attr("value",result['course']['duration']);
                            $("#editCourse #status").html("<option selected hidden disabled>--- Select ---</option>");
                            $.each(courseStatus, function (key, value) {
                                if(key == result['course']['status'])
                                    $("#editCourse #status").append("<option selected value='"+key+"'>"+value[0]+"</option>");
                                else
                                    $("#editCourse #status").append("<option value='"+key+"'>"+value[0]+"</option>");
                            });
                            $("#editCourseModal").modal("show");
                        }
                    }
                });
            });
            //Ends here

            //Reload page on closing modal
            $("#editCourseModal").on('hidden.bs.modal', function () {
                location.reload();
            });
            //Ends here

            //Ajax update course details
            $("#editCourse").submit(function (event) {
                event.preventDefault();
                $.ajax({
                    url:        $(this).attr("action"),
                    method:     "PUT",
                    data:       $(this).serialize(),
                    dataType:   "JSON",
                    beforeSend: function () {
                        $("#editCourse #result").html("<i class='fa fa-spinner'></i>").addClass("text-center");
                    },
                    success:    function (result) {
                        if(result.failed)
                            $("#editCourse #result").html("<div class='alert alert-warning font-weight-bold'><i class='fa fa-exclamation-triangle'></i> "+result["failed"]+"</div>");
                        else if (result.errors)
                        {
                            var errorHtml='';
                            $.each(result.errors, function (key, value) {
                                errorHtml   +=  "<div class='alert alert-danger font-weight-bold'><i class='fa fa-exclamation-triangle'></i> "+value+"</div>";
                            });
                            $("#editCourse #result").html(errorHtml);
                        }
                        else
                            $("#editCourse #result").html("<div class='alert alert-success font-weight-bold'><i class='fa fa-check-circle'></i> "+result["success"]+"</div>");
                        // $("#editCourse #result").html("");
                    }
                });
            });
        });
    </script>
@endsection