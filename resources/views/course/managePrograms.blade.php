@extends("layouts.bobkar")
@section("content")
    <!-- Modal -->
    <div class="modal fade" id="editProgramModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="result"></div>
                    <form id="editProgram" method="post" action="">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group">
                            <label for="program_name" class="col-form-label">Program Name</label>
                            <input type="text" class="form-control" name="program_name" id="program_name">
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
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-uppercase">manage programs</div>
                <div class="card-body">
                    @if(count($programs))
                    <div class="row">
                        <div class="col-12 text-center font-weight-bold invalid-feedback pb-2">
                            <u>Note:</u> Courses cannot be edited unless the program is Unpublished.
                        </div>
                        <div class="table-block table-striped table-hover col-12" id="programs-list">
                            <div class="dialog-courses"></div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Program ID</th>
                                        <th>Program Name</th>
                                        <th>Status</th>
                                        <th>Edit</th>
                                        <th>Active Courses</th>
                                        <th>Manage Courses</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($programs as $program)
                                    <tr>
                                        <td>{{$program->id}}</td>
                                        <td>{{$program->program_name}}</td>
                                        <td class="{{$status->getProgramStatus($program->status)[1]}}">{{$status->getProgramStatus($program->status)[0]}}</td>
                                        <td class="text-center"><a href="{{route("program.edit",$program->id)}}" class="nounderline text-info program-edit"><i class="fa fa-edit"></i></a></td>
                                        <td class="text-center"><a href="{{route("program.show",$program->id)}}" title="{{$program->program_name}}" class="text-capitalize text-success nounderline view-courses">view</a></td>
                                        <td class="text-center"><a href="{{ $program->status != 0 ? "javascript:void(0)": route("course.index", $program->id) }}" class="text-secondary"><i class="fa fa-external-link-alt"></i></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            $("#programs-list .program-edit").click(function (event) {
                event.preventDefault();
                var programStatus   =   {!! json_encode($programStatus) !!};
                $.ajax({
                    url:        $(this).attr("href"),
                    method:     "GET",
                    dataType:   "JSON",
                    beforeSend: function () {
                        $(".message-box").show().html("Loading...");
                    },
                    success:    function (result) {
                        $(".message-box").hide().html("");
                        $("#editProgram").attr("action",result['update-url']);
                        $("#editProgram #program_name").attr("value",result['program']['program_name']);
                        $("#editProgram #status").html("<option selected hidden disabled>--- Select ---</option>");
                        $.each(programStatus, function (key, value) {
                            if(key == result['program']['status'])
                                $("#editProgram #status").append("<option selected value='"+key+"'>"+value[0]+"</option>");
                            else
                                $("#editProgram #status").append("<option value='"+key+"'>"+value[0]+"</option>");
                        });
                        $("#editProgramModal").modal("show");
                    }
                });
            });

            $("#editProgram").submit(function (event) {
                event.preventDefault();
                $.ajax({
                    url:        $(this).attr("action"),
                    method:     "PUT",
                    data:       $(this).serialize(),
                    dataType:   "JSON",
                    beforeSend: function () {
                        $("#result").addClass("text-center").html("<i class='fa fa-spinner fa-2x'></i>")
                    },
                    success:    function (result) {
                        if(result.errors)
                        {
                            var error_html="";
                            $.each(result["errors"], function (key, value) {
                                error_html  +=  "<div class='alert alert-danger font-weight-bold'><i class='fa fa-exclamation-triangle'></i> "+value+"</div>";
                            });
                            $("#result").html(error_html);
                        }
                        else
                            $("#result").html("<div class='alert alert-success font-weight-bold'><i class='fa fa-check-circle'></i> "+result["success"]+"</div>");
                    }
                });
            });
            //Action on modal close
            $("#editProgramModal").on('hidden.bs.modal', function () {
                location.reload();
            });
            //Ends here
        });
        //Ajax load active courses
        $("#programs-list .view-courses").click(function (event) {
            event.preventDefault();
            var title           =   $(this).attr("title");
            $.ajax({
                url:        $(this).attr("href"),
                method:     "GET",
                dataType:   "JSON",
                beforeSend: function () {
                    $(".message-box").html("Loading...").show();
                },
                success:    function (result) {
                    if(result.success)
                    {
                        var outHtml         =   "<table class='table table-striped table-bordered'>\n" +
                            "                                <thead>\n" +
                            "                                    <tr>\n" +
                            "                                        <th width='30%'>Sequence No</th>\n" +
                            "                                        <th width='28%'>Course Name</th>\n" +
                            "                                        <th width='42%'>Duration<small> (in months)</small></th>\n" +
                            "                                    </tr>\n" +
                            "                                </thead>\n" +
                            "                                <tbody>";

                        $.each(result.success, function (key, value) {
                            outHtml         +=  "<tr>\n" +
                                "                    <td>"+value["sequence_number"]+"</td>\n" +
                                "                    <td>"+value["course_name"]+"</td>\n" +
                                "                    <td>"+value["duration"]+"</td>\n" +
                                "                    </tr>";
                        });

                        outHtml             +=   "</tbody></table>";
                        $(".dialog-courses").html(outHtml);
                        $(".message-box").html("").hide();
                        $(".dialog-courses").dialog({
                            show: {
                                effect: "fade",
                                duration: 250
                            },
                            hide: {
                                effect: "fade",
                                duration: 150
                            },
                            clickOutside: true,
                            draggable:  false,
                            resizable:  false,
                            modal:      true,
                            title:      title,
                            width:      "auto",
                            height:     "500",
                            buttons: {
                                "Close": function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        $(".dialog-courses").bind( "clickoutside", function(event){
                            $(this).dialog('close');
                        });
                    }
                    else if(result.failed)
                        $(".message-box").html("No active courses present in the program").show();
                    else
                        $(".message-box").html(result);
                }
            });
        });
        //Ends here
    </script>
@endsection