@extends('layouts.bobkar')

@section('content')
<div class="modal fade" id="editFeePayModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="exampleModalLongTitle"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="result" class="text-center"></div>
                <form id="update-fee-collection" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="fee_payment_type">Payment Type</label>
                            <select name="fee_payment_type" id="fee_payment_type" class="form-control select-picker-no-search">
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label for="fee_id">Price</label>
                            <select name="fee_id" id="fee_id" class="form-control select-picker-no-search">
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <label for="physical_receipt_no">Receipt Number</label>
                            <input type="text" class="form-control" name="physical_receipt_no" id="physical_receipt_no">
                        </div>
                        
                        <div class="form-group col-12">
                            <label for="physical_receipt_date">Receipt Date</label>
                            <input type="text" readonly class="date form-control" name="physical_receipt_date" id="physical_receipt_date">
                        </div>

                        <div class="form-group col-12">
                            <label for="comments">Reason</label>
                            <textarea name="comments" id="comments" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group col-12">
                            <label for="status">Payment Status</label>
                            <select name="status" id="status" class="form-control select-picker-no-search">
                                <option></option>
                                @for($i=0;$i<count($status);$i++)
                                <option value="{{$i}}">{{$status[$i][0]}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-12">
                            <button type="submit" class="btn search-btn btn-block">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="feeDetailsModal"></div>
<div class="row justify-content-center">
    <div class="col-lg-9 col-md-10 col-sm-12 col-12">
        <div class="card">
            <div class="card-header text-uppercase">fee collection</div>
            <div class="card-body">
                <div class="col-12 p-0">
                    <div class="form">
                        <form action="{{route("fee_collect.pay")}}" id="search_student">
                            <div class="form-group row">
                                <label for="level_id" class="col-md-3 col-sm-4 col-12 col-lg-3 col-form-label font-weight-bold">Student Number :-</label>
                                <div class="col-md-6 col-lg-6 col-sm-12 col-12">
                                    <select data-url="{{route("fee_collect.student_search")}}" name="level_id" id="level_id" class="form-control">
                                        <option></option>
                                        @if(isset($level))
                                            <option value="{{$level->id}}" selected>{{$level->student_no}}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 pt-md-0 pt-lg-0 pt-sm-1 col-12 pt-1">
                                    <button class="btn search-btn">Check <i class="fa fa-chevron-right"></i></button>
                                </div>
                            </div>
                        </form>
                        <hr class="themed-margin">
                    </div>
                    <div id="search-results">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>

        $(document).on("click","#all-records .btn-contrast", function (event) {
            event.preventDefault();
            $("#buffer-records .expandable-toggle-head").trigger("click");
        });
        $(document).on("click","#buffer-records .btn-contrast", function (event) {
            event.preventDefault();
            $("#draft-records .expandable-toggle-head").trigger("click");
        });

        $(document).on("click","#draft-records button", function (event) {
            event.preventDefault();
            $.ajax({
                url:        $(this).data('url'),
                method:     "PUT",
                dataType:   "JSON",
                data:       "button_action=final_submit",
                beforeSend: function () {
                    $("#load").show()
                },
                success:    function (result) {
                    refresh_payment_page(3);
                    $("#load").hide();
                }
            });
        });

        $(document).on("click", ".bill-card .bill-card-head a", function (event) {
            event.preventDefault();
            $.ajax({
                url:            $(this).attr("href"),
                method:         "PUT",
                dataType:       "JSON",
                data:           "button_action=remove_from_buffer",
                beforeSend:     function () {
                    $("#load").show();
                },
                success:        function (result) {
                    refresh_payment_page(2);
                    $("#load").hide();
                }
            });
        });

        $(document).on("click",".drafted a", function (event) {
            event.preventDefault();
            $.ajax({
                url:            $(this).attr("href"),
                method:         "PUT",
                dataType:       "JSON",
                data:           "button_action=remove_from_draft",
                beforeSend:     function () {
                    $("#load").show();
                },
                success:        function (result) {
                    refresh_payment_page(3);
                    $("#load").hide();
                }
            });
        });

        $(document).on('change','.bill-card .bill-card-body form .payment-type', function (event) {
            event.preventDefault();
            var form = $(this).parents('form:first');
            if($(this).val() == 0)
            {
                $(".reason", form).hide();
                $(".receipt_no", form).show();
                $(".fee_price", form).show();
                $(".fee_date", form).show();
            }
            if($(this).val() == 1)
            {
                $(".receipt_no", form).hide();
                $(".fee_price", form).hide();
                $(".reason", form).show();
                $(".fee_date", form).hide();
            }
        });

        $(document).on("submit",".bill-card form", function (event) {
            event.preventDefault();
            var form  =   $(this);
            $.ajax({
                url:        $(this).attr("action"),
                method:     "PUT",
                dataType:   "JSON",
                data:       $(this).serialize()+"&button_action=draft",
                beforeSend: function () {
                    $("#load").show();
                },
                success:    function (result) {
                    if(result.errors)
                    {
                        var errors="";
                        $.each(result.errors, function (key, value) {
                            errors+="<div class='text-danger text-center'><i class='fa fa-exclamation-circle'></i> "+value+"</div>";
                        });
                        $(".result",form).html(errors);
                    }
                    if(result.success)
                    {
                        refresh_payment_page(2);
                        $(".result",form).html("<div class='text-success text-center'><i class='fa fa-check-circle'></i> "+result['success']+"</div>")
                        refresh_payment_page(2);
                    }
                    $("#load").hide();
                }
            });
        });

        $(document).ready(function () {

            // var query_string    =   window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            var field = 'level_id';
            var url = window.location.href;
            if(url.indexOf('?' + field + '=') != -1)
                refresh_payment_page(0);

            $("#search_student").on('submit', function (event) {
                event.preventDefault();
                history.pushState(null, '', $(this).attr('action')+"?"+$(this).serialize());
                $.ajax({
                    url:        $(this).attr('action'),
                    method:     'GET',
                    data:       $(this).serialize(),
                    beforeSend: function () {
                        $("#load").show();
                    },
                    success:    function (result) {
                        var error_html="";
                        if(result.errors)
                        {
                            $.each(result.errors, function (key, value) {
                                error_html += "<div class='alert alert-danger text-center font-weight-bold'><i class='fa fa-exclamation-triangle'></i> "+value+"</div>";
                            });
                            $("#search-results").html(error_html);
                        }
                        else
                        {
                            $("#search-results").html(result);
                        }
                        $("#load").hide();
                    }
                });
            });
        });

        $(document).on('click', '.expandable-toggle-container .expandable-toggle-head', function () {
            if($(this).hasClass("active"))
            {
                $(this).removeClass("active");
                $("i", this).replaceWith("<i class='fa fa-plus-circle'></i>");
            }
            else
            {
                $(".expandable-toggle-head").removeClass("active");
                $(".expandable-toggle-head i").replaceWith("<i class='fa fa-plus-circle'></i>");
                $(this).addClass('active');
                $("i",this).replaceWith("<i class='fa fa-minus-circle'></i>");
            }

        });

        $(document).on('click','.fee-details .edit-fee', function (event) {
            event.preventDefault();
            $.ajax({
                url:        $(this).attr("href"),
                dataType:   "JSON",
                method:     "GET",
                beforeSend: function () {
                    $("#load").show();
                },
                success:    function (result) {
                    $("#update-fee-collection")[0].reset();
                    var fee_select_option   =   '';
                    var fee_payment_type    =   '';
                    $modal  =   $("#editFeePayModal");
                    $(".modal-header h4", $modal).html(result['fee_collection']['fee_description']);
                    fee_select_option       =   '<option></option>';
                    $.each(result.fee_ids, function (key, value) {
                        fee_select_option   +='<option value="'+value['id']+'">'+value['price']+'</option>';
                    });
                    fee_payment_type        =   '<option></option>';
                    for(var i=0;i<result['payment_types'].length;i++)
                        fee_payment_type    +=  '<option value="'+i+'">'+result['payment_types'][i]+'</option>';
                    $("#update-fee-collection #fee_payment_type").html(fee_payment_type).val(result['fee_collection']['fee_payment_type']);
                    $("#update-fee-collection #status").val(result['fee_collection']['status']);
                    $("#update-fee-collection #fee_id").html(fee_select_option).val(result['fee_collection']['fee_id']);
                    $("#update-fee-collection").attr('action',result['action-url']);
                    $("#update-fee-collection #comments").html(result['fee_collection']['comments']);

                    $("#update-fee-collection #physical_receipt_no").val(result['fee_collection']['physical_receipt_no']);
                    $("#update-fee-collection #physical_receipt_date").val(result['fee_collection']['physical_receipt_date']);
                    $(".modal-body #result",$modal).html("");
                    $modal.modal('show');
                    $("#load").hide();
                }
            });
        });

        $(document).on("click", "#all-records .view-details", function (event) {
            event.preventDefault();
            $.ajax({
                url:        $(this).attr("href"),
                method:     "GET",
                dataType:   "JSON",
                beforeSend: function () {
                    $("#load").show();
                },
                success:    function (result) {
                    var title       =   result['success']['fee_description'];
                    var out_html    =   "";
                    out_html        +=  "<div class='table-block'>" +
                                        "<table class='table table-bordered table-striped'><tbody>" +
                                        "<tr>" +
                                        "<th>Payment type</th>" +
                                        "<td>"+checkNull(result['success']['fee_payment_type'])+"</td>" +
                                        "</tr>" +
                                        "<tr>" +
                                        "<th>Receipt No</th>" +
                                        "<td>"+checkNull(result['success']['physical_receipt_no'])+"</td>" +
                                        "</tr>" +
                                        "<tr>" +
                                        "<th>Comments</th>" +
                                        "<td>"+checkNull(result['success']['comments'])+"</td>" +
                                        "</tr>" +
                                        "<tr>" +
                                        "<th>Status</th>" +
                                        "<td class='"+result['success']['status'][1]+"'>"+result['success']['status'][0]+"</td>" +
                                        "</tr>" +
                                        "</tbody></table>"+
                                        "</div>";
                    $(".feeDetailsModal").html(out_html);
                    $(".feeDetailsModal").dialog({
                        modal:      true,
                        draggable:  false,
                        resizable:  true,
                        title:      title,
                        width:      400,
                        height:     350
                    });
                    $("#load").hide();
                }
            });
        });

        $(document).on('click','.fee-details .add-fee', function (event) {
            event.preventDefault();
            $.ajax({
                url:        $(this).attr("href"),
                method:     "PUT",
                data:       "button_action=add_fee",
                dataType:   "JSON",
                beforeSend: function () {
                    $('#load').show();
                },
                success:    function (result) {
                    refresh_payment_page(1);
                    $('#load').hide();
                }
            });
        });

        $(document).on('submit','#update-fee-collection', function (event) {
            event.preventDefault();
            $.ajax({
                url:            $(this).attr("action"),
                method:         "PUT",
                dataType:       "JSON",
                data:           $(this).serialize()+"&button_action=admin_update_fee",
                async:          true,
                beforeSend: function () {
                    $("#editFeePayModal .modal-body #result").html('<i class="fa fa-spinner"></i>');
                },
                success:    function (result) {
                    var error_html='';
                    $("#editFeePayModal .modal-body #result").html(result);
                    if(result.errors)
                    {
                        $.each(result.errors, function (key, value) {
                            error_html  +=  "<div class='alert alert-danger font-weight-bold'><i class='fa fa-exclamation-triangle'></i> "+value+"</div>";
                        });
                        $("#editFeePayModal .modal-body #result").html(error_html);
                    }
                    else
                    {
                        $("#editFeePayModal .modal-body #result").html('<div class="alert alert-success font-weight-bold"><i class="fa fa-check-circle"></i> '+result.success+'</div>');
                        refresh_payment_page(0);
                    }

                }
            });
        });

        $("#level_id").select2({
            theme:  'bootstrap',
            placeholder:    '--- Select ---',
            minimumInputLength: 3,
            ajax: {
                url: function () {
                    return $(this).data('url')
                },
                dataType: 'json',
                data: function (params) {
                    var query = {
                        search: params.term,
                    };
                    return query;
                },
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.text,
                                id: item.id
                            }
                        })
                    };
                },
                cache:  true
            }
        });

        function refresh_payment_page(stage) {
            var query_string    =   window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

            $.ajax({
                url:        window.location,
                method:     "GET",
                data:       query_string+"&stage="+stage,
                beforeSend: function(){
                    $("#load").show()
                },
                success:    function (result) {
                    $("#search-results").html(result);
                    $("#load").hide();
                }
            });
        }
    </script>
@endsection