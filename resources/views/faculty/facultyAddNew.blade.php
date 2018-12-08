<div class="result"></div>
<form method="POST" action="{{ route('faculty.store') }}" enctype="multipart/form-data" id="facultyRegisterForm">
@csrf
    <div class="form-row">
        <input type="hidden" name="button_action" value="add_new">
        <div class="form-group col-md-6 text-center">
            <img src="{{ asset('images/initial_profile_pic.jpg') }}" id="imgPreview" class="rounded-circle" alt="profile_image" width="220px" height="220px">
        </div>
        <div class="form-group col-md-6 text-center">
            <h5 class='text-muted pb-4 pt-3'>Profile picture upload</h5>
            <label class="btn-bs-file btn btn-xs btn-primary">
                <i class="fa fa-image"></i>&nbsp;&nbsp; Browse
                <input type="file" accept="image/*" name="path" id="path">
            </label>
            <strong><span class="invalid-feedback" id="path-error"></span></strong>
        </div>
        <div class="form-group col-md-6">
            <label for="name">Faculty Name</label>
            <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}" value="{{ old('name') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="father_name">Father Name</label>
            <input type="text" name="father_name" id="father_name" class="form-control {{ $errors->has('father_name') ? 'is-invalid':'' }}" value="{{ old('father_name') }}">
        </div>
        <div class="form-group col-md-12">
            <label for="permanent_address">Permanent Address</label>
            <textarea name="permanent_address" id="permanent_address" class="form-control {{ $errors->has('permanent_address') ? 'is-invalid':'' }}" rows="4">{{ old('permanent_address') }}</textarea>
        </div>
        <div class="form-group col-md-6">
            <label for="contact_no1">Contact number 1</label>
            <input type="text" name="contact_no1" id="contact_no1" class="form-control {{ $errors->has('contact_no1') ? 'is-invalid':'' }}" value="{{ old('contact_no1') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="contact_no2">Contact number 2</label>
            <input type="text" id="contact_no2" name="contact_no2" class="form-control {{ $errors->has('contact_no2') ? 'is-invalid':'' }}" value="{{ old('contact_no2') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="email">Email ID</label>
            <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? 'is-invalid':'' }}" value="{{ old('email') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="qualification">Qualification</label>
            <input type="text" name="qualification" id="qualification" class="form-control {{ $errors->has('qualification') ? 'is-invalid':'' }}" value="{{ old('qualification') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="occupation">Occupation</label>
            <input type="text" id="occupation" class="form-control {{ $errors->has('occupation') ? 'is-invalid':'' }}" name="occupation" value="{{ old('occupation') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="dob">Date of Birth</label>
            <input type="text" name="dob" readonly class="date form-control {{ $errors->has('dob') ? 'is-invalid':'' }}" id="dob" value="{{ old('dob') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="family_income">Family Income in Rs.</label>
            <input type="text" class="form-control {{ $errors->has('family_income') ? 'is-invalid':'' }}" id="family_income" name="family_income" value="{{ old('family_income') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="">Gender</label> <br>
            {{--<label><input type="radio" name="gender" value="Male" {{ old('gender') ? (old('gender') == 'Male'  ? 'checked' : '') : '' }}> Male</label>&nbsp;&nbsp;&nbsp;&nbsp;--}}
            {{--<label><input type="radio" name="gender" value="Female" {{ old('gender') ? (old('gender') == 'Female'  ? 'checked' : '') : '' }}> Female</label><br/>--}}
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" disabled value="Male" id="Male" {{old("gender")!=null?(old("gender")=="Male" ? 'checked':''):''}} name="gender" class="custom-control-input">
                <label class="custom-control-label" for="Male">Male</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" checked value="Female" id="Female" {{old("gender")!=null?(old("gender")=="Female" ? 'checked':''):''}} name="gender" class="custom-control-input">
                <label class="custom-control-label" for="Female">Female</label>
            </div>
        </div>
        <div class="form-group col-md-12">
            <label for="">Married</label> <br>
            <div class="custom-control custom-radio custom-control-inline">
                <input value="1" type="radio" {{ old('married')!=null ? (old('married') == 1  ? 'checked' : '') : 'checked' }} id="Yes" name="married" data-url="{{route("facultyMarried.index")}}" class="custom-control-input married-check">
                <label class="custom-control-label" for="Yes">Yes</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input value="0" type="radio" {{ old('married') ? (old('married') == 0  ? 'checked' : '') : 'checked' }} id="No" name="married" class="custom-control-input married-check">
                <label class="custom-control-label" for="No">No</label>
            </div>

            {{--<label for="">Married</label> <br>--}}
            {{--<label><input type="radio" data-url="{{route("facultyMarried.index")}}" name="married" value="1" class="married-check" required {{ old('married') == 1 ? 'checked' : '' }}> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}
            {{--<label><input type="radio" name="married" value="0" class="married-check" {{ old('married') == 0 ? 'checked' : '' }}> No</label>--}}
        </div>
        <div id="married-form" class="row">
        </div>
        <div class="form-group col-md-6">
            <label for="languages_known">Languages Known</label>
            <input type="text" name="languages_known" id="languages_known" class="form-control {{ $errors->has('languages_known') ? 'is-invalid':'' }}" value="{{ old('languages_known') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="hobbies">Hobbies</label>
            <input type="text" name="hobbies" id="hobbies" class="form-control {{ $errors->has('hobbies') ? 'is-invalid':'' }}" value="{{ old('hobbies') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="special_at">You are special at ?</label>
            <input type="text" name="special_at" id="special_at" class="form-control {{ $errors->has('special_at') ? 'is-invalid':'' }}" value="{{ old('special_at') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="past_experience">Past Experience</label>
            <input type="text" name="past_experience" id="past_experience" class="form-control {{ $errors->has('past_experience') ? 'is-invalid':'' }}" value="{{ old('past_experience') }}">
        </div>
        <div class="form-group col-md-6 col-lg-6 col-sm-12">
            <label for="franchisee_id">Franchisee Branch</label>
            <select name="franchisee_id" id="franchisee_id" class="form-control selectpicker {{ $errors->has('franchisee_id') ? 'is-invalid':'' }}">
                <option></option>
                @foreach($franchisee_obj as $franchisee)
                <option value="{{$franchisee->id}}" {{ old('franchisee_id')== $franchisee->id ? 'selected':''}}>{{ $franchisee->center_code }} - {{ $franchisee->franchisee_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="status">Status</label>
            <select name="status" class="form-control select-picker-no-search" id="status">
                <option></option>
                @for($i=count($faculty_status)-1;$i>=0;$i--)
                <option value="{{$i}}">{{$faculty_status[$i][0]}}</option>
                @endfor
            </select>
        </div>
    </div>
    <div class="form-group row mb-0">
        <div class="col-md-4 offset-md-4">
            <button type="submit" name="submit" value="addNew" class="btn btn-warning btn-block">
                {{ __('Register') }}
            </button>
        </div>
    </div>
</form>