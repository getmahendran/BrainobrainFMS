$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });


    /*AJAX fee collection action on submit and database update*/
    $('#feeCollectionForm').on('submit', function (event) {
        event.preventDefault();
        var x               =   $('input[name="fee_type_id[]"]:checked');
        var check_values    =   [];
        var htmlOut         =   '';
        if(x.length>0){
            $(x).each(function () {
                if(this.value == 2 || this.value == 3)
                    check_values.push(this.value);
                else
                    location.reload();
            });
            $.ajax({
                url: '/brainmanage/public/feeCollect/'+id+'/collect',
                type: 'POST',
                data: {
                    'check_values': check_values,
                    'duration': duration
                },
                dataType: 'JSON',
                beforeSend:function () {
                    $('.message-box').show().html('Updating..!!');
                },
                success: function (result) {
                    monthly_fee_count       =   result['monthly_fee_obj'].length;
                    if(result['monthly_fee_obj'].length == duration){
                        $('#monthlyFeeStatus').html('<span class="text-success">Paid</span>');
                        $('#monthlyFeePending').html('<span class="text-muted">None</span>');
                        $.each(result['monthly_fee_obj'], function (key, value) {
                            htmlOut     +=  '<label class="container-check pl-0">Month  '+(key+1)+' - Bill number - '+value['id']+'</label>';
                        });
                        $('#monthlyFeePaid').html(htmlOut);
                        htmlOut     =   '';
                    }else{
                        $('#monthlyFeeStatus').html('<span class="text-danger">Pending</span>');
                        for(var i = result['monthly_fee_obj'].length+1; i <= duration; i++){
                            htmlOut     +=  '<label class="container-check">Month '+i+' Fee<span class="text-muted fee-price"> - <i class="fa fa-rupee-sign"></i> '+monthly_fee+'</span> <input type="checkbox" name="fee_type_id[]" value="2" class="fee-select"> <span class="checkmark-check"></span> </label>';
                        }
                        $('#monthlyFeePending').html(htmlOut);
                        htmlOut     =   '';
                        console.clear();
                        $.each(result['monthly_fee_obj'], function (key, value) {
                            htmlOut     +=  '<label class="container-check pl-0">Month  '+(key+1)+' - Bill number - '+value['id']+'</label>';
                        });
                        $('#monthlyFeePaid').html(htmlOut);
                    }

                    if(result['exam_fee_obj'].length > 0){
                        $('#examFeeStatus').html('<span class="text-success">Paid</span>');
                        $.each(result['exam_fee_obj'], function (key, value) {
                            htmlOut     =   '<span class="text-success">Paid</span> - Bill number - '+value['id'];
                        });
                    }else{
                        $('#examFeeStatus').html('<span class="text-danger">Pending</span>');
                        if(result['monthly_fee_obj'].length == duration)
                            htmlOut     =   '<label class="container-check">Exam Fee <span class="text-muted fee-price"> - <i class="fa fa-rupee-sign"></i> '+exam_fee+'</span> <input type="checkbox" name="fee_type_id[]" value="3" class="fee-select exam-fee"><span class="checkmark-check"></span></label>';
                        else
                            htmlOut     =   '<label class="container-check">Exam Fee <span class="text-muted fee-price"> - <i class="fa fa-rupee-sign"></i> '+exam_fee+'</span> <input type="checkbox" name="fee_type_id[]" value="3" class="fee-select exam-fee" disabled><span class="checkmark-check"></span></label>';
                    }
                    $('#examFee').html(htmlOut);
                    htmlOut     =   '';

                    if(result['monthly_fee_obj'].length == duration && result['exam_fee_obj'].length > 0)
                        $('.end-component').html('');
                    $('#total').html('<i class="fa-rupee-sign fa"></i> 0');
                    $('.message-box').html(result['output']);
                    setTimeout(function() { $(".message-box").hide(); }, 1000);
                }
            });
        }
        else{
            $('.message-box').show().html('No fee selected..!!');
            setTimeout(function() { $(".message-box").hide(); }, 1500);
        }
    });
    /*Ends here*/


    //Ajax student profile image update
    $('#studentProfileImageUpdateForm').on('submit', function (event) {
        event.preventDefault();
        var errorHtml   =   "";
        var formData    =   new FormData(this);
        $.ajax({
            url:            $(this).attr('action'),
            type:           'POST',
            dataType:       'JSON',
            data:           formData,
            cache:          false,
            contentType:    false,
            processData:    false,
            beforeSend:     function () {
                $('#result').show().html('<i class="fa fa-spinner fa-2x"></i>').addClass('text-center');
            },
            success:        function (result) {
                if(result.errors){
                    $.each(result.errors, function (key, value) {
                        errorHtml+=value + "<br>";
                    });
                    $('#studentProfileImageUpdateModal #studentProfileImageUpdateForm')[0].reset();
                    $('#path-error').html(errorHtml);
                }else{
                    $('#result').html('<div class="alert alert-success"><strong>'+result["success"]+'</strong></div>');
                    $('#studentEditForm #imgPreview').attr('src', $('#studentProfileImageUpdateModal #imgPreview').attr('src'));
                    $('#studentProfileImageUpdateModal #studentProfileImageUpdateForm')[0].reset();
                }
            },
        });
    });
    //Ends here

    //Ajax retrive students for question paper request on selecting the franchisee
    $("#raiseQuestionPaperRequestForm #franchisee_id").change(function () {
        var table;
        if(Math.floor($(this).val()) == $(this).val() && $.isNumeric($(this).val()))
        {
            // alert($(this).val());
            $.ajax({
                url:            $('option:selected', this).attr("target-url"),
                method:         'GET',
                dataType:       "JSON",
                beforeSend:     function () {
                    $(".message-box").show().html("Loading...");
                },
                success:        function (result) {
                    if(result.error)
                    {
                        $(".message-box").html(result["error"]);
                        var table = $('#students-list table').DataTable();
                        var rows = table
                            .rows()
                            .remove()
                            .draw();
                        $("#raiseQuestionPaperRequestForm #students-list").hide();
                        $("#raiseQuestionPaperRequestForm button").hide();
                    }
                    else
                    {
                        $(".message-box").hide().html("");
                        $("#raiseQuestionPaperRequestForm #students-list").show();

                        if(table)   table.clear();
                        $("#students-list").show();
                        // $('#students-list button').show();
                        table = $("#students-list table").DataTable({
                            order:          [ 1, 'asc' ],
                            destroy:        true,
                            bLengthChange:  false,
                            paging:         true,
                            columnDefs:     [{targets:  [0, 3, 4], orderable: false},{targets: [0], className:'text-center'}]
                        });
                        var rows = table
                            .rows()
                            .remove()
                            .draw();
                        $.each(result, function (key, value) {
                            table.row.add([
                                value.select_level_id,
                                value.student_no,
                                value.name,
                                value.batch,
                                value.program,
                                value.course,
                                value.franchisee
                            ]).draw();
                        });
                        $("#raiseQuestionPaperRequestForm button").show();
                    }
                }
            });
        } else {
            alert("Franchisee Invalid..!!");
        }
    });
    //Ends here

    //Ajax View question paper request details
    $("table .qp-request-details").click(function (event) {
        event.preventDefault();
        $button     =   $(this);
        $.ajax({
            url:        $(this).attr("href"),
            method:     "GET",
            dataType:   "JSON",
            data:       "button_action=for_admin",
            beforeSend: function () {
                $(".message-box").show().html("Loading..");
            },
            success:    function (result) {
                $button.closest("tr").find("td:eq(3)").html(result["status"]["status"]).attr("class",result["status"]["status-class"]);
                $(".message-box").html("").hide();
                $("#questionPaperRequestDetailsModal #franchisee").html(result["franchisee"]);
                $("#questionPaperRequestDetailsModal #statusText").html(result["status"]["status"]).addClass(result["status"]["status-class"]);
                $("#questionPaperRequestDetailsModal form").attr("action",result["update_url"]);
                $("#qpRequestDetails table tbody").html("");
                $.each(result.details, function (key, value) {
                    $("#qpRequestDetails table tbody").append("<tr>" +
                        "<td>"+value["student_no"]+"</td>" +
                        "<td>"+value["name"]+"</td>" +
                        "<td>"+value["course"]+"</td>" +
                        "<td><a tabindex=\"0\" class='nounderline' data-toggle=\"popover\" href='#' data-trigger=\"focus\" title=\"Fee Details\" data-content=\""+value["fee_details"]+"\">Click here</a></td>" +
                        "</tr>");
                });
                $("#questionPaperRequestDetailsModal select").html("<option selected hidden disabled>---- Select -----</option>")
                $.each(result.all_status, function (key, value) {
                    $("#questionPaperRequestDetailsModal select").append("" +
                        "<option value='"+key+"'>"+value[0]+"</option>")
                });
                $("#questionPaperRequestDetailsModal").modal("show");
            }
        });
    });
    //Ends here

    //Update question paper request status
    $("#questionPaperRequestDetailsModal").on("submit","form",function (event) {
        event.preventDefault();
        $.ajax({
            url:            $(this).attr("action"),
            method:         "PUT",
            dataType:       "JSON",
            data:           $(this).serialize(),
            async:          true,
            beforeSend:     function () {
                $("#questionPaperRequestDetailsModal #message").html("<i class='fa fa-spinner'></i>");
            },
            success:        function (result) {
                var qp_request_response = result;
                localStorage.setItem("qp_request_response", qp_request_response);
                location.reload();
            }
        });
    });    //Ends here

});



/*User defined functions functions*/


/*AJAX Retrive form elements if faculty/franchisee owner is married */
function marriedDisplay(value){
    $.ajax({
        url: value,
        type: 'GET',
        beforeSend:function () {
            $('.message-box').show().html('Loading..!!');
        },
        success: function (result) {
            $(".message-box").html('').hide();
            $("#married-form").html(result);
        }
    });
}
/*Ends here*/

//load page using ajax
function pageload() {
    var query_string = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    $.ajax({
        url:            window.location,
        method:         "GET",
        data:           query_string,
        beforeSend:     function () {
            $("#load").show();
        },
        success:        function (result) {
            $("#load").hide();
            $("#body").html(result);
            $(".batch-students table").dataTable({
                "columnDefs": [ {
                    "targets": 0,
                    "orderable": false
                }]
            });
        }
    });
}
//Ends here

//Function to create question paper request
function raiseQuestionPaperRequest(form) {
    $.ajax({
        url:        form.attr("action"),
        method:     "POST",
        dataType:   "JSON",
        data:       form.serialize(),
        async:      true,
        beforeSend: function () {
            $("#questionPaperRequestPreviewModal #message").html("<i class=\"fa fa-spinner fa-2x\"></i>");
        },
        success:    function (result) {
            $("#questionPaperRequestPreviewModal #message").html("<div class='alert alert-success'>"+result["success"]+"</div>");
            $("#raiseQuestionPaperRequestForm")[0].reset();
            $("#raiseQuestionPaperRequestForm button").hide();
            $("#raiseQuestionPaperRequestForm #students-list").hide();
        }
    });
}
//Ends here

function programDropDownRefresh() {
    $.ajax({
        url:        "/brainmanage/public/program",
        method:     "GET",
        data:       "action=Dropdown_refresh",
        dataType:   "JSON",
        success:    function (result) {
            $("#program_id").html(result);
        }
    });
}
