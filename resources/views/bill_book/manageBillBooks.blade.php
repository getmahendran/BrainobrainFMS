@extends("layouts.bobkar")

@section("content")
    <div class="modal fade" id="editBillBookModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="exampleModalLongTitle">Bill Book Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="message"></div>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th width="60%">Reference No</th>
                            <td width="40%">5</td>
                        </tr>
                        <tr>
                            <th>From</th>
                            <td id="from"></td>
                        </tr>
                        <tr>
                            <th>Till</th>
                            <td id="till"></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td id="status"></td>
                        </tr>
                        <tr>
                            <th>Fee Type</th>
                            <td id="fee_type"></td>
                        </tr>
                        </tbody>
                    </table>
                    <form action="" id="editBillBookForm" onsubmit="update_bill_details($(this))">
                        <div class="form-group row">
                            <label for="wasted" class="col-12 font-weight-bold">Cancelled count</label>
                            <div class="col-12">
                                <input type="text" class="form-control form-control-sm" name="wasted_count" id="wasted">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="comments" class="col-12 font-weight-bold">Comments</label>
                            <div class="col-12">
                                <textarea type="text" name="comments" id="comments" rows="" class="form-control form-control-sm">
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 text-center">
                                <button class="btn search-btn" type="submit"><i class="fa fa-paper-plane"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<div class="dialog-confirm">
</div>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header text-uppercase">manage bill books</div>
            <div class="card-body">
                <div class="result"></div>
                <form action="{{route("bill_book.index")}}" id="searchFranchiseeBillBookForm" onsubmit="search_franchisee_bill_book($(this))">
                    <div class="form-group row justify-content-center">
                        <label for="franchisee_id" class="col-md-3 col-sm-4 col-12 col-lg-3 col-form-label font-weight-bold">Franchisee Branch :-</label>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-12">
                            <select data-url="{{route("fee_collect.student_search")}}" name="franchisee_id" id="franchisee_id" class="form-control selectpicker">
                                <option></option>
                                @foreach($franchisees as $franchisee)
                                    <option value="{{$franchisee->id}}">{{$franchisee->center_code}} - {{$franchisee->franchisee_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 pt-md-0 pt-lg-0 pt-sm-1 col-12 pt-1">
                            <button class="btn search-btn"><i class="fa fa-search"></i> Search </button>
                        </div>
                    </div>
                </form>
                <div class="tab-container row" id="fee-type">
                </div>
                <br>
                <div id="bill-book-details">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("script")
    <script>
        $(document).ready(function () {
            // var query_string    =   window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            $(document).on("click", "#fee-type div", function (event) {
                event.preventDefault();
                $("#fee-type div").removeClass("active");
                $(this).addClass("active");
                $("#bill-book-details").html($(".d-none", this).html());
            });
        });

        function edit_bill_details(element)
        {
            event.preventDefault();
            $.ajax({
                url:        element.attr("href"),
                method:     "GET",
                dataType:   "JSON",
                beforeSend: function () {
                    $("#load").show();
                },
                success:    function (result) {
                    $("#load").hide();
                    $("#editBillBookForm textarea[name='comments']").html(result['comments']);
                    $("#editBillBookModal input[name='wasted_count']").val(result['wasted_count']);
                    $("#editBillBookModal #id").html(result['id']);
                    $("#editBillBookModal #from").html(result['from']);
                    $("#editBillBookModal #till").html(result['till']);
                    $("#editBillBookModal #status").html("<span class='"+result['status'][1]+"'>"+result['status'][0]+"</span>");
                    $("#editBillBookModal #fee_type").html(result['fee_type']);
                    $("#editBillBookModal #editBillBookForm").attr("action",result["action_url"]);
                    $("#editBillBookModal").modal("show");
                }
            });
        }
        function update_bill_details(element) {
            event.preventDefault();
            $.ajax({
                url:        element.attr('action'),
                method:     "PUT",
                dataType:   "JSON",
                data:       element.serialize()+"&button_action=wasted_receipt",
                beforeSend: function () {
                    $(".message").html("<div class='text-center'><i class='fa fa-spinner'></i></div>");
                },
                success:    function (result) {
                    if(result.errors)
                    {
                        var error_html  =   "";
                        $.each(result.errors, function (key, value) {
                            error_html  +=  "<div class='alert font-weight-bold alert-danger'><i class='fa fa-exclamation-circle'></i> "+value+"</div>";
                        });
                        $(".message").html(error_html);
                    }
                    else
                    {
                        $(".message").html("<div class='alert font-weight-bold alert-success text-center'><i class='fa fa-check-circle'></i> "+result+"</div>");
                        search_franchisee_bill_book($("#searchFranchiseeBillBookForm"), $("#fee-type div.active"));
                    }
                    $(".message").fadeTo(2000, 500).slideUp(500, function() {
                    $(".message").slideUp(1000);
                    });
                }
            });
        }
        
        function delete_bill_confirm_dialog(title, message, element) {
            event.preventDefault();
            $(".dialog-confirm").html(message);
            $(".dialog-confirm").dialog({
                draggable:  false,
                resizable:  false,
                modal:      true,
                title:      title,
                width:      320,
                height:     200,
                buttons: {
                    "Yes": function () {
                        delete_bill_book(true, element);
                        $(this).dialog('close');
                    },
                    "No": function () {
                        $(this).dialog('close');
                    }
                }
            });
        }

        function delete_bill_book(bool, element)
        {
            if(bool)
            {
                $.ajax({
                    url:        element.attr("href"),
                    method:     "DELETE",
                    dataType:   "JSON",
                    beforeSend: function () {
                        $("#load").show()
                    },
                    success:    function (result) {
                        $("#load").hide();
                        $(".dialog-confirm").html(result);
                        $(".dialog-confirm").dialog({
                            draggable:  false,
                            resizable:  false,
                            modal:      true,
                            title:      "Success",
                            width:      320,
                            height:     200,
                            buttons: {
                                "Close": function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        search_franchisee_bill_book($("#searchFranchiseeBillBookForm"));
                    }
                });
            }
        }

        function search_franchisee_bill_book(form) {
            event.preventDefault();
            $.ajax({
                url:        form.attr("action"),
                method:     "GET",
                dataType:   "JSON",
                data:       form.serialize(),
                beforeSend: function () {
                    $("#load").show();
                },
                success:    function (result) {
                    if(result.errors)
                    {
                        var error_html  =   '';
                        $.each(result.errors, function (key, value) {
                            error_html  +=  "<div class='alert alert-danger text-center'><i class='fa fa-exclamation-circle'></i> "+value+"</div>";
                        });
                        $("#bill-book-details").html(error_html);
                    }
                    if(result.success)
                    {
                        var out_html='';
                        $.each(result.success, function (key, value) {
                            out_html    +=  "<div class=\"col-4 stab pt-md-3 pb-md-3 pt-sm-2 pb-sm-2 pt-1 pb-1 text-center\">" +
                                value['fee_type']['name'];
                            out_html    +=  "<div class='d-none'>" +
                                "<div class='row'>" +
                                "<label for='' class='col-12 col-sm-12 col-md-7'><b>Fee Type:- </b>"+value['fee_type']['name']+"</label>" +
                                "<div class='col-12 col-sm-12 col-md-5 col-5'><label class='sr-only'></label><select name='status' id='' class='form-control custom-select custom-select-sm form-control-sm' onchange='filterText()'>";
                            out_html    +=  "<option value='all'>All</option>";
                            $.each(value.status, function (k, v) {
                                out_html    +=  "<option>"+v[0]+"</option>";
                            });
                            out_html    +=  "</select></div>"+
                                "</div><br>" +
                                "<div class='table-block'>" +
                                "<table style='width=100%' class='table text-center table-bordered'>" +
                                "<thead><tr>" +
                                "<th>Ref No</th>" +
                                "<th>From</th>" +
                                "<th>Till</th>" +
                                "<th>Cancelled</th>" +
                                "<th>Remaining</th>"+
                                "<th>Status</th>" +
                                "<th colspan='2'>Action</th>" +
                                "</tr></thead>" +
                                "<tbody>" ;
                            $.each(value.bills, function (k,v) {
                                out_html    +=  "<tr>" +
                                    "<td>"+v['id']+"</td>" +
                                    "<td>"+v['from']+"</td>" +
                                    "<td>"+v['till']+"</td>" +
                                    "<td>"+checkNull(v['wasted_count'])+"</td>" +
                                    "<td>"+checkNull(v['remaining'])+"</td>" +
                                    "<td class='"+v['status'][1]+"'>"+v['status'][0]+"</td>"+
                                    v['external-link']+
                                    "</tr>";
                            });
                            out_html    +=  "</tbody>" +
                                "</table>" +
                                "</div>"+
                                "</div>" ;
                            out_html    +=  "</div>";
                        });
                        $("#fee-type").html(out_html);
                        $("#bill-book-details").html("");
                        $("#fee-type div:first").trigger("click");
                    }
                    $("#load").hide();
                }
            });
        }

        function activate_bill_Book(element) {
            event.preventDefault();
            $.ajax({
                url:        element.attr("href"),
                method:     "PUT",
                dataType:   "JSON",
                data:       "button_action=activate",
                beforeSend: function () {
                    $("#load").show();
                },
                success:    function (result) {
                    $("#load").hide();
                    if(result.errors)
                    {
                        var error_html  =   '';
                        $.each(result.errors, function (key, value) {
                            error_html  +=  "<div class='text-center font-weight-bold alert alert-danger'><i class='fa fa-exclamation-circle'></i> "+value+"</div>";
                        });
                        $(".result").html(error_html);
                    }
                    else
                    {
                        search_franchisee_bill_book($("#searchFranchiseeBillBookForm"));
                        $(".result").html("<div class='alert alert-success font-weight-bold text-center'><i class='fa fa-check-circle'> "+result+"</i></div>");
                    }
                    $(".result").fadeTo(2000, 500).slideUp(500, function(){
                    $(".result").slideUp(1000);
                    });
                }
            });
        }

        function deactivate_bill_Book(element) {
            event.preventDefault();
            $.ajax({
                url:        element.attr("href"),
                method:     "PUT",
                dataType:   "JSON",
                data:       "button_action=deactivate",
                beforeSend: function () {
                    $("#load").show();
                },
                success:    function (result) {
                    $("#load").hide();
                    if(result.errors)
                    {
                        var error_html  =   '';
                        $.each(result.errors, function (key, value) {
                            error_html  +=  "<div class='text-center font-weight-bold alert alert-danger'><i class='fa fa-exclamation-circle'></i> "+value+"</div>";
                        });
                        $(".result").html(error_html);
                    }
                    else
                    {
                        search_franchisee_bill_book($("#searchFranchiseeBillBookForm"));
                        $(".result").html("<div class='alert alert-warning font-weight-bold text-center'><i class='fa fa-check-circle'> "+result+"</i></div>");
                    }
                    $(".result").fadeTo(2000, 500).slideUp(500, function(){
                        $(".result").slideUp(1000);
                    });
                }
            });
        }

        function filterText()
        {
            var rex = new RegExp($('#bill-book-details select[name=\'status\']').val());
            if(rex =="/all/")
            {
                clearFilter();
            }
            else
            {
                $('#bill-book-details table tbody tr').hide();
                $('#bill-book-details table tbody tr').filter(function() {
                    return rex.test($(this).text());
                }).show();
            }
        }
        function clearFilter()
        {
            $('.filterText').val('');
            $('#bill-book-details table tbody tr').show();
        }
    </script>
@endsection    