@if(!isset($faculty))
<div class="form-group row">
    <label for="faculty_account_id" class="col-md-4 col-lg-3 col-12">Existing Account ID :-</label>
    <div class="col-md-7 col-lg-6 col-12 col-sm-9">
        <select name="" data-url="{{route('faculty.search')}}" id="faculty_account_id" class="form-control select-picker-ajax">
            <option></option>
        </select>
    </div>
</div>
@else
    <div id="result"></div>
    <div class="row">
        <a href="javascript:void(0)" class="col-auto nounderline  text-muted" onclick="pageload()"><i class="fa fa-arrow-left fa-2x"></i></a>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-4 text-center">
            <img id="path" src="{{asset('/images/initial_profile_pic.jpg')}}" class="rounded-circle" alt="Cool" width="200px" height="200px">
        </div>
        <div class="col-md-12 mt-2 col-lg-8">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home"><i class="fa fa-indent"></i> Summary</a>
                </li>
                <li class="nav-item">
                    <a data-toggle="tab" class="nav-link" href="#menu1"><i class="fa fa-bookmark"></i> Contact Info</a>
                </li>
                <li class="nav-item">
                    <a data-toggle="tab" class="nav-link" href="#menu2"><i class="fa fa-home"></i> Address</a>
                </li>
                <li class="nav-item">
                    <a data-toggle="tab" class="nav-link" href="#menu3"><i class="fa fa-info-circle"></i> General Info</a>
                </li>
                @if($faculty->married == 1)
                    <li class="nav-item married">
                        <a data-toggle="tab" class="nav-link" href="#menu4"><i class="fa fa-info"></i> Marrital Info</a>
                    </li>
                @endif
            </ul>
            <div class="tab-content">
                <div id="home" class="tab-pane show fade in active">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr><th class=" " width="30%">Full Name</th><td id="name">{{$faculty->name}}</td></tr>
                            <tr><th class=" ">Date of Birth</th><td id="dob">{{$faculty->dob}}</td></tr>
                            <tr><th class=" ">Qualification</th><td id="qualification">{{$faculty->qualification}}</td></tr>
                            <tr><th class=" ">Training Details</th><td>Not available</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="menu1" class="tab-pane fade">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr><th width="30%" class=" ">Contact number 1</th><td id="contact_no1">{{$faculty->contact_no1}}</td></tr>
                            <tr><th class=" ">Contact number 2</th><td id="contact_no2">{{$faculty->contact_no2}}</td></tr>
                            <tr><th class=" ">Email</th><td id="email">{{$faculty->email}}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="menu2" class="tab-pane fade">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr><th class=" " width="30%">Permanent Address</th><td id="permanent_address">{{$faculty->permanent_address}}</td></tr>
                            <tr><th class=" ">State</th><td>Karnataka</td></tr>
                            <tr><th class=" ">Country</th><td>India</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="menu3" class="tab-pane fade">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr><th class=" " width="30%">Father Name</th><td id="father_name">{{$faculty->father_name}}</td></tr>
                            <tr><th class=" ">Languages Known</th><td id="languages_known">{{$faculty->languages_known}}</td></tr>
                            <tr><th class=" ">Hobbies</th><td id="hobbies">{{$faculty->hobbies}}</td></tr>
                            <tr><th class=" ">Special at</th><td id="special_at">{{$faculty->special_at}}</td></tr>
                            <tr><th class=" ">Past Experience</th><td id="past_experiance">{{$faculty->past_experience}}</td></tr>
                            <tr><th class=" ">Family income</th><td id="family_income"><i class="fa fa-rupee-sign"></i> {{$faculty->family_income}}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($faculty->married == 1)
                    <div id="menu4" class="tab-pane fade">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr><th class=" ">Spouse name</th><td id="spouse_name">{{$faculty->spouse_name}}</td></tr>
                                <tr><th class=" ">Spouse Date of birth</th><td id="spouse_dob">{{$faculty->spouse_dob}}</td></tr>
                                <tr><th class=" ">Spouse Occupation</th><td id="spouse_occupation">{{$faculty->spouse_occupation}}</td></tr>
                                <tr><th class=" ">Spouse Qualification</th><td id="spouse_qualification">{{$faculty->spouse_qualification}}</td></tr>
                                <tr><th class=" ">Wedding Anniversary</th><td id="wedding_anniversary">{{$faculty->wedding_anniversary}}</td></tr>
                                <tr><th class=" ">Child 1 Name</th><td id="child1_name">{{$faculty->child1_name}}</td></tr>
                                <tr><th class=" ">Child 1 Date of birth</th><td id="child1_dob">{{$faculty->child1_dob}}</td></tr>
                                <tr><th class=" ">Child 2 Name</th><td id="child2_name">{{$faculty->child2_name}}</td></tr>
                                <tr><th class=" ">Child 2 Date of birth</th><td id="child2_dob">{{$faculty->child2_dob}}</td></tr>
                                <tr><th class=" ">Child 3 Name</th><td id="child3_name">{{$faculty->child3_name}}</td></tr>
                                <tr><th class=" ">Child 3 Date of birth</th><td id="child3_dob">{{$faculty->child3_dob}}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <hr>
    <form id="existingFacultyRegisterForm" method="POST" action="{{route('faculty.existing.store',$faculty->id)}}">
        @csrf
        <div class="form-group row justify-content-center">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <label for="franchisee_id">Select Franchisee Branch :-</label>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <select class="form-control selectpicker" name="franchisee_id" id="franchisee_id">
                    <option></option>
                    @foreach($franchisees as $franchisee)
                        <option value="{{$franchisee->id}}">{{$franchisee->center_code}} - {{$franchisee->franchisee_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-lg-4 offset-lg-4 offset-md-2 col-md-8 justify-content-center">
            <button class="btn btn-block btn-secondary" id="existingFacultyRegisterButton" type="submit">Register Faculty</button>
        </div>
    </form>
@endif
<!--Model Ends here-->



