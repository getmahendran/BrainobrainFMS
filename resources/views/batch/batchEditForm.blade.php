@if($button_action == 'students_list')
    <div class="table-block table-bordered" xmlns="http://www.w3.org/1999/html">
        @if(count($batch->level))
        <div class="table table-striped">
            <div class="tr text-center">
                <span class="th">Student No	</span>
                <span class="th">Name	</span>
                <span class="th">Course	</span>
                <span class="th">Start date	</span>
                <span class="th">End date</span>
                <span class="th">Marks</span>
                <span class="th"></span>
            </div>
            @foreach($batch->level as $item)
                <form action="{{route('student.update',$item->id)}}" class="tr update-student-progress row" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <span class="td">{{$item->student_no}}</span>
                    <span class="td">{{$item->student->name}}</span>
                    <span class="td">{{$item->course->program->program_name}} - {{$item->course->course_name}}</span>
                    <span class="td"><input type="text" autocomplete="off" value="{{$item->start_date}}" data-max-date="{{date('Y-m-d')}}" name="start_date" class="form-control form-control-sm date"></span>
                    <span class="td"><input type="text" {{$item->start_date==null ? "readonly":""}} autocomplete="off" value="{{$item->end_date}}" data-max-date="{{date('Y-m-d')}}" name="end_date" class="form-control form-control-sm {{$item->start_date==null ? "":"date"}}"></span>
                    <span class="td"><input type="text" {{$item->start_date==null||$item->end_date==null ? "readonly":""}} value="{{$item->marks}}" name="marks" class="form-control form-control-sm"></span>
                    <input type="hidden" name="button_action" value="update_student_progress">
                    <span class="td"><button type="submit" class="table-button table-button-info">Save</button></span>
                </form>
            @endforeach
        </div>
        @else
            <div class="text-center text-muted">No records found</div>
        @endif
    </div>
@elseif($button_action == 'student_promotion')
    <div class="table-block table-striped table-bordered">
        @if(count($batch->level))
        <div class="table">
            <div class="tr text-center">
                <span class="th">Student No	</span>
                <span class="th">Name	</span>
                <span class="th">Course	</span>
                <span class="th">Promote	</span>
            </div>
            @foreach($batch->level as $item)
            <form class="tr">
                <span class="td">{{$item->student_no}}</span>
                <span class="td">{{$item->student->name}}</span>
                <span class="td">{{$item->course->program->program_name}} - {{$item->course->course_name}}</span>
                <span class="td text-center">
                    @if($item->status == 3)
                        <a  href="{{route('student.edit',$item->id)}}" class="text-success promote-student disabled"><i class="fa fa-arrow-circle-up"></i></a>
                    @else
                        <a data-url="{{route('batch.edit',$batch->id)}}?franchisee_id={{$batch->franchisee_id}}&button_action=students_list" href="javascript:void(0)" class="batch-task-tab"><span class="badge badge-danger">Update end date</span></a>
                    @endif
                </span>
            </form>
            @endforeach
        </div>
        @else
            <div class="text-center text-muted">No records found</div>
        @endif
    </div>
@elseif($button_action == 'batch_transfer')
    <div class="table-block table-striped table-bordered">
        @if(count($batch->level))
            <div class="table">
                <div class="tr text-center">
                    <span class="th">Student No	</span>
                    <span class="th">Name	</span>
                    <span class="th">Course	</span>
                    <span class="th">Transfer	</span>
                </div>
                @foreach($batch->level as $item)
                    <form class="tr">
                        <span class="td">{{$item->student_no}}</span>
                        <span class="td">{{$item->student->name}}</span>
                        <span class="td">{{$item->course->program->program_name}} - {{$item->course->course_name}}</span>
                        <span class="td text-center"><a href="" class="text-info "><i class="fa fa-exchange-alt"></i></a></span>
                    </form>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted">No records found</div>
        @endif
    </div>
@else
    <div class="text-center">404 NOT FOUND</div><br>
@endif