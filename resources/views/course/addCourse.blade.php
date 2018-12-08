@extends('layouts.bobkar')

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="addProgramModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">New Program</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="result"></div>
                    <form id="addNewProgram" method="post" action="{{ route('program.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="program_name" class="col-form-label">Program Name</label>
                            <input type="text" class="form-control" name="program_name" id="program_name">
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-form-label">Status</label>
                            <select name="status" id="status" class="form-control select-picker-no-search">
                                <option></option>
                                @for($i=0;$i<count($programStatus);$i++)
                                    <option value="{{$i}}">{{$programStatus[$i][0]}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Model Ends here-->

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-uppercase">{{ __('Add New Course') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('course.store') }}" id="addNewCourse">
                        @csrf
                        @if(session()->has('success'))
                            <div class="row">
                                <div class="alert alert-success col-sm-12 text-center">
                                    <strong><i class="fa fa-check-circle"></i> {{ session()->get('success') }}</strong>
                                </div>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label for="program_id" class="col-md-4 col-form-label text-md-right">{{ __('Program Name') }}</label>
                            <div class="col-md-6">
                                <select name="program_id" class="form-control select-picker-no-search" id="program_id">
                                    <option></option>
                                    @if(!empty($course_types)>0)
                                    @foreach($course_types as $course_type)
                                        <option target-url="{{route("program.courses",$course_type->id)}}" value="{{ $course_type->id }}">{{ $course_type->program_name }}</option>
                                    @endforeach
                                    @endif
                                    <option value="new">New</option>
                                </select>
                                @if ($errors->has('program_id'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('program_id') }}</strong></span>
                                @endif
                            </div>
                            <div class="col-md-2">
                                <h3><a href="javascript:void(0)" class="nounderline text-info font-weight-bold" data-toggle="popover" title="Note" data-content="<p align='justify'>Select <strong>New</strong> option to add new program.</p>"><i class="fa fa-question-circle"></i></a></h3>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="course_name" class="col-md-4 col-form-label text-md-right">{{ __('Course Name') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="course_name" id="course_name" value="{{ old('course_name') }}">
                                @if ($errors->has('course_name'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('course_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="duration" class="col-md-4 col-form-label text-md-right">{{ __('Duration in months') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ old('duration') }}" name="duration" id="duration">
                                @if ($errors->has('duration'))
                                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('duration') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sequence_number" class="col-md-4 col-form-label text-md-right">{{ __('Course Sequence Number') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="sequence_number" id="sequence_number" value="{{old("sequence_number")}}">
                                @if ($errors->has('sequence_number'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('sequence_number') }}</strong></span>
                                @endif
                            </div>
                            <div class="col-md-2">
                                <h3><a href="javascript:void(0)" class="nounderline text-info font-weight-bold" data-toggle="popover" title="Note" data-content="<p align='justify'>Enter the sequence number of the course in selected level.<br><i>Eg: <strong>1</strong> if it is the first course in the selected level.</p>"><i class="fa fa-question-circle"></i></a></h3>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="course-status" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>
                            <div class="col-md-6">
                                <select name="status" id="course-status" class="form-control select-picker-no-search">
                                    <option value=""></option>
                                @for($i=count($courseStatus)-1;$i>=0;$i--)
                                    <option value="{{$i}}">{{$courseStatus[$i][0]}}</option>
                                @endfor
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        //jquery to add new Program
        $("#addNewProgram").submit(function(event){
            event.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
                type:       'POST',
                async:      true,
                url:        $(this).attr("action"),
                dataType:   "JSON",
                data:       form_data,
                beforeSend: function(){$("#result").html('Loading...')},
                success: function(result)
                {
                    if(result.error){
                        var error_html='';
                        $.each(result.error, function (key, value) {
                            error_html+='<div class="alert alert-danger font-weight-bold"><i class="fa fa-exclamation-triangle"></i> '+value+'</div>';
                        });
                        $("#result").html(error_html);
                    } else {
                        programDropDownRefresh();
                        $('#result').html("<div class='alert alert-success font-weight-bold'><i class='fa fa-check-circle'></i> "+result['success']+"</div>");
                        $('#addNewProgram')[0].reset();
                    }
                }
            });
        });
        //end of jquery to add new Program

        //jQuery for model popup for new program & ajax to retrive courses for existing programs
        $("#addNewCourse #program_id").on("change", function (event) {
            event.preventDefault();
            $modal = $('#addProgramModal');
            if ($(this).val() === 'new') {
                $('#result').html('');
                $('#addNewProgram')[0].reset();
                $modal.modal('show');
            }
        });
            //Ends here
        //jQuery for closing add program modal
        $('#addProgramModal').on('hidden.bs.modal', function () {
            $("#addNewCourse #program_id").val(null).trigger('change');
            $("#previous_course_id").html("<option selected disabled hidden>--- Select ---</option>");
        });
        // Ends here
    </script>
@endsection