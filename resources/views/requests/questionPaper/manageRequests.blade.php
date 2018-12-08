@extends("layouts.bobkar")

@section("content")
    <div class="modal fade" id="questionPaperRequestDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="title">Question paper request details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="message" class="text-center"></div>
                    <p><b>Franchisee:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="franchisee"></span></p>
                    <p><b>Status:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="statusText"></span></p>
                    <div class="table-responsive table-hover table-block" id="qpRequestDetails">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Student No.</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Fee Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <form method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group row">
                            <label for="status" class="col-4">Status:</label>
                            <select class="form-control form-control-sm col-7" id="status" name="status"></select>
                        </div>
                        <div class="form-group row justify-content-center">
                            <input type="hidden" value="status_update" name="button_action">
                            <button class="btn btn-info btn-block col-4">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-uppercase">
                    manage question paper requests
                </div>
                <div class="card-body">
                    <div class="table-hover table-block table-responsive">
                        <table class="table table-bordered">
                        <thead>
                        <tr class="text-center">
                            <th>Ref No.</th>
                            <th>Franchisee</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($qp_requests as $qp_request)
                        <tr class="text-center">
                            <td>{{$qp_request->getIdAttr($qp_request->id)}}</td>
                            <td>{{$qp_request->franchisee->branch_name}}</td>
                            <td>{{$qp_request->created_at}}</td>
                            <td class="{{$status->getQuestionPaperRequestStatus($qp_request->status,1)}}">{{$status->getQuestionPaperRequestStatus($qp_request->status,0)}}</td>
                            <td class="text-center"><a href="{{route("questionPaperRequest.edit",$qp_request->getOriginal('id'))}}" class="nounderline text-info qp-request-details"><i class="fa fa-info-circle fa-2x"></i></a></td>
                        </tr>
                        @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            if (localStorage.getItem("qp_request_response") != null)
            {
                $(".message-box").show().html(localStorage.getItem("qp_request_response"));
                setTimeout(function () {
                    $(".message-box").html('').hide();
                }, 3000);
            }
            localStorage.clear();
        });
    </script>
@endsection