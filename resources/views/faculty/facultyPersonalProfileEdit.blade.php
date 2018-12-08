<form method="POST" action="{{ route('faculty.update', $facultyAccount->id) }}" enctype="multipart/form-data" id="facultyEditForm">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="name">Faculty Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $faculty->name }}">
        </div>
        <div class="form-group col-md-6">
            <label for="father_name">Father Name</label>
            <input type="text" name="father_name" id="father_name" class="form-control" value="{{ $faculty->father_name }}">
        </div>
        <div class="form-group col-md-12">
            <label for="permanent_address">Permanent Address</label>
            <textarea name="permanent_address" id="permanent_address" class="form-control" rows="4">{{ $faculty->permanent_address }}</textarea>
        </div>
        <div class="form-group col-md-6">
            <label for="contact_no1">Contact number 1</label>
            <input type="text" name="contact_no1" id="contact_no1" class="form-control" value="{{ $faculty->contact_no1 }}">
        </div>
        <div class="form-group col-md-6">
            <label for="contact_no2">Contact number 2</label>
            <input type="text" id="contact_no2" name="contact_no2" class="form-control" value="{{ $faculty->contact_no2 }}">
        </div>
        <div class="form-group col-md-6">
            <label for="email">Email ID</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $faculty->email }}">
        </div>
        <div class="form-group col-md-6">
            <label for="qualification">Qualification</label>
            <input type="text" name="qualification" id="qualification" class="form-control" value="{{ $faculty->qualification }}">
        </div>
        <div class="form-group col-md-6">
            <label for="occupation">Occupation</label>
            <input type="text" id="occupation" class="form-control" name="occupation" value="{{ $faculty->occupation }}">
        </div>
        <div class="form-group col-md-6">
            <label for="dob">Date of Birth</label>
            <input type="text" name="dob" class="form-control date" readonly id="dob" value="{{ $faculty->dob }}">
        </div>
        <div class="form-group col-md-6">
            <label for="family_income">Family Income in Rs.</label>
            <input type="text" class="form-control" id="family_income" name="family_income" value="{{ $faculty->family_income }}">
        </div>
        <div class="form-group col-md-6">
            <label for="">Gender</label> <br>
            {{--<label><input type="radio" name="gender" value="Male" disabled> Male</label>&nbsp;&nbsp;&nbsp;&nbsp;--}}
            {{--<label><input type="radio" name="gender" value="Female" checked> Female</label><br/>--}}
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" value="Male" disabled id="Male" name="gender" class="custom-control-input">
                <label class="custom-control-label" for="Male">Male</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" value="Female" checked id="Female" name="gender" class="custom-control-input">
                <label class="custom-control-label" for="Female">Female</label>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="">Married</label> <br>
            <div class="custom-control custom-radio custom-control-inline">
                <input value="1" type="radio" id="Yes" name="married"  {{ $faculty->married == 1 ? 'checked' : '' }} data-url="{{route("facultyMarried.edit",$faculty->id)}}" class="custom-control-input married-check">
                <label class="custom-control-label" for="Yes">Yes</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input value="0" type="radio" id="No" name="married" class="custom-control-input married-check" {{ $faculty->married == 0 ? 'checked' : '' }}>
                <label class="custom-control-label" for="No">No</label>
            </div>
        </div>

        <div id="married-form" class="form-row">
            @if($faculty->married == 1)
                @include('married')
            @endif
        </div>
        <div class="form-group col-md-6">
            <label for="languages_known">Languages Known</label>
            <input type="text" name="languages_known" id="languages_known" class="form-control" value="{{ $faculty->languages_known }}">
        </div>
        <div class="form-group col-md-6">
            <label for="hobbies">Hobbies</label>
            <input type="text" name="hobbies" id="hobbies" class="form-control" value="{{ $faculty->hobbies }}">
        </div>
        <div class="form-group col-md-6">
            <label for="special_at">You are special at ?</label>
            <input type="text" name="special_at" id="special_at" class="form-control" value="{{ $faculty->special_at }}">
        </div>
        <div class="form-group col-md-6">
            <label for="past_experience">Past Experience</label>
            <input type="text" name="past_experience" id="past_experience" class="form-control" value="{{ $faculty->past_experience }}">
        </div>
        <div class="form-group col-md-6">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control select-picker-no-search">
                <option></option>
                @for($i=0;$i<count($status);$i++)
                    <option {{$i==$facultyAccount->status ? "selected":""}} value="{{$i}}">{{$status[$i][0]}}</option>
                @endfor
            </select>
        </div>
    </div>
    <div class="form-group row mb-0">
        <div class="col-md-4 offset-md-4">
            <button type="submit" name="submit" value="addNew" class="btn btn-warning btn-block">
                {{ __('Save Changes') }}
            </button>
        </div>
    </div>
</form>