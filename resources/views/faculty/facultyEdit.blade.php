@extends('layouts.bobkar')

@section('content')
    <div class="dialog-confirm"></div>
    <!-- Update Training courses modal -->
    <div class="modal fade" id="facultyTrainingDetailsUpdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Update Training Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="result"></div>
                    <form action="{{route('faculty.update',$faculty->id)}}">
                        <div class="form-group col-md-12 col-lg-3">
                            <label for="">Program</label>
                            @foreach($programs as $program)
                                <label class="container-radio">{{ $program->program_name }}
                                    <input type="radio" value="{{ route('course.index',$program->id) }}" name="program_id" class="program_id">
                                    <span class="checkmark-radio"></span>
                                </label>
                            @endforeach
                            @if($errors->has('program_id'))
                                <strong><span class="invalid-feedback">{{ $errors->first('program_id') }}</span></strong>
                            @endif
                        </div>
                        <div class="form-group col-md-12 col-lg-12">
                            <label for="course_id">Course</label>
                            <select name="course_id" id="course_id" class="form-control select-picker-no-search">
                            </select>
                        </div>
                        <div class="form-group col-md-12 col-lg-12">
                            <button class="btn btn-block btn-success">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Ends here -->
    <!-- Password reset Modal -->
    <div class="modal fade" id="facultyPasswordResetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Password Reset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="result"></div>
                    <form id="facultyPasswordResetForm" method="POST" action="{{ route('faculty.update',$facultyAccount->id) }}">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group">
                            <label for="password" class="col-form-label">New Password</label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                        <div class="form-group">
                            <label for="password-confirm" class="col-form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password-confirm" id="password-confirm">
                        </div>
                        <div class="form-group float-right">
                            <button type="submit" class="btn btn-info">Reset</button>
                            <button type="submit" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Model Ends here-->
    <!-- Faculty profile picture edit Modal -->
    <div class="modal fade" id="facultyProfileImageUpdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Change profile picture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="result"></div>
                    <form id="facultyProfileImageUpdateForm" method="POST" enctype="multipart/form-data" action="{{ route('faculty.updateImage', $facultyAccount->id) }}">
                        @csrf
                        <div class="row">
                            <div class="form-group col-12">
                                <div class="text-center">
                                    <img src="{{asset($faculty->path)}}" id="imgPreview" alt="profile_image" width="360px" height="360px">
                                </div>
                            </div>
                            <div class="offset-2 col-8 text-center">
                                <div class="form-group">
                                    <label class="btn-bs-file btn btn-primary ">
                                        <i class="fa fa-image"></i>&nbsp;&nbsp; Browse
                                        <input type="file" accept="image/*" name="path" id="path">
                                    </label>
                                    <strong><span class="invalid-feedback" id="path-error"></span></strong>
                                </div>
                                <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Save</button>
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Model Ends here-->
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-10 p-2 col-sm-12">
            <div class="card">
                <div class="card-header text-uppercase">{{ __('Faculty ID - ') }} <strong>{{ $facultyAccount->faculty_code }} - {{ $facultyAccount->franchisee->franchisee_name }} Branch</strong></div>
                <div class="card-body">
                    <div class="col-lg-2 col-md-3 pb-3"><a href="{{ route('faculty.index') }}" class="nounderline text-muted"><i class="fa fa-arrow-left fa-2x"></i></a></div>
                    <div id="errors"></div>
                    <div class="form-row">
                        <div class="form-group col-md-6 text-center">
                            <img src="{{ asset($faculty->path) }}" id="imgPreview" class="rounded-circle" alt="profile_image" width="220px" height="220px">
                        </div>
                        <div class="form-group col-md-6 text-center">
                            <h5 class='pb-4 pt-3'>Profile picture</h5>
                            <a class="btn btn-primary" id="browse" href="javascript:void(0)">
                                <i class="fa fa-file-image"></i>&nbsp;&nbsp; Change / View
                            </a>
                        </div>
                        <div class="form-group col-12">
                            <a href="javascript:void(0)" id="passwordReset" class="float-right nounderline"><i class="fas fa-key"></i> Reset password</a>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-12 ftab-container pl-0 pb-0 pr-0">
                        <ul class="pt-2 pb-2 pr-0 col-12">
                            <li class="pl-3 pr-3 pt-2 pb-2" data-url="{{route('faculty.edit',$facultyAccount->id)}}?button_action=personal">Personal Profile</li>
                            <li class="pl-3  pr-3 pt-2 pb-2" data-url="{{route('faculty.edit',$facultyAccount->id)}}?button_action=training">Training Profile</li>
                        </ul>
                        <div class="ftab-body pt-0 col-12">
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        default_path_value  =   "{{asset($faculty->path)}}";
        $(document).ready(function () {

            var field = 'button_action';
            var url = window.location.href;
            if(url.indexOf('?' + field + '=') != -1)
                element     =   $(".ftab-container ul li[data-url='"+window.location+"']").addClass("active");

            else
            {
                element     =   $(".ftab-container ul li:first-child");
                $(element).addClass("active");
                history.pushState(null,"", element.data("url"));
            }
            loadDetails(element);
            /*JS to check change in status in radio button married Yes or No*/
            $(document).on("change","#facultyEditForm .married-check",function () {
                var value   =   $(this).val();
                if(value == '1')
                    marriedDisplay($(this).data('url'));
                else
                    $('#married-form').html('');
            });/*Ends here*/

            /*Ajax faculty password reset*/
            $("#facultyPasswordResetModal #facultyPasswordResetForm").submit(function (event) {
                event.preventDefault();
                var errorHtml    =  '';
                $.ajax({
                    url:            $(this).attr('action'),
                    type:           'PUT',
                    dataType:       'JSON',
                    data:           $(this).serialize()+"&button_action=password_reset",
                    beforeSend:     function () {
                        $('#result').html('<div class="alert alert-secondary">Updating..</div>');
                    },
                    success:        function (result) {
                        if(result.errors){
                            $.each(result.errors, function (key, value) {
                                errorHtml   += '<div class="alert alert-danger font-weight-bold"><i class="fa fa-exclamation-triangle"></i>  '+value+'</div>';
                            });
                            $('#result').html(errorHtml);
                        } else {
                            $('#result').html('<div class="alert alert-success font-weight-bold"><i class="fa fa-check-circle"></i>  '+result['success']+'</div>');
                            $('#facultyPasswordResetModal #facultyPasswordResetForm')[0].reset();
                        }
                    }
                });
            });
            /*Ends here*/

            /*Ajax update faculty profile*/
            $(document).on("submit", "#facultyEditForm", function (event) {
                var errorHtml='';
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url:            $(this).attr('action'),
                    type:           'PUT',
                    dataType:       'JSON',
                    data:           formData+"&button_action=update_profile",
                    beforeSend:     function () {
                        $('.message-box').show().html("Loading..");
                    },
                    success:        function (result) {
                        if(result.errors){
                            $('.message-box').hide()
                            $.each(result.errors, function (key, value) {
                                errorHtml   +=  '<div class="alert alert-danger"><strong><i class="fa fa-exclamation-triangle"></i> '+value+'</strong></div>';
                            });
                            $('#errors').show().html(errorHtml);
                            $('html, body').animate({
                                scrollTop: 0}, 500);
                        }else{
                            $('.message-box').html(result);
                            $('#errors').hide();
                            setTimeout(function() { $(".message-box").hide(); }, 1000);
                        }
                    },
                });
            });
            /*Ends here*/

            //Ajax faculty profile image update
            $(document).on("submit","#facultyProfileImageUpdateForm", function(event){
                event.preventDefault();
                var errorHtml   =   "";
                var formData    =   new FormData(this);
                $.ajax({
                    url:            $(this).attr('action'),
                    type:           'POST',
                    dataType:       'JSON',
                    data:           formData,
                    cache:          false,
                    contentType:    false,
                    processData:    false,
                    beforeSend:     function () {
                        $('#facultyProfileImageUpdateModal #result').show().html('<i class="fa fa-spinner fa-2x"></i>').addClass('text-center');
                    },
                    success:        function (result) {
                        if(result.errors){
                            $('#facultyProfileImageUpdateModal #result').html('');
                            $.each(result.errors, function (key, value) {
                                errorHtml+=value + "<br>";
                            });
                            $('#facultyProfileImageUpdateModal #facultyProfileImageUpdateForm')[0].reset();
                            $('#path-error').html(errorHtml);
                        }else{
                            $('#facultyProfileImageUpdateModal #result').html('<div class="alert alert-success"><strong>'+result["success"]+'</strong></div>');
                            $('#facultyEditForm #imgPreview').attr('src', $('#facultyProfileImageUpdateModal #imgPreview').attr('src'));
                            $('#facultyProfileImageUpdateModal #facultyProfileImageUpdateForm')[0].reset();
                        }
                    },
                });
            });
            //Ends here

            //jQuery - modal popup for change profile picture of faculty
            // $('#browse').click(function () {
            $(document).on("click", "#browse", function(event) {
                $("#facultyProfileImageUpdateModal #result").html("");
                $('#path-error').html('');
                $('#facultyProfileImageUpdateModal #facultyProfileImageUpdateForm')[0].reset();
                $('#facultyProfileImageUpdateModal #imgPreview').attr('src', $('#facultyEditForm #imgPreview').attr('src'));
                $('#facultyProfileImageUpdateModal').modal('show');
            });
            /*Ends here*/

            //jQuery - modal popup for franchisee password
            $('#passwordReset').click(function () {
                $('#result').html('');
                $('#facultyPasswordResetModal').modal('show');
                $('#facultyPasswordResetModal #facultyPasswordResetForm')[0].reset();
            });
            //Ends here

            //Show currently browsed profile image
            $(document).on("change","#facultyRegisterForm #path",function () {
                readURL(this, default_path_value);
            });
            //Ends here

            //
            $('#facultyProfileImageUpdateModal #path').change(function () {
                readURL(this, default_path_value);
            });

            $(document).on("click",".update-faculty-training-details", function (event) {
                event.preventDefault();
                $("#facultyTrainingDetailsUpdateModal").modal("show");
            });

            $(document).on("click",".ftab-container ul li", function (event) {
                event.preventDefault();
                $(".ftab-container ul li").removeClass("active");
                $(this).addClass("active")
                history.pushState(null, "", $(this).data("url"));
                loadDetails($(this));
            });

            $(document).on("submit","#facultyTrainingDetailsUpdateModal form", function (event) {
                event.preventDefault();
                $.ajax({
                    url:        $(this).attr("action"),
                    method:     "PUT",
                    dataType:   "JSON",
                    data:       $(this).serialize()+"&button_action=update_training_profile",
                    beforeSend: function () {
                        $("#load").show()
                    },
                    success:    function (result) {
                        $("#load").hide();
                        if(result.success)
                        {
                            $("#facultyTrainingDetailsUpdateModal form")[0].reset();
                            loadDetails($(".ftab-container ul li.active"));
                            $(".result").html("<div class='alert alert-success font-weight-bold'><i class='fa fa-check-circle'></i> "+result['success']+"</div>");
                        }
                        else
                        {
                            var out_html='';
                            $.each(result['errors'], function (key, value) {
                                out_html    +=  "<div class='alert-danger alert font-weight-bold'><i class='fa fa-exclamation-circle'></i> "+value+"</div>";
                            });
                            $(".result").html(out_html);
                        }
                    }
                });
            });

            $(document).on("click",".remove-training", function (event) {
                event.preventDefault();
                element =   $(this);
                $(".dialog-confirm").html("Want to remove this training ?");
                $(".dialog-confirm").dialog({
                    draggable:  false,
                    resizable:  false,
                    modal:      true,
                    title:      "Are you sure?",
                    width:      320,
                    height:     200,
                    buttons: {
                        "Yes": function () {
                            delete_trained_course(element);
                            $(this).dialog('close');
                        },
                        "No": function () {
                            $(this).dialog('close');
                        }
                    }
                });
            });

            function delete_trained_course(element)
            {
                $.ajax({
                    url:        element.attr("href"),
                    method:     "DELETE",
                    data:       "button_action=remove_trained_course",
                    dataType:   "JSON",
                    beforeSend: function () {
                        $("#load").show();
                    },
                    success:    function (result) {
                        $("#load").hide();
                        $(".message-box").show().html(result['success']);
                        setTimeout(function () {
                            $(".message-box").hide();
                        }, 3000);
                        loadDetails($(".ftab-container ul li.active"));
                    }
                });
            }

            $(document).on("change","#facultyTrainingDetailsUpdateModal .program_id", function (event) {
                event.preventDefault();
                $.ajax({
                    url:        $(this).val(),
                    dataType:   "JSON",
                    method:     "GET",
                    beforeSend: function () {
                        $("#load").show();
                    },
                    success:    function (result) {
                        var out_html    =   '<option></option>';
                        $.each(result.courses, function (key, value) {
                            out_html    +=  "<option value='"+value['id']+"'>"+value['course_name']+"</option>";
                        });
                        $("#facultyTrainingDetailsUpdateModal #course_id").html(out_html);
                        $("#load").hide();
                    }
                });
            });

            function loadDetails(element)
            {
                var query_string = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                $.ajax({
                    url:        element.data("url"),
                    method:     "GET",
                    data:       query_string,
                    beforeSend:     function () {
                        $("#load").show();
                    },
                    success:        function (result) {
                        $("#load").hide();
                        $(".ftab-container .ftab-body").html(result);
                        console.log(result)
                    }
                });
            }
        });
    </script>
@endsection