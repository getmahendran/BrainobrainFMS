@extends("layouts.bobkar")

@section("content")
    <!-- Modal -->
    <div class="modal fade" id="questionPaperRequestPreviewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="exampleModalLongTitle">Question Paper Request Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center" id="message"></div>
                    <div class="pr-3 float-right"><a href="#" class="nounderline" id="edit-details"><i class="fa fa-pencil-alt"></i> Edit</a></div>
                    <div class="table-hover col-12 pt-1" style="overflow-x: auto">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Student no</th>
                                <th>Name</th>
                                <th>Batch</th>
                                <th>Program</th>
                                <th>Course</th>
                                <th>Franchisee</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 text-center alert alert-danger">
                        <strong>Note:</strong> Changes are not allowed once request is submitted..!!
                    </div>
                    <div class="text-center">
                        <button class="btn btn-success" id="raise-request"><i class="fa fa-check-circle"></i> &nbsp; Confirm & Raise Request</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Ends Here -->

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-uppercase">New question paper request</div>
                <div class="card-body">
                    <form action="{{route('questionPaperRequest.store')}}" method="POST" id="raiseQuestionPaperRequestForm">
                        @csrf
                        <div class="row">
                            <div class="form-group col-12 col-lg-4 col-md-6 col-sm-12">
                                <label for="franchisee_id">Franchisee Branch</label>
                                <select class="form-control" name="franchisee_id" id="franchisee_id">
                                    <option value="" selected hidden disabled>---- Select -----</option>
                                    @foreach($franchisees as $franchisee)
                                    <option value="{{$franchisee->id}}" target-url="{{route("questionPaperRequest.get.franchisee",$franchisee->id)}}">{{$franchisee->branch_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group offset-lg-4 col-lg-4 col-md-3 offset-md-3">
                                <button class="float-right btn-purple btn" style="display: none"><i class="fa fa-external-link-alt"></i> Proceed</button>
                            </div>
                        </div>
                        <hr style="background-color: #f7921c;height: 1.25px;width: 100%;">
                        <div class="row">
                            <div class="table-responsive table-hover col-12 pt-1" id="students-list" style="overflow-x: auto;display: none">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Student no</th>
                                        <th>Name</th>
                                        <th>Batch</th>
                                        <th>Program</th>
                                        <th>Course</th>
                                        <th>Franchisee</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("script")
    <script>
        // $(document).ready(function() {
        //     $("#raiseQuestionPaperRequestForm table").DataTable({
        //         order:  [1, 'asc'],
        //         "columnDefs":   [
        //             {"targets": [0,3,4,5,6], orderable: false}
        //         ]
        //     });
        // });
    </script>
@endsection
