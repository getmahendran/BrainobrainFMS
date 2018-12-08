@extends('layouts.bobkar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-10 p-2 col-sm-12">
            <div class="card">
                <div class="card-header text-uppercase">{{ __('new student registration') }}</div>
                <div class="card-body">
                    @if(session()->has('message'))
                    <div class="alert alert-success text-center"><strong><i class="fa fa-check-circle"></i> {{ session()->get('message') }}</strong></div>
                    @endif
                    <form method="POST" enctype="multipart/form-data" id="studentRegisterForm" action="{{ route('student.store') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 text-center">
                                <img src="{{ asset('images/initial_profile_pic.jpg') }}" id="imgPreview" class="rounded-circle" alt="profile_image" width="220px" height="220px">
                            </div>
                            <div class="form-group col-md-6 text-center">
                                <h5 class='text-muted pb-4 pt-3'>Profile picture upload</h5>
                                <label class="btn-bs-file btn-xs btn btn-primary">
                                    <i class="fa fa-image"></i>&nbsp;&nbsp; Browse
                                    <input type="file" accept="image/*" name="path" id="path">
                                </label>
                                @if($errors->has('path'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('path') }}</span></strong>
                                @endif
                                <strong><span class="invalid-feedback" id="path-error"></span></strong>
                            </div>
                            <div class="form-group col-md-12 col-lg-3">
                                <label for="">Program</label>
                                @foreach($programs as $program)
                                <label class="container-radio">{{ $program->program_name }}
                                    <input type="radio" value="{{ $program->id }}" name="program_id" class="child_program" {{ old('program_id') == $program->id ? 'checked':'' }}>
                                    <span class="checkmark-radio"></span>
                                </label>
                                @endforeach
                                @if($errors->has('program_id'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('program_id') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-5">
                                <label for="franchisee_id">Franchisee Branch </label>
                                <select class="form-control selectpicker {{ $errors->has('franchisee_id') ? 'is-invalid':'' }} form-control" name="franchisee_id" id="franchisee_id">
                                    <option></option>
                                    @foreach($franchisees as $franchisee)
                                    <option data-tokens="" {{ old('franchisee_id') == $franchisee->id ? 'selected':'' }} value="{{ $franchisee->id }}">{{ $franchisee->center_code }} - {{ $franchisee->franchisee_name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('franchisee_id'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('franchisee_id') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-3 offset-lg-1">
                                <label for="fee_id">Admission Fee in Rs.</label>
                                <select name="fee_id" id="fee_id" class="form-control select-picker-no-search">
                                    <option></option>
                                    @foreach($admission_fees as $admission_fee)
                                    <option data-icon="fa-rupee-sign" {{old('fee_id')==$admission_fee->id?"selected":""}} value="{{$admission_fee->id}}"><i class="fa fa-rupee-sign"></i> {{$admission_fee->price}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('fee_id'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('fee_id') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-4">
                                <label for="physical_receipt_no">Physical Receipt Number </label><br>
                                <input type="text" class="form-control {{ $errors->has('physical_receipt_no') ? 'is-invalid':'' }}" value="{{ old('physical_receipt_no') }}" name="physical_receipt_no" id="physical_receipt_no">
                                @if($errors->has('physical_receipt_no'))
                                    <span class="invalid-feedback font-weight-bold">{{ $errors->first('physical_receipt_no') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-4">
                                <label for="physical_receipt_date">Physical receipt date</label>
                                <input autocomplete="off" type="text" class="form-control date {{ $errors->has('physical_receipt_date') ? 'is-invalid':'' }}" value="{{old('physical_receipt_date')}}" id="physical_receipt_date" name="physical_receipt_date">
                                @if($errors->has('physical_receipt_date'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('physical_receipt_date') }}</span></strong>
                                @endif
                            </div>
                        </div>
                        <hr class="themed-margin">
                        <div class="form-row mt-3">
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="name">Student Name</label>
                                <input type="text" value="{{ old('name') }}" class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}" name="name" id="name">
                                @if($errors->has('name'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('name') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group pl-lg-2 col-md-12 col-lg-6">
                                <label>Gender</label><br>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" {{old("gender")=="Male"?"checked":""}} id="male" value="Male" name="gender" class="custom-control-input">
                                    <label class="custom-control-label" for="male">Male</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" {{old("gender")=="Female"?"checked":""}} id="female" value="Female" name="gender" class="custom-control-input">
                                    <label class="custom-control-label" for="female">Female</label>
                                </div>
                                @if($errors->has('gender'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('gender') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="dob">Date of Birth</label>
                                <input autocomplete="off" type="text" data-max-date="{{date("Y-m-d")}}" class="date form-control {{ $errors->has('dob') ? 'is-invalid':'' }}" name="dob" id="dob" value="{{ old('dob') }}">
                                @if($errors->has('dob'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('dob') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="school_name">School Name</label>
                                <input type="text" class="form-control {{ $errors->has('school_name') ? 'is-invalid':'' }}" name="school_name" id="school_name" value="{{ old('school_name') }}">
                                @if($errors->has('school_name'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('school_name') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="standard">Standard / Grade</label>
                                <input type="text" class="form-control {{ $errors->has('standard') ? 'is-invalid':'' }}" name="standard" id="standard" value="{{ old('standard') }}">
                                @if($errors->has('standard'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('standard') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="monthly_income">Monthly Family Income</label>
                                <input type="text" class="form-control {{ $errors->has('monthly_income') ? 'is-invalid':'' }}" placeholder="(OPTIONAL)" name="monthly_income" id="monthly_income" value="{{ old('monthly_income') }}">
                                @if($errors->has('monthly_income'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('monthly_income') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="father_name">Father's Name</label>
                                <input type="text" class="form-control {{ $errors->has('father_name') ? 'is-invalid':'' }}" name="father_name" id="father_name" value="{{ old('father_name') }}">
                                @if($errors->has('father_name'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('father_name') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="mother_name">Mother's Name</label>
                                <input type="text" class="form-control {{ $errors->has('mother_name') ? 'is-invalid':'' }}" name="mother_name" id="mother_name" value="{{ old('mother_name') }}">
                                @if($errors->has('mother_name'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('mother_name') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="father_occupation">Father's Occupation</label>
                                <input type="text" class="form-control {{ $errors->has('father_occupation') ? 'is-invalid':'' }}" name="father_occupation" id="father_occupation" value="{{ old('father_occupation') }}">
                                @if($errors->has('father_occupation'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('father_occupation') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="mother_occupation">Mother's Occupation</label>
                                <input type="text" class="form-control {{ $errors->has('mother_occupation') ? 'is-invalid':'' }}" name="mother_occupation" id="mother_occupation" value="{{ old('mother_occupation') }}">
                                @if($errors->has('mother_occupation'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('mother_occupation') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="residence_address">Residence Address</label>
                                <textarea name="residence_address" id="residence_address" class="form-control {{ $errors->has('residence_address') ? 'is-invalid':'' }}" cols="30" rows="5">{{ old('residence_address') }}</textarea>
                                @if($errors->has('residence_address'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('residence_address') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="office_address">Office Address</label>
                                <textarea name="office_address" id="office_address" class="form-control {{ $errors->has('office_address') ? 'is-invalid':'' }}" cols="30" rows="5">{{ old('office_address') }}</textarea>
                                @if($errors->has('office_address'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('office_address') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="father_mobile">Father's mobile number</label>
                                <input type="text" class="form-control {{ $errors->has('father_mobile') ? 'is-invalid':'' }}" name="father_mobile" id="father_mobile" value="{{ old('father_mobile') }}">
                                @if($errors->has('father_mobile'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('father_mobile') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="mother_mobile">Mother's mobile number</label>
                                <input type="text" class="form-control {{ $errors->has('mother_mobile') ? 'is-invalid':'' }}" name="mother_mobile" id="mother_mobile" value="{{ old('mother_mobile') }}">
                                @if($errors->has('mother_mobile'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('mother_mobile') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="father_email">Father's Email</label>
                                <input type="text" class="form-control {{ $errors->has('father_email') ? 'is-invalid':'' }}" name="father_email" id="father_email" value="{{ old('father_email') }}">
                                @if($errors->has('father_email'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('father_email') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="mother_email">Mother's Email</label>
                                <input type="text" class="form-control {{ $errors->has('mother_email') ? 'is-invalid':'' }}" name="mother_email" id="mother_email" value="{{ old('mother_email') }}">
                                @if($errors->has('mother_email'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('mother_email') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label>Details of Brothers & Sisters</label>
                            </div>
                            <div class="form-group col-md-8 col-7">
                                <div class="notification_box_container">
                                    <div  class="notification_head" style="padding-top: 5px; height: 30px;"> Name </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-5">
                                <div class="notification_box_container">
                                    <div style="padding-top: 5px; height: 30px;" class="notification_head">Date of Birth</div>
                                </div>
                            </div>
                            <div class="form-group col-md-8 col-7">
                                <input class="form-control {{ $errors->has('sibling_1_name') ? 'is-invalid':'' }}" name="sibling_1_name" id="sibling_1_name" type="text" placeholder="1." value="{{ old('sibling_1_name') }}">
                                @if($errors->has('sibling_1_name'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('sibling_1_name') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-4 col-5">
                                <input autocomplete="off" class="form-control date {{ $errors->has('sibling_1_age') ? 'is-invalid':'' }}" name="sibling_1_dob" id="sibling_1_age" type="text" data-min-date="{{date("Y-m-d")}}" placeholder="" value="{{ old('sibling_1_dob') }}">
                                @if($errors->has('sibling_1_age'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('sibling_1_dob') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-8 col-7">
                                <input class="form-control {{ $errors->has('sibling_2_name') ? 'is-invalid':'' }}" name="sibling_2_name" id="sibling_2_name" type="text" placeholder="2." value="{{ old('sibling_2_name') }}">
                                @if($errors->has('sibling_2_name'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('sibling_2_name') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-4 col-5">
                                <input autocomplete="off" class="form-control date {{ $errors->has('sibling_2_age') ? 'is-invalid':'' }}" name="sibling_2_dob" id="sibling_2_age" type="text" data-min-date="{{date("Y-m-d")}}" placeholder="" value="{{ old('sibling_2_dob') }}">
                                @if($errors->has('sibling_2_age'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('sibling_2_dob') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                                <label>How do you know about Brainobrain ?</label><br>
                                @for($i=0;$i<count($sourcess);$i++)
                                <div class="custom-control source custom-radio custom-control-inline">
                                    <input {{ old('source')==''.$i ? 'checked':'' }} type="radio" id="{{$sourcess[$i]}}" name="source" value="{{$i}}" class="custom-control-input">
                                    <label class="custom-control-label" for="{{$sourcess[$i]}}">{{$sourcess[$i]}}</label>
                                </div>
                                @endfor
                                @if($errors->has('source'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('source') }}</span></strong>
                                @endif
                                <label for="comments" class="sr-only"></label>
                                <input type="text" name="comments" class="form-control-sm form-control col-12 {{$errors->has('comments')?'is-invalid':''}}" id="comments" value="{{ old('comments') }}">
                                @if($errors->has('comments'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('comments') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-md-12 col-lg-12">
                                <label for="about_child">Please tell us about child</label>
                                <textarea name="about_child" id="about_child" class="form-control {{ $errors->has('about_child') ? 'is-invalid':'' }}" cols="30" rows="5">{{ old('about_child') }}</textarea>
                                @if($errors->has('about_child'))
                                    <strong><span class="invalid-feedback">{{ $errors->first('about_child') }}</span></strong>
                                @endif
                            </div>
                            <div class="form-group col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-12">
                                <button type="submit" class="btn btn-block btn-warning">{{ __('Register') }}</button>
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
        $(document).ready(function () {
        //jQuery for how do you know bob show and hide text box
                $('#studentRegisterForm .source').change(function () {

                    if ($(this).find('input').val() == 6) {
                        // $('#comments').attr('placeholder', 'Mr./Mrs.').val('');
                        $("#comments").val("").show().attr('placeholder', 'Mr/Ms/Mrs');
                    }
                    else if ($(this).find('input').val() == 7) {
                        $('#comments').attr('placeholder', 'Specify..').val('').show();
                    }
                    else $('#comments').css('display', 'none');
                });
        //end of jQuery for how do you know bob show and hide text box

        //jQuery to show a preview selected image
            $('#studentRegisterForm #path').change(function () {
                readURL(this, default_path_value);
            });
        //Ends here

        });
        var default_path_value        =       '{{ asset('images/initial_profile_pic.jpg') }}';
        var source                    =         '{{ old('source') }}';
        if(source == '6')
            $('#comments').show().attr('placeholder','Mr/Ms/Mrs');
        else if(source == '7')
            $('#comments').show().attr('placeholder','Specify');
        else
            $('#comments').hide();
    </script>
@endsection