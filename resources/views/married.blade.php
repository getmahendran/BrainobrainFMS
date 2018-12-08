<div class="form-group col-md-6 married">
    <label for="spouse_name">Spouse Name</label>
    <input type="text" value="{{ old('spouse_name') }}{{ isset($franchisee->spouse_name) ? $franchisee->spouse_name : ''}}{{ isset($faculty->spouse_name) ? $faculty->spouse_name : ''}}" class="form-control {{ $errors->has('spouse_name') ? 'is-invalid' : '' }}" id="spouse_name" name="spouse_name">
    @if($errors->has('spouse_name'))
        <span class="invalid-feedback"><strong>{{ $errors->first('spouse_name') }}</strong></span>
    @endif
</div>
<div class="form-group col-md-6 married">
    <label for="input_spouse_dob">Spouse Date of Birth</label>
    <input type="text" value="{{ isset($franchisee->spouse_dob) ? $franchisee->spouse_dob : ''}}{{ isset($faculty->spouse_dob) ? $faculty->spouse_dob : ''}}{{ old('spouse_name') }}" readonly class="date form-control {{ $errors->has('spouse_dob') ? 'is-invalid' : '' }}" name="spouse_dob" id="input_spouse_dob">
    @if($errors->has('spouse_dob'))
        <span class="invalid-feedback"><strong>{{ $errors->first('spouse_dob') }}</strong></span>
    @endif
</div>
<div class="form-group col-md-6 married">
    <label for="spouse_occupation">Spouse Present Occupation</label>
    <input type="text" value="{{ old('spouse_occupation') }}{{ isset($faculty->spouse_occupation) ? $faculty->spouse_occupation : ''}}" class="form-control {{ $errors->has('spouse_occupation') ? 'is-invalid' : '' }}" name="spouse_occupation" id="spouse_occupation">
    @if($errors->has('spouse_occupation'))
        <span class="invalid-feedback"><strong>{{ $errors->first('spouse_occupation') }}</strong></span>
    @endif
</div>
<div class="form-group col-md-6 married">
    <label for="spouse_qualification">Spouse Qualification</label>
    <input type="text" value="{{ old('spouse_qualification') }}{{ isset($faculty->spouse_qualification) ? $faculty->spouse_qualification : ''}}" class="form-control {{ $errors->has('spouse_qualification') ? 'is-invalid' : '' }}" name="spouse_qualification" id="spouse_qualification">
    @if($errors->has('spouse_qualification'))
        <span class="invalid-feedback"><strong>{{ $errors->first('spouse_qualification') }}</strong></span>
    @endif
</div>
<div class="form-group col-md-6 married">
    <label for="input_anniversary">Wedding Anniversary</label>
    <input type="text" readonly data-max-date="{{date("Y-m-d")}}" value="{{ old('wedding_anniversary') }}{{ isset($franchisee->wedding_anniversary) ? $franchisee->wedding_anniversary : ''}}{{ isset($faculty->wedding_anniversary) ? $faculty->wedding_anniversary : ''}}" class="form-control {{ $errors->has('wedding_anniversary') ? 'is-invalid' : '' }} date" name="wedding_anniversary" id="input_anniversary">
    @if($errors->has('wedding_anniversary'))
        <span class="invalid-feedback"><strong>{{ $errors->first('wedding_anniversary') }}</strong></span>
    @endif
</div>
<div class="form-group col-md-6 married">
</div>
<div class="form-group col-md-6 married">
    <label for="child1_name">Child 1 Name</label>
    <input type="text" value="{{ old('child1_name') }}{{ isset($franchisee->child1_name) ? $franchisee->child1_name : ''}}{{ isset($faculty->child1_name) ? $faculty->child1_name : ''}}" class="form-control {{ $errors->has('child1_name') ? 'is-invalid' : '' }}" name="child1_name" id="child1_name">
    @if($errors->has('child1_name'))
        <span class="invalid-feedback"><strong>{{ $errors->first('child1_name') }}</strong></span>
    @endif
</div>
<div class="form-group col-md-6 married">
    <label for="child1_dob">Child 1 Date of Birth</label>
    <input type="text" readonly data-max-date="{{date("Y-m-d")}}" value="{{ old('child1_dob') }}{{ isset($franchisee->child1_dob) ? $franchisee->child1_dob : ''}}{{ isset($faculty->child1_dob) ? $faculty->child1_dob : ''}}" class="date form-control {{ $errors->has('child1_dob') ? 'is-invalid' : '' }}" name="child1_dob" id="child1_dob">
    @if($errors->has('child1_name'))
        <span class="invalid-feedback"><strong>{{ $errors->first('child1_name') }}</strong></span>
    @endif
</div>
<div class="form-group col-md-6 married">
    <label for="child2_name">Child 2 Name</label>
    <input type="text" value="{{ old('child2_name') }}{{ isset($franchisee->child2_name) ? $franchisee->child2_name : ''}}{{ isset($faculty->child2_name) ? $faculty->child2_name : ''}}" class="form-control {{ $errors->has('child2_name') ? 'is-invalid' : '' }}" name="child2_name" id="child2_name">
    @if($errors->has('child2_name'))
        <span class="invalid-feedback"><strong>{{ $errors->first('child2_name') }}</strong></span>
    @endif
</div>
<div class="form-group col-md-6 married">
    <label for="child2_dob">Child 2 Date of Birth</label>
    <input type="text" readonly data-max-date="{{date("Y-m-d")}}" value="{{ old('child2_dob') }}{{ isset($franchisee->child2_dob) ? $franchisee->child2_dob : ''}}{{ isset($faculty->child2_dob) ? $faculty->child2_dob : ''}}" class="form-control {{ $errors->has('child2_dob') ? 'is-invalid' : '' }} date" name="child2_dob" id="child2_dob">
    @if($errors->has('child2_dob'))
        <span class="invalid-feedback"><strong>{{ $errors->first('child2_dob') }}</strong></span>
    @endif
</div>
<div class="form-group col-md-6 married">
    <label for="child3_name">Child 3 Name</label>
    <input type="text" value="{{ old('child3_name') }}{{ isset($franchisee->child3_name) ? $franchisee->child3_name : ''}}{{ isset($faculty->child3_name) ? $faculty->child3_name : ''}}" class="form-control {{ $errors->has('child3_name') ? 'is-invalid' : '' }}" id="child3_name" name="child3_name">
    @if($errors->has('child3_name'))
        <span class="invalid-feedback"><strong>{{ $errors->first('child3_name') }}</strong></span>
    @endif
</div>
<div class="form-group col-md-6 married">
    <label for="child3_dob">Child 3 Date of Birth</label>
    <input type="text" readonly data-max-date="{{date("Y-m-d")}}" class="form-control date {{ $errors->has('child3_dob') ? 'is-invalid' : '' }}" name="child3_dob" id="child3_dob" value="{{ old('child3_dob') }}{{ isset($franchisee->child3_dob) ? $franchisee->child3_dob : ''}}{{ isset($faculty->child3_dob) ? $faculty->child3_dob : ''}}">
    @if($errors->has('child3_dob'))
        <span class="invalid-feedback"><strong>{{ $errors->first('child3_dob') }}</strong></span>
    @endif
</div>