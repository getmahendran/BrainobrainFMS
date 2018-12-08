    <div class="table-block">
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th width="50%" class="font-weight-bold">Student No</th>
                <td>{{$level->student_no}}</td>
            </tr>
            <tr>
                <th width="50%" class="font-weight-bold">Student Name</th>
                <td>{{$level->student->name}}</td>
            </tr>
            <tr>
                <th width="50%" class="font-weight-bold">Course</th>
                <td>{{$level->course->program->program_name}} - {{$level->course->course_name}}</td>
            </tr>
            <tr>
                <th width="50%" class="font-weight-bold">Duration</th>
                <td>{{$level->course->duration}} months</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="expandable-toggle-container" id="all-records">
        <div class="expandable-toggle-head {{isset($stage)?($stage==1?'active':''):''}}">All Fees <i class="fa {{isset($stage)?($stage==1?'fa-minus-circle':'fa-plus-circle'):'fa-plus-circle'}} "></i></div>
        <div class="expandable-toggle-body">
            <div class="bill-book-container">
                <div class="bill-book-head">Monthly Fee</div>
                <div class="bill-book-body table-block p-0">
                    <table class="table table-striped table-bordered fee-details">
                        <thead>
                        <tr class="text-center">
                            <th width="55%">Fee Description</th>
                            <th width="20%">Receipt no</th>
                            <th width="10%">Details</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($monthly_fees)!=0)
                            @foreach($monthly_fees as $monthly_fee)
                                @if($monthly_fee->status==0||$monthly_fee->status==1||$monthly_fee->status==4)
                                    <tr @if($monthly_fee->status==4) class="text-danger" @endif>
                                        <td>{{$monthly_fee->fee_description}}</td>
                                        <td>{{$monthly_fee->physical_receipt_no!=null?$monthly_fee->physical_receipt_no:'NA'}}</td>
                                        <td class="text-center"><a title="Details" href="{{route('fee_collect.show',$monthly_fee->id)}}" class="text-info view-details"><i class="fa fa-1x fa-info-circle"></i></a></td>
                                        @if($monthly_fee->status==0||$monthly_fee->status==4)
                                            <td class="text-center"><a href="{{route('fee_collect.update',$monthly_fee->id)}}" title="Add" class="table-button table-button-success add-fee"><i class="fa fa-plus-circle"></i> Add</a></td>
                                            @break
                                        @endif
                                        @if($monthly_fee->status==1)
                                            <td class="text-center"><a href="{{route('fee_collect.edit',$monthly_fee->id)}}" title="Edit" class="text-success edit-fee"><i class="fa fa-edit"></i></a></td>
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr><td colspan="100%" class="text-center">No records found</td></tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bill-book-container">
                <div class="bill-book-head">Exam Fee</div>
                <div class="bill-book-body">
                    <table class="table table-striped table-bordered fee-details">
                        <thead>
                        <tr class="text-center">
                            <th width="55%">Fee Description</th>
                            <th width="20%">Receipt no</th>
                            <th width="10%">Details</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($exam_fees)!=0)
                            @foreach($exam_fees as $exam_fee)
                                <tr @if($exam_fee->status == 4) class="text-danger" @endif>
                                    <td>{{$exam_fee->fee_description}}</td>
                                    <td>{{$exam_fee->physical_receipt_no!=null ? $exam_fee->physical_receipt_no:"NA"}}</td>
                                    <td class="text-center"><a title="Details" href="{{route('fee_collect.show',$exam_fee->id)}}" class="text-info view-details"><i class="fa fa-1x fa-info-circle"></i></a></td>
                                    @if($exam_fee->status == 0||$exam_fee->status == 4)
                                        <td class="text-center"><a href="{{route('fee_collect.update',$exam_fee->id)}}" class="table-button table-button-success add-fee"><i class="fa fa-plus-circle"></i> Add</a></td>
                                    @else
                                        <td class="text-center"><a href="{{route('fee_collect.edit',$exam_fee->id)}}" title="Edit" class="text-success edit-fee"><i class="fa fa-edit"></i></a></td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="100%" class="text-center">No records found</td></tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="text-center pt-2">
                <button class="btn btn-contrast">Next <i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i></button>
            </div>
        </div>
    </div>
    <div class="expandable-toggle-container" id="buffer-records">
        <div class="expandable-toggle-head {{isset($stage)?($stage==2?'active':''):''}}">Fill Bill Details &nbsp; <span class="badge badge-pill badge-success">{{count($fees_buffer)!=0 ? count($fees_buffer):''}}</span> <i class="fa {{isset($stage)?($stage==2?'fa-minus-circle':'fa-plus-circle'):'fa-plus-circle'}} "></i></div>
        <div class="expandable-toggle-body">
            @if(count($fees_buffer)!=0)
                @foreach($fees_buffer as $item)
                    <div class="bill-card">
                        <div class="bill-card-head">{{$item->fee_description}}
                            @if($loop->last) <a href="{{route('fee_collect.update',$item->id)}}"><i class="fa text-danger fa-window-close"></i></a> @endif</div>
                        @if($loop->first)
                            <div class="bill-card-body">
                                <form action="{{route('fee_collect.update',$item->id)}}">
                                    <div class="result pb-2"></div>
                                    <div class="form-group row">
                                        <label for="" class="col-md-4 col-form-label col-form-label-sm">Payment Type :-</label>
                                        <div class="col-md-8">
                                            <select name="fee_payment_type" class="form-control payment-type form-control-sm select-picker-no-search">
                                                <option value=""></option>
                                                <option value="0">Cash Pay</option>
                                                <option value="1">Force closed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row fee_price" id="" style="display: none">
                                        <label for="" class="col-md-4 col-form-label col-form-label-sm">Price :-</label>
                                        <div class="col-md-8">
                                            <select type="text" name="fee_id" class="form-control form-control-sm select-picker-no-search">
                                                <option></option>
                                                @if($item->fee_type_id==2)
                                                    @foreach($active_monthly_fees as $monthly_fee)
                                                        <option data-icon="fa-rupee-sign" value="{{$monthly_fee->id}}">{{$monthly_fee->price}}</option>
                                                    @endforeach
                                                @endif
                                                @if($item->fee_type_id==3)
                                                    @foreach($active_exam_fees as $exam_fee)
                                                        <option data-icon="fa-rupee-sign" value="{{$exam_fee->id}}">{{$exam_fee->price}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row receipt_no" id="" style="display: none">
                                        <label for="" class="col-md-4 col-form-label col-form-label-sm">Receipt no :-</label>
                                        <div class="col-md-8">
                                            <input type="text" name="physical_receipt_no" id="" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="form-group row fee_date" style="display: none">
                                        <label for="" class="col-md-4 col-form-label col-form-label-sm">Receipt date :-</label>
                                        <div class="col-md-8">
                                            <input type="text" readonly name="physical_receipt_date" class="form-control form-control-sm date">
                                        </div>
                                    </div>
                                    <div class="form-group row reason" id="" style="display: none">
                                        <label for="" class="col-md-4 col-form-label col-form-label-sm">Reason :-</label>
                                        <div class="col-md-8">
                                            <input type="text" name="comments" id="" class="form-control-sm form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" name="submit" value="submit" class="table-button table-button-info">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="text-muted text-center">No records found</div>
            @endif
                <div class="text-center pt-2">
                    <button class="btn btn-contrast">Next <i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i></button>
                </div>
        </div>
    </div>
    <div class="expandable-toggle-container" id="draft-records">
        <div class="expandable-toggle-head {{isset($stage)?($stage==3?'active':''):''}}">Final Submission &nbsp; @if(count($drafted_fees)) <span class="badge badge-success badge-pill">{{count($drafted_fees)}}</span> @endif <i class="fa {{isset($stage)?($stage==3?'fa-minus-circle':'fa-plus-circle'):'fa-plus-circle'}} "></i></div>
        <div class="expandable-toggle-body table-responsive table-block pl-0 pr-0">
            @if(count($drafted_fees))
                <table class="table table-bordered drafted pt-0">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Fee Description</th>
                        <th>Receipt No</th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($drafted_fees as $drafted_fee)
                        <tr>
                            <td class="text-center">@if($loop->last)<a href="{{route("fee_collect.update", $drafted_fee->id)}}" title="Edit" class="text-danger"><i class="fa fa-trash-alt"></i></a> @endif</td>
                            <td>{{$drafted_fee->fee_description}}</td>
                            <td>{{$drafted_fee->physical_receipt_no!=null?$drafted_fee->physical_receipt_no:'NA'}}</td>
                            <td class="text-center">@if($drafted_fee->fee['price']!=null)<i class="fa fa-rupee-sign"></i> {{$drafted_fee->fee->price}} @else NA @endif</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2"></td>
                        <td><b>Total :-</b></td>
                        <td class="text-center"><i class="fa fa-rupee-sign"></i> {{$sum}}</td>
                    </tr>
                    </tbody>
                </table>
                <div class="text-center alert-danger alert">
                    <b> <i class="fa fa-exclamation-circle"></i> Note:- </b>&nbsp;&nbsp; After final submission changes are not allowed
                </div>
                <div class="text-center pt-3">
                    <button data-url="{{route("fee_collect.update",$level->id)}}" class="btn btn-contrast"><i class="fa fa-paper-plane"></i> Submit</button>
                </div>
            @else
                <div class="text-center text-muted pt-1 pb-1">No records found</div>
            @endif
        </div>
    </div>
