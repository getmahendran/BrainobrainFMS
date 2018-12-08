//Jquery code for toggle left navigation
$(document).ready(function() {

    $(".dataTable").DataTable();

    $(document).ajaxComplete(function () {
        $('.selectpicker').each(function () {
            $(this).select2({
                theme: 'bootstrap',
                placeholder: "--- Select ---",
                templateSelection: iformat,
                templateResult: iformat,
                allowHtml: true
            });
        });
        $('.select-picker-no-search').each(function () {
            $(this).select2({
                theme:  'bootstrap',
                placeholder: "--- Select ---",
                minimumResultsForSearch: -1,
                templateSelection: iformat,
                templateResult: iformat,
                allowHtml: true
            });
        });
    });
    //Ends here

    //Activate select2 plugin on page load
    $('.selectpicker').each(function () {
        $(this).select2({
            theme: 'bootstrap',
            placeholder: "--- Select ---",
            templateSelection: iformat,
            templateResult: iformat,
            allowHtml: true
        });
    });
    $('.select-picker-no-search').each(function () {
        $(this).select2({
            theme:  'bootstrap',
            placeholder: "--- Select ---",
            minimumResultsForSearch: -1,
            templateSelection: iformat,
            templateResult: iformat,
            allowHtml: true
        });
    });
    //Ends here

    //Enable pop-overs
    $('[data-toggle="popover"]').popover({
        html: true
    });
    $(document).ajaxComplete(function () {
        $('[data-toggle="popover"]').popover({
            html: true
        });
    });
    //Ends here

    $("#toggle").click(function () {
        if ($(window).width() > 1200) {
            if ($('#me').hasClass('d-md-block')) {
                $('#me').removeClass('d-md-block');
                $('#me').addClass('d-md-none');
                $('#content').addClass('col-lg-12 col-md-12');
                $('#content').removeClass('offset-lg-2 offset-md-2 col-lg-10 col-md-10');
            } else {
                $('#me').addClass('d-md-block');
                $('#content').removeClass('col-lg-12 col-md-12');
                $('#content').addClass('offset-lg-2 offset-md-2 col-lg-10 col-md-10');
            }
        }
        else if (($(window).width() >= 992) && ($(window).width() <= 1199.98)) {
            if ($('#me').hasClass('d-md-block')) {
                $('#me').removeClass('d-md-block');
                $('#me').addClass('d-md-none');
                $('#content').addClass('col-lg-12 col-md-12');
                $('#content').removeClass('offset-lg-2 offset-md-2 col-lg-10 col-md-10');
            } else {
                $('#me').addClass('d-md-block');
                $('#content').removeClass('col-lg-12 col-md-12');
                $('#content').addClass('offset-lg-2 offset-md-2 col-lg-10 col-md-10');
            }
        }
        else if (($(window).width() >= 768) && ($(window).width() <= 991.98)) {
            if ($('#me').hasClass('d-md-block')) {
                $('#me').removeClass('d-md-block');
                $('#content').addClass('col-lg-12 col-md-12');
                $('#me').addClass('d-md-none');
                $('#content').removeClass('offset-lg-2 offset-md-2 col-lg-10 col-md-10');
            } else {
                $('#me').addClass('d-md-block');
                $('#content').removeClass('col-lg-12 col-md-12');
                $('#content').addClass('offset-lg-2 offset-md-2 col-lg-10 col-md-10');
            }
        }
        else if (($(window).width() >= 576) && ($(window).width() <= 767.98)) {
            if ($('#me').hasClass('d-sm-block')) {
                $('#me').removeClass('d-sm-block');
                $('#me').addClass('d-sm-none');
            } else {
                $('#me').addClass('d-sm-block');
            }
        }
        else if ($(window).width() <= 575.98) {
            /*if($('#me').hasClass('d-xs-block')){
                $('#me').removeClass('d-xs-block');
                $('#me').addClass('d-xs-none');
                alert('jhgh');
            }else{
                $('#me').addClass('d-xs-block');
                alert('4545');
            }*/
            if ($('#me').hasClass('d-xs-block')) {
                $('#me').removeClass('d-xs-block');
                $('#me').addClass('d-none');
                $('#me').addClass('d-xs-none');
            }
            else {
                $('#me').removeClass('d-none');
                $('#me').removeClass('d-xs-none');
                $('#me').addClass('d-xs-block');
            }
        }
    });
//Jquery code for toggle left navigation ends here

//jQuery for drop down menu
    $('.list-stack a').click(function () {
        $(this).find('i').toggleClass('fa-chevron-right fa-chevron-up');
    });
    $('.navbar-toggler').click(function () {
        $(this).find('i').toggleClass('fa-angle-double-up');
    });
//End jQuery for drop down menu4

//Search code to filter master from list
    $("#master-list-filter").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $(".master-list tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    /*Ends Here*/

//jQuery for new batch add - modal popup
    $("#batchCreateForm #batch_id").on("change", function (event) {
        event.preventDefault();
        $modal = $('#addBatchModal');
        if ($(this).val() === 'new') {
            $('#result').html('');
            $('#addBatchForm')[0].reset();
            $modal.modal('show');
        }
    });
//Ends here

    //Reset course menu on change of franchisee
    $("#batchCreateForm #franchisee_id").change(function () {
        // alert($("#franchisee_id option:selected").attr('target-url'));
        $("#course_id").prop('selectedIndex', 0);
    });
    //Ends here

    //jQuery UI for datepicker
    $('.date').each(function () {
        $(this).datepicker({
            minDate: $(this).data("min-date"),
            maxDate: $(this).data("max-date"),
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
        });
    });


    $(document).ajaxComplete(function () {

        $('.date').each(function () {
            $(this).datepicker({
                minDate: $(this).data("min-date"),
                maxDate: $(this).data("max-date"),
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
            });
        });

        //Start Batch confirm dialog box
        $('#startBatchForm').submit(function (event) {
            event.preventDefault();
            $(".dialog-confirm").html("Want to start the batch today ?");
            $form = $(this).closest("form");
            $(".dialog-confirm").dialog({
                draggable: false,
                resizable: false,
                modal: true,
                title: "Are you sure?",
                width: 320,
                height: 200,
                buttons: {
                    "Yes": function () {
                        $(this).dialog('close');
                        startBatch(true, $form);
                    },
                    "No": function () {
                        $(this).dialog('close');
                    }
                }
            });
        });
        //Ends here

        //Stop Batch confirm dialog box
        $('#stopBatch').submit(function (event) {
            event.preventDefault();
            $(".dialog-confirm").html("Want to terminate the batch ?");
            $form = $(this).closest("form");
            $(".dialog-confirm").dialog({
                draggable: false,
                resizable: false,
                modal: true,
                title: "Are you sure",
                width: 320,
                height: 200,
                buttons: {
                    "Yes": function () {
                        $(this).dialog('close');
                        endBatch(true, $form);
                    },
                    "No": function () {
                        $(this).dialog('close');
                    }
                }
            });
        });
        //Ends here

        //Delete Batch confirm dialog box
        $('#deleteBatch').submit(function (event) {
            event.preventDefault();
            $(".dialog-confirm").html("Want to remove the batch, students will also be removed from the batch ?");
            $form = $(this).closest("form");
            $(".dialog-confirm").dialog({
                draggable: false,
                resizable: false,
                modal: true,
                title: "Are you sure",
                width: 320,
                height: 200,
                buttons: {
                    "Yes": function () {
                        $(this).dialog('close');
                        deleteBatch(true, $form);
                    },
                    "No": function () {
                        $(this).dialog('close');
                    }
                }
            });
        });
        //Ends here

        //Preview the question paper request details
        $("#raiseQuestionPaperRequestForm button").click(function (event) {
            event.preventDefault();
            if ($("#raiseQuestionPaperRequestForm table input[name=\"level_id[]\"]:checked").length) {
                $form_data = $("#raiseQuestionPaperRequestForm table input[name=\"level_id[]\"]:checked");
                $("#questionPaperRequestPreviewModal").modal("show");
                $("#questionPaperRequestPreviewModal table tbody").html('');
                $.each($($form_data), function () {
                    $("#questionPaperRequestPreviewModal table tbody").append("<tr><td>" + $(this).closest("tr").find("td:eq(1)").text()
                        + "</td><td>" + $(this).closest("tr").find("td:eq(2)").text()
                        + "</td><td>" + $(this).closest("tr").find("td:eq(3)").text()
                        + "</td><td>" + $(this).closest("tr").find("td:eq(4)").text()
                        + "</td><td>" + $(this).closest("tr").find("td:eq(5)").text()
                        + "</td><td>" + $(this).closest("tr").find("td:eq(6)").text()
                        + "</td></tr>");
                });
            } else {
                $(".message-box").show().html("Plese select students..!!");
                setTimeout(function () {
                    $(".message-box").html('').hide()
                }, 2000);
            }
        });
        //Ends here
        //Close modal to make changes in the question paper request
        $("#questionPaperRequestPreviewModal a").click(function (event) {
            event.preventDefault();
            $("#questionPaperRequestPreviewModal").modal("hide");
        });
        //Ends here

        //Submit the request call the function that has ajax
        $("#questionPaperRequestPreviewModal #raise-request").click(function () {
            raiseQuestionPaperRequest($("#raiseQuestionPaperRequestForm"));
        });
        //Ends here


    });



//jQuery - modal popup for reset admin password
    $('#adminEdit #passwordReset').click(function () {
        $('#result').html('');
        $('#adminPasswordResetModal').modal('show');
        $('#adminPasswordResetModal #passwordResetForm')[0].reset();
    });
//Ends here

//jQuery - Search box input on selection of franchisee branch
    $('#studentSearchForm #franchisee_branch').change(function () {
        $('#studentSearchForm input').val('S' + $(this).val()).focus();
    });
//Ends here

});
//Function to preview the image before upload
function readURL(input, default_path) {
    if (input.files && input.files[0]) {
        var reader 			= 	new FileReader();
        var file			=	input.files[0];
        var ValidImageTypes = 	["image/gif", "image/jpeg", "image/png"];
        var file_type	=	file['type'];
        if ($.inArray(file_type, ValidImageTypes) < 0) {
            $('#path-error').html('Only images of types GIF, JPEG and PNG are allowed');
            $('#imgPreview').attr('src', default_path);
        } else if(file.size > 2097152){
            $('#path-error').html('Image size should be less than 2MB');
            $('#imgPreview').attr('src', default_path);
		} else {
            $('#path-error').html('');
            reader.onload = function(e) {
                $('#imgPreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
		}
    }else{
        $('#imgPreview').attr('src', default_path);
	}
}

$("#franchiseeRegisterForm #path").change(function() {
    readURL(this, default_path_value);
});

$("#franchiseeProfileImageUpdateModal #path").change(function () {
    readURL(this, default_path_value);
});


$("#studentProfileImageUpdateForm #path").change(function () {
	readURL(this, default_path_value);
});
// Ends here

function checkNull(value) {
    if(value == null) return "NA";
    else return value;
}
//Get current date
function currentDate() {
    date    =   new Date();
    return date.getFullYear()+"-"+(date.getMonth() <=9 ? "0"+(date.getMonth()+1) : (date.getMonth()+1))+"-"+date.getDate();
}
//Ends here

//Function to apppend font-awesome icons in select menu
function iformat(icon) {
    var originalOption = icon.element;
    return $('<span><i class="fa ' + $(originalOption).data('icon') + '"></i> ' + icon.text + '</span>');
}
//Ends here