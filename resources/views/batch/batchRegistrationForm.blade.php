@if(isset($franchisees))
    <div class="form-group row justify-content-center" id="batch-create-franchisee-select">
        <div class="col-lg-3 col-md-5 pt-lg-2">
            <label for="franchisee_id" class="">Franchisee Branch :-</label>
        </div>
        <div class="col-lg-6 col-md-6 float-left">
            <select name="franchisee_id" id="franchisee_id" class="form-control selectpicker">
                <option></option>
                @foreach($franchisees as $franchisee)
                    <option value="{{route('batch.create',['franchisee_id'=>$franchisee->id])}}">{{ $franchisee->center_code }} - {{ $franchisee->franchisee_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif
@if(isset($button_action))
    <div class="col-auto mb-2 p-0">
        @if($button_action == "add_students")
            <a href="{{route('batch.create',['franchisee_id'=>request('franchisee_id')])}}" class="text-muted" id="batch-create-back-btn"><i class="fa fa-arrow-left fa-2x"></i></a>
        @else
            <a href="{{route('batch.create')}}" class="text-muted" id="batch-create-back-btn"><i class="fa fa-arrow-left fa-2x"></i></a>
        @endif
        @if($button_action == "add_batch")
        <a href="{{route('batch.create',['franchisee_id'=>request('franchisee_id'), 'button_action'=>'add_students'])}}" class="nounderline float-right" id="batch-create-add-students"><i class="fa fa-external-link-alt"></i> Add Students to batch</a>
        @endif
    </div>
    @if($button_action == "add_students")
        @if(count($batches) == 0)
            <div class="text-center text-muted">No batches found :)<br> Go back and create batches to continue.</div>
        @elseif(count($students) == 0)
            <div class="text-center text-muted">No students found in the selected franchisee, All students should belong to some batch :)<br> Please try again later.</div>
        @else
            <form action="{{route('batch.store')}}" method="POST" id="batchCreateForm">
            @csrf
            <input type="hidden" name="franchisee_id" value="{{request('franchisee_id')}}">
            <div class="form-group row">
                <label for="batch_id" class="col-4 pt-2 text-md-right">Select Batch :-</label>
                <div class="col-6">
                    <select name="batch_id" id="batch_id" class="form-control select-picker-no-search">
                        <option value=""></option>
                        @foreach($batches as $batch)
                            <option value="{{$batch->id}}">{{$batch->batch_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class=" table-responsive table-hover table-block batch-students col-12 pt-1">
                <table class="table table-bordered">
                    <thead>
                    <tr class="text-center">
                        <th></th>
                        <th>Student no</th>
                        <th>Name</th>
                        <th>Program</th>
                        <th>Course</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td class="text-center"><input type="checkbox" name="level_id[]" value="{{$student['level_id']}}"></td>
                            <td>{{$student['student_no']}}</td>
                            <td>{{$student['student_name']}}</td>
                            <td>{{$student['program_name']}}</td>
                            <td>{{$student['course_name']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="form-group col-12">
                <div class="text-center offset-md-4 offset-sm-2 col-md-4 col-sm-8 col-12 batch-students"><button class="btn btn-warning btn-block align-content-center">Add to batch</button></div>
            </div>
        </form>
        @endif
    @endif
    @if($button_action == "add_batch")
        <form action="{{route('batch.store')}}" method="POST" id="newBatchForm">
            @csrf
            <input type="hidden" name="franchisee_id" value="{{request('franchisee_id')}}">
            <div class="form-group row">
                <label for="faculty_account_id" class="col-md-4 col-form-label text-md-right">Faculty</label>
                <div class="col-md-6">
                    <select name="faculty_account_id" id="faculty_account_id" class="form-control selectpicker">
                        <option></option>
                        @if(count($faculties))
                            @foreach($faculties as $faculty)
                            <option value="{{$faculty->id}}">{{$faculty->faculty_code}} - {{$faculty->faculty->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="batch_name" class="col-md-4 col-form-label text-md-right">{{__('Batch name')}}</label>
                <div class="col-md-6">
                    <input type="text" id="batch_name" name="batch_name" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4"></div>
                <div class="col-md-6">
                    <button class="btn btn-warning btn-block">Submit</button>
                </div>
            </div>
        </form>
    @endif
@endif