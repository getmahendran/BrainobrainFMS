@extends('layouts.bobkar')

@section('content')
    <!-- Password reset Modal -->
    <div class="modal fade" id="franchiseePasswordResetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Password Reset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="franchiseePasswordResetForm" method="POST" action="{{ route('franchisee.update',$franchisee->id) }}">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="button_action" value="update_password">
                        <div id="result" class="form-group col-12 text-center"></div>
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Model Ends here-->

    <!-- Profile pictre upload Modal -->
    <div class="modal fade" id="franchiseeProfileImageUpdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Change profile picture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="result"></div>
                    <form id="franchiseeProfileImageUpdateForm" method="POST" enctype="multipart/form-data" action="{{ route('franchisee.update', $franchisee->id) }}">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="button_action" value="update_profile_image">
                        <div class="row">
                            <div class="form-group col-12">
                                <div class="text-center">
                                    <img src="" id="imgPreview" height="360px" width="360px" alt="profile_image">
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
                <div class="card-header text-uppercase">Franchisee -  <b>{{ $franchisee->center_code }} - {{ $franchisee->franchisee_name }}</b></div>
                <div class="card-body">
                    <div class="col-lg-2 col-md-3 pb-3"><a href="{{ route('franchisee.index') }}" class="nounderline text-muted"><i class="fa fa-arrow-left fa-2x"></i></a></div>
                    <div id="errors"></div>
                    <form method="POST" action="{{ route('franchisee.update', $franchisee->id) }}" enctype="multipart/form-data" id="franchiseeEditForm">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="button_action" value="update_details">
                        <div class="form-row">
                            <div class="form-group col-md-6 text-center">
                                <img src="{{asset($franchisee->path)}}" id="imgPreview" class="rounded-circle" alt="profile_image" width="220px" height="220px">
                                <div class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-6 text-center">
                                <h5 class='text-center pt-4'>Profile picture</h5>
                                <a class="btn btn-primary" id="browse" href="javascript:void(0)">
                                    <i class="fa fa-file-image"></i>&nbsp;&nbsp; View / Change
                                </a>
                            </div>
                            <div class="form-group col-12">
                                <a href="javascript:void(0)" id="passwordReset" class="float-right nounderline"><i class="fas fa-key"></i> Reset password</a>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name"> Name</label>
                                <input type="text" name="name" id="name" value="{{ $franchisee->name }}" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}">
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="father_name">Father Name</label>
                                <input type="text" name="father_name" id="father_name" value="{{ $franchisee->father_name }}" class="form-control {{ $errors->has('father_name') ? ' is-invalid' : '' }}">
                                @if ($errors->has('father_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('father_name') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                                <label for="permanent_address">Permanent Address</label>
                                <textarea name="permanent_address" id="permanent_address" class="form-control {{ $errors->has('permanent_address') ? ' is-invalid' : '' }}" cols="15" rows="4">{{ $franchisee->permanent_address }}</textarea>
                                @if ($errors->has('permanent_address'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('permanent_address') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="contact_no1">Contact number 1</label>
                                <input type="text" value="{{ $franchisee->contact_no1 }}" name="contact_no1" id="contact_no1" class="form-control {{ $errors->has('contact_no1') ? 'is-invalid' : '' }}">
                                @if ($errors->has('contact_no1'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contact_no1') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="contact_no2">Contact number 2</label>
                                <input type="text" id="contact_no2" value="{{ $franchisee->contact_no2 }}" name="contact_no2" class="form-control {{ $errors->has('contact_no2') ? 'is-invalid' : '' }}">
                                @if($errors->has('contact_no2'))
                                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('contact_no2') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Email ID</label>
                                <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ $franchisee->email }}">
                                @if($errors->has('email'))
                                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="qualification">Qualification</label>
                                <input type="text" name="qualification" id="qualification" value="{{ $franchisee->qualification }}" class="form-control {{ $errors->has('qualification') ? 'is-invalid' : '' }}">
                                @if($errors->has('qualification'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('qualification') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="occupation">Occupation</label>
                                <input type="text" id="occupation" value="{{ $franchisee->occupation }}" class="form-control {{ $errors->has('occupation') ? 'is-invalid' : '' }}" name="occupation">
                                @if($errors->has('occupation'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('occupation') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dob">Date of Birth</label>
                                <input type="date" name="dob" value="{{ $franchisee->dob }}" class="form-control {{ $errors->has('dob') ? 'is-invalid' : '' }}" id="dob">
                                @if($errors->has('dob'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('dob') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="family_income">Family Income in Rs.</label>
                                <input type="text" value="{{ $franchisee->family_income }}" class="form-control {{ $errors->has('family_income') ? 'is-invalid' : '' }}" id="family_income" name="family_income">
                                @if($errors->has('family_income'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('family_income') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Gender</label> <br>
                                <label><input type="radio" name="gender" value="Male" {{ $franchisee->gender == 'Male'  ? 'checked' : '' }}> Male</label>&nbsp;&nbsp;&nbsp;&nbsp;
                                <label><input type="radio" name="gender" value="Female" {{ $franchisee->gender == 'Female'  ? 'checked' : '' }}> Female</label><br/>
                                @if($errors->has('gender'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('gender') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">Married</label> <br>
                                <label><input class="married-check" type="radio" data-url="{{route("franchiseeMarried.edit",$franchisee->id)}}" name="married" value="1" {{ $franchisee->married == 1 ? 'checked' : ''}}> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label><input class="married-check" type="radio" name="married" value="0" {{ $franchisee->married == 0  ? 'checked' : ''}}> No</label>
                                @if($errors->has('married'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('married') }}</strong></span>
                                @endif
                            </div>
                            <div id="married-form" class="form-row">
                                @if($franchisee->married == 1)
                                    @include('married')
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="languages_known">Languages Known</label>
                                <input type="text" value="{{ $franchisee->languages_known }}" name="languages_known" id="languages_known" class="form-control {{ $errors->has('languages_known') ? 'is-invalid' : '' }}">
                                @if($errors->has('languages_known'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('languages_known') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="hobbies">Hobbies</label>
                                <input type="text" name="hobbies" value="{{ $franchisee->hobbies }}" id="hobbies" class="form-control {{ $errors->has('hobbies') ? 'is-invalid' : '' }}">
                                @if($errors->has('hobbies'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('hobbies') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="special_at">You are special at ?</label>
                                <input type="text" name="special_at" id="special_at" value="{{ $franchisee->special_at }}" class="form-control {{ $errors->has('special_at') ? 'is-invalid' : '' }}">
                                @if($errors->has('special_at'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('special_at') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="past_experience">Past Experience</label>
                                <input type="text" name="past_experience" value="{{ $franchisee->past_experience }}" id="past_experience" class="form-control {{ $errors->has('past_experience') ? 'is-invalid' : '' }}">
                                @if($errors->has('past_experience'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('past_experience') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="franchisee_name">Franchisee Name</label>
                                <input type="text" name="franchisee_name" value="{{ $franchisee->franchisee_name }}" id="franchisee_name" class="form-control {{ $errors->has('') ? 'is-invalid' : '' }}">
                                @if($errors->has('franchisee_name'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('franchisee_name') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="area_id">Rural/Urban</label>
                                <select class="form-control" name="area_id" id="area_id">
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" {{ $area->id == $franchisee->area_id ? 'selected' : '' }}>{{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('area_id'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('area_id') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="franchisee_address">Franchisee Full Address</label>
                                <textarea id="franchisee_address" name="franchisee_address" class="form-control {{ $errors->has('franchisee_address') ? 'is-invalid' : '' }}" rows="4">{{ $franchisee->franchisee_address }}</textarea>
                                @if($errors->has('franchisee_address'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('franchisee_address') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status">Status</label>
                                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                                    <option selected disabled hidden>--- Select ---</option>
                                    @for($i=count($franchisee_status)-1; $i>=0;$i--)
                                    <option {{$i==$franchisee->status ? "selected":""}} value="{{$i}}">{{$franchisee_status[$i][0]}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4 col-sm-12 col-12 offset-md-2 p-1">
                                <button type="submit" class="btn btn-block btn-success">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                            <div class="col-md-4 col-12 col-sm-12 p-1">
                                <a href="{{ route('franchisee.index') }}" class="btn btn-block btn-secondary">
                                    {{ __('Exit Without Saving') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    /*Ajax franchisee password reset*/
    $("#franchiseePasswordResetModal #franchiseePasswordResetForm").submit(function (event) {
        event.preventDefault();
        var errorHtml    =  '';
        $.ajax({
            url:            $(this).attr('action'),
            type:           'PUT',
            dataType:       'JSON',
            data:           $(this).serialize(),
            beforeSend:     function () {
                $('#result').html('<i class="fa fa-spinner"></i>');
            },
            success:        function (result) {
                if(result.errors){
                    $.each(result.errors, function (key, value) {
                        errorHtml   += '<div class="alert alert-danger font-weight-bold"><i class="fa fa-exclamation-triangle"></i>  '+value+'</div>';
                    });
                    $('#result').html(errorHtml);
                } else {
                    $('#result').html('<div class="alert alert-success font-weight-bold"><i class="fa fa-check-circle"></i>  '+result['success']+'</div>');
                    $('#franchiseePasswordResetModal #franchiseePasswordResetForm')[0].reset();
                }
            }
        });
    });
    /*Ends here*/

    /*Ajax faculty profile image update*/
    $('#franchiseeProfileImageUpdateForm').on('submit', function (event) {
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
                $('#franchiseeProfileImageUpdateModal #result').show().html('<i class="fa fa-spinner fa-2x"></i>').addClass('text-center');
            },
            success:        function (result) {
                if(result.errors){
                    $('#franchiseeProfileImageUpdateModal #result').html('');
                    $.each(result.errors, function (key, value) {
                        errorHtml+=value + "<br>";
                    });
                    $('#franchiseeProfileImageUpdateModal #franchiseeProfileImageUpdateForm')[0].reset();
                    $('#path-error').html(errorHtml);
                }else{
                    $('#franchiseeProfileImageUpdateModal #result').html('<div class="alert alert-success font-weight-bold"><i class="fa fa-check-circle"></i> '+result["success"]+'</div>');
                    $('#franchiseeEditForm #imgPreview').attr('src', $('#franchiseeProfileImageUpdateModal #imgPreview').attr('src'));
                    $('#franchiseeProfileImageUpdateModal #franchiseeProfileImageUpdateForm')[0].reset();
                }
            },
        });
    });
    /*Ends here*/

    /*AJAX Franchisee profile update*/
    $('#franchiseeEditForm').submit(function (event) {
        errorHtml='';
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url:            $(this).attr('action'),
            type:           'PUT',
            dataType:       'JSON',
            data:           formData,
            beforeSend:     function () {
                $('.message-box').show().html('Updating...');
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

    //jQuery - modal popup for change profile picture of franchisee
    $('#franchiseeEditForm #browse').click(function () {
        $("#franchiseeProfileImageUpdateModal #result").html("");
        $('#path-error').html('');
        $('#franchiseeProfileImageUpdateModal #franchiseeProfileImageUpdateForm')[0].reset();
        $('#franchiseeProfileImageUpdateModal #imgPreview').attr('src', $('#franchiseeEditForm #imgPreview').attr('src'));
        $('#franchiseeProfileImageUpdateModal').modal('show');
    });
    //Ends here

    //jQuery - modal popup for franchisee password
    $('#franchiseeEditForm #passwordReset').click(function () {
        $('#result').html('');
        $('#franchiseePasswordResetModal').modal('show');
        $('#franchiseePasswordResetModal #franchiseePasswordResetForm')[0].reset();
    });
    //Ends here

    /*JS to check change in status in radio button married Yes or No*/
    $('#franchiseeEditForm .married-check').change(function () {
        var value   =   $(this).val();
        if(value == '1'){
            marriedDisplay($(this).data("url"));
        }else{
            $('.message-box').html("").hide();
            $('#married-form').html('');
        }
    });/*Ends here*/
</script>
@endsection