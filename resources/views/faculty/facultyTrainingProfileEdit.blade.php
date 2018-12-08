<div class="row">
    <div class="col-12">
        <a href="javascript:void(0)" data-toggle="modal"class="btn btn-primary float-right update-faculty-training-details"><i class="fa fa-plus"></i> Add Course</a>
    </div>
    @foreach($output as $item)
    <div class="col-6">
        <div class="bill-book-container">
            <div class="bill-book-head">{{$item["program"]->program_name}}</div>
            <div class="bil-book-body pb-0">
                <div class="table-block">
                    <table class="table  table-bordered table-stripped">
                        <tr class="text-center">
                            <th>Course Name</th>
                            <th></th>
                        </tr>
                        @foreach($item["courses"] as $training)
                        <tr>
                            <td>{{$training->course->course_name}}</td>
                            @if($loop->last)
                                <td class="text-center"><a href="{{route('faculty.destroy',$training->id)}}" class="text-danger remove-training"><i class="fa fa-trash-alt"></i></a></td>
                            @endif
                        </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>