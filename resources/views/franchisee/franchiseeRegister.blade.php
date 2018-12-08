@extends('layouts.bobkar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-10 p-2 col-sm-12">
            <div class="card">
                <div class="card-header text-uppercase">{{ __('New franchisee registration') }}</div>
                <div class="card-body">
                    @if(session()->has('message'))
                    <div class="alert alert-success text-center"><strong><i class="fa fa-check-circle"></i> {{ session()->get('message') }}</strong></div>
                    @endif
                    <form method="POST"  action="{{ route('franchisee.store') }}" enctype="multipart/form-data" id="franchiseeRegisterForm">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 text-center">
                                <img src="{{ asset('images/initial_profile_pic.jpg') }}" id="imgPreview" class="rounded-circle" alt="profile_image" width="220px" height="220px">
                                <div class="text-danger"></div>
                            </div>
                            <div class="form-group col-md-6 text-center">
                                <h5 class='pb-4 pt-3'>Profile picture upload</h5>
                                <label class="btn-bs-file btn btn-xs btn-primary">
                                    <i class="fa fa-image"></i>&nbsp;&nbsp; Browse
                                    <input type="file" accept="image/*" name="path" id="path">
                                </label>
                                @if($errors->has('path'))
                                <strong><span class="invalid-feedback">{{ $errors->first('path') }}</span></strong>
                                @endif
                                <strong><span class="invalid-feedback" id="path-error"></span></strong>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}">
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="father_name">Father Name</label>
                                <input type="text" name="father_name" id="father_name" value="{{ old('father_name') }}" class="form-control {{ $errors->has('father_name') ? ' is-invalid' : '' }}">
                                @if ($errors->has('father_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('father_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                                <label for="permanent_address">Permanent Address</label>
                                <textarea name="permanent_address" id="permanent_address" class="form-control {{ $errors->has('permanent_address') ? ' is-invalid' : '' }}" cols="15" rows="4">{{ old('permanent_address') }}</textarea>
                                @if ($errors->has('permanent_address'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('permanent_address') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="contact_no1">Contact number 1</label>
                                <input type="text" value="{{ old('contact_no1') }}" name="contact_no1" id="contact_no1" class="form-control {{ $errors->has('contact_no1') ? 'is-invalid' : '' }}">
                                @if ($errors->has('contact_no1'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contact_no1') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="contact_no2">Contact number 2</label>
                                <input type="text" id="contact_no2" value="{{ old('contact_no2') }}" name="contact_no2" class="form-control {{ $errors->has('contact_no2') ? 'is-invalid' : '' }}">
                                @if($errors->has('contact_no2'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contact_no2') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Email ID</label>
                                <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}">
                                @if($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="qualification">Qualification</label>
                                <input type="text" name="qualification" value="{{ old('qualification') }}" id="qualification" class="form-control {{ $errors->has('qualification') ? 'is-invalid' : '' }}">
                                @if($errors->has('qualification'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('qualification') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="occupation">Occupation</label>
                                <input value="{{ old('occupation') }}" type="text" id="occupation" class="form-control {{ $errors->has('occupation') ? 'is-invalid' : '' }}" name="occupation">
                                @if($errors->has('occupation'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('occupation') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dob">Date of Birth</label>
                                <input readonly type="text" data-max-date="{{date("Y-m-d")}}" value="{{ old('dob') }}" name="dob" class="date form-control {{ $errors->has('dob') ? 'is-invalid' : '' }}" id="dob">
                                @if($errors->has('dob'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('dob') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="family_income">Family Income in Rs.</label>
                                <input type="text" value="{{ old('family_income') }}" class="form-control {{ $errors->has('family_income') ? 'is-invalid' : '' }}" id="family_income" name="family_income">
                                @if($errors->has('family_income'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('family_income') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Gender</label> <br>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" value="Male" id="Male" {{old("gender")!=null?(old("gender")=="Male" ? 'checked':''):''}} name="gender" class="custom-control-input">
                                    <label class="custom-control-label" for="Male">Male</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" value="Female" id="Female" {{old("gender")!=null?(old("gender")=="Female" ? 'checked':''):''}} name="gender" class="custom-control-input">
                                    <label class="custom-control-label" for="Female">Female</label>
                                </div>
                                @if($errors->has('gender'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('gender') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">Married</label> <br>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input value="1" type="radio" {{ old('married')!=null ? (old('married') == 1  ? 'checked' : '') : 'checked' }} id="Yes" name="married" data-url="{{route("franchiseeMarried.index")}}" class="custom-control-input married-check">
                                    <label class="custom-control-label" for="Yes">Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input value="0" type="radio" {{ old('married') ? (old('married') == 0  ? 'checked' : '') : 'checked' }} id="No" name="married" class="custom-control-input married-check">
                                    <label class="custom-control-label" for="No">No</label>
                                </div>
                                @if($errors->has('married'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('married') }}</strong></span>
                                @endif
                            </div>
                            <div id="married-form" class="form-row">
                                @if(old('married') == 1)
                                    @include('married')
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="languages_known">Languages Known</label>
                                <input type="text" value="{{ old('languages_known') }}" name="languages_known" id="languages_known" class="form-control {{ $errors->has('languages_known') ? 'is-invalid' : '' }}">
                                @if($errors->has('languages_known'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('languages_known') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="hobbies">Hobbies</label>
                                <input type="text" value="{{ old('hobbies') }}" name="hobbies" id="hobbies" class="form-control {{ $errors->has('hobbies') ? 'is-invalid' : '' }}">
                                @if($errors->has('hobbies'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('hobbies') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="special_at">You are special at ?</label>
                                <input type="text" value="{{ old('special_at') }}" name="special_at" id="special_at" class="form-control {{ $errors->has('special_at') ? 'is-invalid' : '' }}">
                                @if($errors->has('special_at'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('special_at') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="past_experience">Past Experience</label>
                                <input type="text" value="{{ old('past_experience') }}" name="past_experience" id="past_experience" class="form-control {{ $errors->has('past_experience') ? 'is-invalid' : '' }}">
                                @if($errors->has('past_experience'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('past_experience') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="franchisee_name">Franchisee Name</label>
                                <input type="text" value="{{ old('franchisee_name') }}" name="franchisee_name" id="franchisee_name" class="form-control {{ $errors->has('franchisee_name') ? 'is-invalid' : '' }}">
                                @if($errors->has('franchisee_name'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('franchisee_name') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="area_id">Rural/Urban</label>
                                <select class="form-control select-picker-no-search {{ $errors->has('area_id') ? 'is-invalid' : '' }}" name="area_id" id="area_id">
                                    <option></option>
                                    @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('area_id'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('area_id') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="franchisee_address">Franchisee Full Address</label>
                                <textarea id="franchisee_address" name="franchisee_address" class="form-control {{ $errors->has('franchisee_address') ? 'is-invalid' : '' }}" rows="4">{{ old('franchisee_address') }}</textarea>
                                @if($errors->has('franchisee_address'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('franchisee_address') }}</strong></span>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status">Status</label>
                                <select class="form-control select-picker-no-search {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                                    <option></option>
                                    @for($i=count($franchisee_status)-1; $i>=0;$i--)
                                        <option {{old('status')!=null ?(old('status')== $i ? 'selected':'') : ''}} value="{{$i}}">{{$franchisee_status[$i][0]}}</option>
                                    @endfor
                                </select>
                                @if($errors->has('status'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('status') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-4">
                                <button type="submit" class="btn btn-block btn-warning">
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

@section('script')
    <script>
        var default_path_value        =       '{{ asset('images/initial_profile_pic.jpg') }}';

        /*JS to check change in status in radio button married Yes or No*/
            $('#franchiseeRegisterForm .married-check').change(function () {
            if($(this).val() == 1)
                marriedDisplay($(this).data("url"));
            else
                $('#married-form').html('');
        });/*Ends here*/
    </script>
@endsection