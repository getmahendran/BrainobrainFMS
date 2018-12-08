@extends('layouts.bobkar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-10 p-2 col-sm-12">
            <div class="card">
                <div class="card-header text-uppercase">{{ __('New faculty registration') }}</div>
                <div class="card-body">
                    <label for="" class="font-weight-bold">Do you have a faculty account ?</label>
                    <div class="btn-group ml-5">
                        <a class="btn btn-sm text-uppercase btn-outline-secondary question" data-content="add_existing" href="{{ route('faculty.create') }}?button_action=add_existing">Yes</a>
                        <a class="btn btn-sm text-uppercase btn-outline-secondary question" data-content="add_new" href="{{ route('faculty.create')}}?button_action=add_new">No</a>
                    </div>
                    <hr>
                    <div id="body">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>

        var default_path_value        =       '{{ asset('images/initial_profile_pic.jpg') }}';
        $(document).ready(function () {

            $('a[href = "'+window.location+'"]').addClass('active');
            pageload($('a[href = "'+window.location+'"]'));

            //Load register forms appropriately
            $(".question").click(function () {
                event.preventDefault();
                history.pushState(null, '', $(this).attr("href"));
                $(".question").removeClass("active");
                $(this).addClass("active");
                pageload();
            });
            //Ends here

        });

        //Ajax load form elements for married is true
        $(document).on("change", "#facultyRegisterForm .married-check" ,function (event) {
            event.preventDefault();
            if($(this).val() == 1)
                marriedDisplay($(this).data("url"));
            else
                $("#facultyRegisterForm #married-form").html("");
        });
        // //Ends here

        $(document).on("change","#faculty_account_id", function (event) {
            event.preventDefault();
            $.ajax({
                url:        $(this).val(),
                method:     "GET",
                beforeSend: function () {
                    $("#load").show();
                },
                success:    function (result) {
                    $("#body").html(result);
                    declare_select_picker_ajax($(".select-picker-ajax#faculty_account_id"));
                    $("#load").hide();
                }
            });
        });

        $(document).on("submit","#facultyRegisterForm",function (event) {
            event.preventDefault();
            var form        =   $(this);
            var formData    =   new FormData(this);
            $.ajax({
                url:            $(this).attr('action'),
                type:           "POST",
                dataType:       "JSON",
                data:           formData,
                cache:          false,
                contentType:    false,
                processData:    false,
                beforeSend: function () {
                    $("#load").show();
                },
                success:    function (result) {
                    $("#load").hide();
                    if(result.errors)
                    {
                        var error_html  =   '';
                        $.each(result.errors, function (key, value) {
                            error_html  +=  '<div class="alert font-weight-bold alert-danger"><i class="fa fa-exclamation-circle"></i> '+value+'</div>';
                        });
                        $(".result").html(error_html);
                        window.scrollTo(0,0);
                    }
                    if(result.success)
                    {
                        $(".result").html("<div class='alert alert-success font-weight-bold'><i class='fa fa-check-circle'></i> "+result['success']+"</div>");
                        form[0].reset();
                        window.scroll(0,0);
                    }
                }
            });
        });

        $(document).on('submit','#existingFacultyRegisterForm', function (event) {
            event.preventDefault();
            $.ajax({
                url:        $(this).attr('action'),
                type:       'POST',
                dataType:   'JSON',
                data:       $(this).serialize(),
                async:      true,
                beforeSend: function () {
                    $("#load").show();
                },
                success:    function (result)
                {
                    if(result.errors)
                    {
                        var error_html  =   "";
                        $.each(result.errors, function (key, value) {
                            error_html  +=  "<div class='alert-danger alert font-weight-bold'><i class='fa fa-exclamation-circle'></i>  "+value+"</div>";
                        });
                        $("#result").html(error_html);
                    }
                    else
                    {
                        $('#existingFacultyRegisterForm')[0].reset();
                        $('#result').html('<div class="alert alert-success font-weight-bold" role="alert"><i class="fa fa-check-circle"></i> '+result['success']+'<button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                            '    <span aria-hidden="true">&times;</span>\n' +
                            '  </button></div>');
                    }
                    $("#load").hide();
                }
            });
        });

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
                    declare_select_picker_ajax($(".select-picker-ajax#faculty_account_id"));
                }
            });
        }

        function declare_select_picker_ajax(element) {
            $(element).select2({
                theme:  'bootstrap',
                placeholder:    '--- Select ---',
                minimumInputLength: 5,
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
        }

    </script>
@endsection