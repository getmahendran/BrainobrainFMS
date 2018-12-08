{{--@extends('layouts.bobkar')--}}

{{--@section('content')--}}
{{--<div class="card">--}}
    {{--<div class="text-uppercase card-header">Faculty Details</div>--}}
    {{--<div class="card-body">--}}
        {{--<div class="row">--}}
            <div class="col-12 text-center mb-2 mt-2"><h3>Faculty Details</h3></div>
            <div class="col-12"><hr></div>
            <div class="col-12 col-lg-3 offset-lg-1 text-center">
                @if($faculty_obj->path == '/images/')
                    <img src="{{ asset('images/initial_profile_pic.jpg') }}" class="rounded-circle" alt="" width="250px" height="250px">
                @else
                    <img src="{{ asset($faculty_obj->path) }}" class="rounded-circle" alt="" width="250px" height="250px">
                @endif
            </div>
            <div class="col-lg-7 col-12 offset-lg-1">
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
                    @if($faculty_obj->married)
                    <li class="nav-item">
                        <a data-toggle="tab" class="nav-link" href="#menu4"><i class="fa fa-info"></i> Marrital Info</a>
                    </li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div id="home" class="tab-pane show fade in active">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr><th class="text-muted" width="30%">Full Name</th><td>{{ $faculty_obj->name }}</td></tr>
                                <tr><th class="text-muted">Date of Birth</th><td>{{ Carbon\Carbon::parse($faculty_obj->dob)->format('d/m/Y') }}</td></tr>
                                <tr><th class="text-muted">Qualification</th><td>{{ $faculty_obj->qualification }}</td></tr>
                                <tr><th class="text-muted">Training Details</th><td>Not available</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="menu1" class="tab-pane fade">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr><th width="30%" class="text-muted">Contact number 1</th><td>{{ $faculty_obj->contact_no1 }}</td></tr>
                                <tr><th class="text-muted">Contact number 2</th><td>{{ $faculty_obj->contact_no2 }}</td></tr>
                                <tr><th class="text-muted">Email</th><td>{{ $faculty_obj->email }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="menu2" class="tab-pane fade">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr><th class="text-muted" width="30%">Permanent Address</th><td>{{ $faculty_obj->permanent_address }}</td></tr>
                                <tr><th class="text-muted">State</th><td>Karnataka</td></tr>
                                <tr><th class="text-muted">Country</th><td>India</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="menu3" class="tab-pane fade">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr><th class="text-muted" width="30%">Father Name</th><td>{{ $faculty_obj->father_name }}</td></tr>
                                <tr><th class="text-muted">Languages Known</th><td>{{ $faculty_obj->languages_known }}</td></tr>
                                <tr><th class="text-muted">Hobbies</th><td>{{ $faculty_obj->hobbies }}</td></tr>
                                <tr><th class="text-muted">Special at</th><td>{{ $faculty_obj->special_at }}</td></tr>
                                <tr><th class="text-muted">Past Experiance</th><td>{{ $faculty_obj->past_experiance }}</td></tr>
                                <tr><th class="text-muted">Family income</th><td><i class="fa fa-rupee-sign"></i> {{ $faculty_obj->family_income }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="menu4" class="tab-pane fade">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr><th class="text-muted">Spouse name</th><td>{{ $faculty_obj->spouse_name }}</td></tr>
                                <tr><th class="text-muted">Spouse Date of birth</th><td>{{  Carbon\Carbon::parse($faculty_obj->spouse_dob)->format('d/m/Y') }}</td></tr>
                                <tr><th class="text-muted">Spouse Occupation</th><td>{{ $faculty_obj->spouse_occupation }}</td></tr>
                                <tr><th class="text-muted">Spouse Qualification</th><td>{{ $faculty_obj->spouse_qualification }}</td></tr>
                                <tr><th class="text-muted">Wedding Anniversary</th><td>{{ $faculty_obj->wedding_anniversary }}</td></tr>
                                <tr><th class="text-muted">Child 1 Name</th><td>{{ $faculty_obj->child1_name }}</td></tr>
                                <tr><th class="text-muted">Child 1 Date of birth</th><td>{{ $faculty_obj->child1_dob }}</td></tr>
                                <tr><th class="text-muted">Child 2 Name</th><td>{{ $faculty_obj->child2_name }}</td></tr>
                                <tr><th class="text-muted">Child 2 Date of birth</th><td>{{ $faculty_obj->child2_dob }}</td></tr>
                                <tr><th class="text-muted">Child 3 Name</th><td>{{ $faculty_obj->child3_name }}</td></tr>
                                <tr><th class="text-muted">Child 3 Date of birth</th><td>{{ $faculty_obj->child3_dob }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="offset-7 col-4">
                <button class="btn btn-block btn-secondary" formaction="{{ route('faculty.existing.store',$faculty_obj->id) }}" id="existingFacultyRegisterButton" type="submit">Register Faculty</button>
            </div>
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
{{--@endsection--}}