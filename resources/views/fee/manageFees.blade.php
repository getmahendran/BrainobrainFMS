@extends('layouts.bobkar')

@section('content')

    <!-- Modal -->
    <div class="modal fade" id="editFeeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="result" class="text-center"></div>
                    <form id="editFeeForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group">
                            <label for="area_id" class="col-form-label">Ural/Rural</label>
                            <select name="area_id" id="area_id" class="form-control">
                                <option selected disabled hidden>--- Select ---</option>
                                @foreach($areas as $area)
                                    <option value="{{$area->id}}">{{$area->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="price" class="col-form-label">Price</label>
                            <input type="text" name="price" class="form-control" id="price">
                        </div>
                        <div class="form-group">
                            <label for="effective_from">Effective From</label>
                            <input type="text" class="form-control" name="effective_from" id="effective_from">
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-form-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option selected disabled hidden>--- Select ---</option>
                                @for($i=count($fee_status)-1; $i>=0;$i--)
                                    <option value="{{$i}}">{{$fee_status[$i][0]}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Model Ends here-->


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 p-0 col-md-12 col-lg-10">
                <div class="card">
                    <div class="card-header text-uppercase">manage fees</div>
                    <div class="card-body">
                        <div class="row pb-2 ">
                            <div class="col-12 pr-0">
                                <a href="{{route("fee.create")}}" class="btn btn-primary float-right active" role="button" aria-pressed="true"><i class="fa fa-plus"></i> &nbsp;Add Fee</a>
                            </div>
                        </div>
                        <div class="row" id="fee-type">
                            @foreach($feeTypes as $feeType)
                            <a href="{{route("fee.index")}}" class="col-4 nounderline text-center stab pt-md-3 pb-md-3 pt-sm-2 pb-sm-2 pt-0 pb-0" data-fee-type-id="{{$feeType->id}}">
                                {{$feeType->name}}
                            </a>
                            @endforeach
                        </div>
                        <div class="fee-details stab-data pt-4 pb-1">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            // Ajax load admission fee on page load
            $("#fee-type a:first-child").addClass("active");
            getFeeDetails($("#fee-type a:first-child").attr("href"), "fee_type_id="+$("#fee-type a:first-child").data("fee-type-id"));
            //Ends here

            //Ajax to retrive appropriate fees list using fee type
            $("#fee-type a").click(function (event) {
                event.preventDefault();
                $(".stab").removeClass("active");
                $(this).addClass("active");
                getFeeDetails($(this).attr("href"), "fee_type_id="+$(this).data("fee-type-id"));
            });
            //Ends here

            //Ajax to load popup menu for edit fee details
            $(".fee-details.stab-data").on("click", ".edit-fee", function (event) {
                event.preventDefault();
                $.ajax({
                    url:        $(this).attr("href"),
                    method:     "GET",
                    dataType:   "JSON",
                    beforeSend: function () {
                        $(".message-box").html("Loading...").show();
                    },
                    success:    function (result) {
                        $(".message-box").html("").hide();
                        $("#editFeeModal form").attr("action",result["action-url"]);
                        if(result["fee"]["fee_type_id"] == 2)
                        {
                            $("#area_id option").filter(function() {
                                return $(this).val()==result["fee"]["area_id"];
                            }).prop('selected', true);
                            $("#editFeeModal #area_id").prop("disabled", false);
                        }
                        else
                            $("#editFeeModal #area_id").attr("disabled", "disabled").prop('selectedIndex',0);
                        $("#editFeeForm #price").val(result["fee"]["price"]);
                        $("#editFeeForm #effective_from").val(result["fee"]["effective_from"]).attr("data-min-date",currentDate()).addClass("date");
                        $("#editFeeForm #status option").filter(function () {
                            return $(this).val()==result["fee"]["status"];
                        }).prop("selected", true);
                        $("#editFeeModal #result").html("");
                        $("#editFeeModal").modal("show");
                    }
                });
            });
            //Ends here

            //Ajax update fees details on modal
            $("#editFeeForm").on("submit", function (event) {
                event.preventDefault();
                $.ajax({
                    url:        $(this).attr("action"),
                    method:     "PUT",
                    dataType:   "JSON",
                    data:       $(this).serialize(),
                    beforeSend: function () {
                        $("#editFeeModal #result").html("<i class='fa fa-spinner'></i>");
                    },
                    success:    function (result) {
                        if(result.errors)
                        {
                            var errorHtml   =   "";
                            $.each(result.errors, function (key, value) {
                                errorHtml   +=  "<div class='font-weight-bold alert alert-danger'><i class='fa fa-exclamation-triangle'></i> "+value+"</div>";
                            });
                            $("#editFeeModal #result").html(errorHtml)
                        }
                        else
                            $("#editFeeModal #result").html("<div class='font-weight-bold alert alert-success'><i class='fa fa-check-circle'></i> "+result+"</div>")
                        $(".stab.active").trigger("click");
                    }
                });
            });
            //Ends here

        });

        function getFeeDetails(url, data) {
            $.ajax({
                url:        url,
                method:     "GET",
                dataType:   "JSON",
                data:       data,
                beforeSend: function () {
                    $(".fee-details.stab-data").html("<div class='text-center'><i class='fa fa-spinner'></i></div>");
                },
                success:    function (result) {
                    if(result.errors)
                        $(".fee-details.stab-data").html("<div class='text-danger text-center'><i class='fa fa-exclamation-triangle'></i> "+result["errors"]+"</div>");
                    else
                    {
                        var outHtml="<div class='col-12 pl-0 pr-0 pb-3'>" +
                            "<b>Fee Type :-</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+result['fee_type']+"" +
                            "</div>" +
                            "<div class='col-12 table-block table-striped pl-0 pr-0 pr-0'>" +
                            "<table class='table table-bordered'>" +
                            "<thead>" +
                            "<tr>" +
                            "<th>Fee ID</th>" +
                            "<th>Rural/Urban</th>" +
                            "<th>Price</th>" +
                            "<th>Status</th>" +
                            "<th>Effective From</th>" +
                            "<th>Effective Till</th>" +
                            "<th>Edit</th>" +
                            "</tr>" +
                            "</thead>" +
                            "<tbody>";
                        $.each(result.fee_details, function (key, value) {
                            outHtml +=  "<tr>" +
                                "<td>"+value["fee_id"]+"</td>" +
                                "<td>"+checkNull(value["area"])+"</td>" +
                                "<td>"+value["price"]+"</td>" +
                                "<td class='"+value["status"][1]+"'>"+value["status"][0]+"</td>" +
                                "<td>"+value["effective_from"]+"</td>" +
                                "<td>"+checkNull(value["effective_till"])+"</td>" +
                                "<td class='text-center'><a href='"+value["edit-url"]+"' class='nounderline edit-fee text-success'><i class='fa fa-edit'></i></a></td>" +
                                "</tr>";
                        });
                        outHtml     +=  "</tbody></table></div>";
                        $(".fee-details.stab-data").html(outHtml);
                    }
                }
            });
        }
    </script>
@endsection