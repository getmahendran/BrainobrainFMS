@extends('layouts.bobkar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-12 col-lg-10">
            <div class="card">
                <div class="card-header text-uppercase">Batch Registration</div>
                <div class="result"></div>
                <div class="card-body" id="body">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            pageload();

            $(document).on("change","#batch-create-franchisee-select #franchisee_id", function (event) {
                event.preventDefault();
                history.pushState(null, '', $(this).val());
                pageload();
            });

            $(document).on("click","#batch-create-back-btn", function (event) {
                event.preventDefault();
                history.pushState(null, '', $(this).attr("href"));
                $(".result").html('');
                pageload();
            });

            $(document).on("click","#batch-create-add-students", function (event) {
                event.preventDefault();
                history.pushState(null, '', $(this).attr("href"));
                $(".result").html('');
                pageload();
            });
            //Add new batch
            $(document).on("submit","#newBatchForm", function (event) {
                event.preventDefault();
                $.ajax({
                    url:        $(this).attr("action"),
                    method:     "POST",
                    dataType:   "JSON",
                    data:       $(this).serialize()+"&button_action=add_batch",
                    beforeSend: function () {
                        $("#load").show();
                    },
                    success:    function (result) {
                        if(result.errors)
                        {
                            var errorHtml   =   "";
                            $.each(result.errors, function (key, value) {
                                errorHtml   +=  "<div class='alert alert-danger font-weight-bold text-center'><i class='fa fa-exclamation-circle'></i> "+value+"</div>";
                            });
                            $(".result").html(errorHtml);
                        }
                        else
                        {
                            $(".result").html("<div class='alert alert-success font-weight-bold text-center'><i class='fa fa-check-circle'></i> "+result['success']+"</div>");
                            $("#newBatchForm")[0].reset();
                        }

                        $("#load").hide();
                    }
                });
            });

            $(document).on("submit","#batchCreateForm", function (event) {
                event.preventDefault();
                $.ajax({
                    url:        $(this).attr("action"),
                    method:     "POST",
                    dataType:   "JSON",
                    data:       $(this).serialize()+"&button_action=add_students",
                    async:      false,
                    beforeSend: function () {
                        $("#load").show();
                    },
                    success:    function (result) {
                        if(result.errors)
                        {
                            var errorHtml   =   "";
                            $.each(result.errors, function (key, value) {
                                errorHtml   +=  "<div class='alert-danger font-weight-bold text-center alert'><i class='fa fa-exclamation-circle'></i> "+value+"</div>";
                            });
                            $(".result").html(errorHtml);
                        }
                        else
                        {
                            $(".result").html("<div class='alert alert-success font-weight-bold text-center'><i class='fa fa-check-circle'></i> "+result['success']+"</div>");
                            pageload();
                        }
                        $("#load").hide();
                    }
                });
            });        });
        //Ends here

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
    </script>
@endsection