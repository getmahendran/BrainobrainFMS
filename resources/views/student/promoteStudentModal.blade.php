<!-- Modal -->
<div class="modal fade" id="promoteStudentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are you sure ?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="result"></div>
                <form id="promoteStudentForm" method="POST" action="{{route('student.update',$student->id)}}">
                    @csrf
                    <div class="table block">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th>Student No</th>
                                <td>{{$student->student_no}}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{$student->student->name}}</td>
                            </tr>
                            <tr>
                                <th>Current course</th>
                                <td>{{$student->course->program->program_name}} - {{$student->course->course_name}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" value="promote_student" name="button_action">
                    <span class="font-weight-bold">Want to promote student to the selected course</span>
                    @if($next_course    !=  null)
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input value="{{$next_course->id}}" type="radio" id="{{$next_course->id}}" name="customRadio" class="custom-control-input">
                            <label class="custom-control-label" for="{{$next_course->id}}">{{$next_course->program->program_name}} - {{$next_course->course_name}}</label>
                        </div>
                        @foreach($next_course->course_branches as $value)
                        <div class="custom-control custom-radio">
                            <input value="{{$value->branch_course_id}}" type="radio" id="{{$value->branch_course_id}}" name="customRadio" class="custom-control-input">
                            <label class="custom-control-label" for="{{$value->branch_course_id}}">{{$value->course->program->program_name}} - {{$value->course->course_name}}</label>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success">Promote</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Model Ends here-->