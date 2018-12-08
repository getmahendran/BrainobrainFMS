@extends('layouts.bobkar')

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="studentProfileImageUpdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <form id="studentProfileImageUpdateForm" method="POST" enctype="multipart/form-data" action="{{ route('student.updateImage', $student->id) }}">
                        @csrf
                        <div class="row">
                            <div class="form-group col-12">
                                <div class="text-center">
                                    <img src="" id="imgPreview" class="rounded-circle" alt="profile_image" width="220px" height="220px">
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
        <div class="col-12">
            <div class="card">
                <div class="card-header active text-uppercase">student <strong> - {{ $level->student_no }}</strong></div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="studentEditForm" action="{{ route('student.update', $student->id) }}">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-row">
                            <div class="form-group col-md-6 text-center">
                                <img src="{{ asset($student->path) }}" id="imgPreview" class="rounded-circle" alt="profile_image" width="220px" height="220px">
                            </div>
                            <div class="form-group col-md-6 text-center">
                                <h5 class='text-muted pb-4 pt-3'>Profile picture upload</h5>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#studentProfileImageUpdateModal">
                                    <i class="fa fa-file-image"></i>&nbsp;&nbsp;Change
                                </button>
                            </div>
                            <div class="form-group col-md-12 col-lg-2">
                                <label for="">Program</label>
                                <span class="text-success form-text font-weight-bold">{{ $level->course->program->program_name }}</span>
                            </div>
                            <div class="form-group col-md-12 col-lg-2">
                                <label for="franchisee_id">Course </label>
                                <span class="text-danger form-text font-weight-bold">{{ $level->course->course_name }}</span>
                            </div>
                            <div class="form-group col-md-12 col-lg-2">
                                <label for="">Franchisee </label><br>
                                <span class="text-success font-weight-bold" id="admission_fee">{{ $level->franchisee->branch_name }}</span>
                            </div>
                            <div class="form-group col-md-12 col-lg-2">
                                <label for="receipt_no"> Status </label><br>
                                <input type="text" class="form-control form-control-sm" name="receipt_no" id="receipt_no">
                            </div>
                        </div>
                        <hr class="themed-margin">
                        <div class="form-row mt-3">
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="name">Student Name</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ $student->name }}">
                            </div>
                            <div class="form-group pl-lg-2  col-md-12 col-lg-6">
                                <label>Gender</label>
                                <div class="d-block pt-lg-2">
                                    <label><input type="radio" name="gender"  value="Male" {{$student->gender=='Male'? 'checked':''}}>&nbsp;&nbsp;Male</label>&nbsp;&nbsp;&nbsp;
                                    <label><input type="radio" name="gender" value="Female" {{$student->gender=='Female'? 'checked':''}}>&nbsp;&nbsp;Female</label>
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="dob">Date of Birth</label>
                                <input type="date" class="form-control {{ $errors->has('dob') ? 'is-invalid':'' }}" name="dob" id="dob" value="{{ $student->dob }}">
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="school_name">School Name</label>
                                <input type="text" class="form-control" name="school_name" id="school_name" value="{{$student->school_name}}">
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="standard">Standard / Grade</label>
                                <input type="text" class="form-control" name="standard" id="standard" value="{{$student->standard}}">
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="monthly_income">Monthly Family Income</label>
                                <input type="text" class="form-control" placeholder="(OPTIONAL)" name="monthly_income" id="monthly_income" value="{{$student->monthly_income}}">
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="father_name">Father's Name</label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="{{$student->father_name}}">
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="mother_name">Mother's Name</label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name" value="{{$student->mother_name}}">
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="father_occupation">Father's Occupation</label>
                                <input type="text" class="form-control" name="father_occupation" id="father_occupation" value="{{$student->father_occupation}}">
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="mother_occupation">Mother's Occupation</label>
                                <input type="text" class="form-control {{ $errors->has('mother_occupation') ? 'is-invalid':'' }}" name="mother_occupation" id="mother_occupation" value="{{$student->mother_occupation}}">
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="office_address">Office Address</label>
                                <textarea name="office_address" id="office_address" class="form-control" cols="30" rows="5">{{$student->office_address}}</textarea>
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="residence_address">Residence Address</label>
                                <textarea name="residence_address" id="residence_address" class="form-control" cols="30" rows="5">{{$student->residence_address}}</textarea>
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="office_pincode">Office Pincode</label>
                                <input type="text" class="form-control" name="office_pincode" id="office_pincode" value="{{$student->office_pincode}}">
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="residence_pincode">Residence Pincode</label>
                                <input type="text" class="form-control" name="residence_pincode" id="residence_pincode" value="{{$student->residence_pincode}}">
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="father_mobile">Father's mobile number</label>
                                <input type="text" class="form-control" name="father_mobile" id="father_mobile" value="{{$student->father_mobile}}">
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="mother_mobile">Mother's mobile number</label>
                                <input type="text" class="form-control" name="mother_mobile" id="mother_mobile" value="{{$student->mother_mobile}}">
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="father_email">Father's Email</label>
                                <input type="text" class="form-control" name="father_email" id="father_email" value="{{$student->father_email}}">
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label for="mother_email">Mother's Email</label>
                                <input type="text" class="form-control" name="mother_email" id="mother_email" value="{{$student->mother_email}}">
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label>Details of Brothers & Sisters</label>
                            </div>
                            <div class="form-group col-sm-8 col-8">
                                <div class="notification_box_container">
                                    <div  class="notification_head" style="padding-top: 5px; height: 30px;"> Name </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-4 col-4">
                                <div class="notification_box_container">
                                    <div style="padding-top: 5px; height: 30px;" class="notification_head">Age</div>
                                </div>
                            </div>
                            <div class="form-group col-sm-8 col-8">
                                <input class="form-control" name="sibling_1_name" id="sibling_1_name" type="text" placeholder="1." value="{{$student->sibling_1_name}}">
                            </div>
                            <div class="form-group col-sm-4 col-4">
                                <input class="form-control" name="sibling_1_age" id="sibling_1_age" type="text" placeholder="Age" value="{{$student->sibling_1_age}}">
                            </div>
                            <div class="form-group col-sm-8 col-8">
                                <input class="form-control" name="sibling_2_name" id="sibling_2_name" type="text" placeholder="2." value="{{$student->sibling_2_name}}">
                            </div>
                            <div class="form-group col-sm-4 col-4">
                                <input class="form-control" name="sibling_2_age" id="sibling_2_age" type="text" placeholder="Age" value="{{$student->sibling_2_age}}">
                            </div>
                            <div class="form-group col-md-12 col-sm-6 col-10">
                                <label class="col-auto">How do you know about Brainobrain ?</label>
                                <div class="col-auto">
                                    @for($i=0;$i<count($sourcess);$i++)
                                        <label class="source" for="{{$sourcess[$i]}}"><input type="radio" name="source" {{ $student->source==$i ? 'checked':'' }} id="{{$sourcess[$i]}}" value="{{$i}}"> {{$sourcess[$i]}}</label>&nbsp;&nbsp;&nbsp;
                                    @endfor
                                    <input type="text" name="comments" class="form-control col-lg-3 col-md-6 col-sm-8 col-12 form-control-sm" id="comments" value="{{ $student->comments }}" style="{{ $student->comments==null ? 'display:none':'' }}">
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-lg-12">
                                <label for="about_child">Please tell us about child</label>
                                <textarea name="about_child" id="about_child" class="form-control {{ $errors->has('about_child') ? 'is-invalid':'' }}" cols="30" rows="5">{{ $student->about_child }}</textarea>
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
        @if($student->path == '/images/')
        var default_path_value        =       '{{ asset('images/initial_profile_pic.jpg') }}';
        @else
        var default_path_value        =       '{{asset($student->path)}}';
        @endif
        $('#studentEditForm #imgPreview').attr('src',default_path_value);
        $('#studentProfileImageUpdateModal #imgPreview').attr('src', default_path_value);
    </script>

@endsection