@extends('layouts.bobkar')
@section('content')
    <div class="student-promote-form-content">
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-md-12">
            <div class="card">
                <div class="card-header text-uppercase"><b>{{$batch->batch_name}}</b> </div>
                <div class="col-12 text-center result"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-auto">
                            <a href="{{route('batch.index',['franchisee_id'=>request('franchisee_id')])}}" class="nounderline text-muted"><i class="fa fa-2x fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="row mr-1 ml-2 justify-content-end">
                        <form action="{{route('batch.destroy', $batch->id)}}" method="POST" class="remove-batch">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="table-button table-button-danger"><i class="fa fa-trash-alt"></i> Delete</button>
                        </form>
                    </div>
                    <br>
                    <form action="{{route('batch.update',$batch->id)}}" method="POST" class="update-batch">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="franchisee_id" value="{{request('franchisee_id')}}">
                        <div class="form-group row">
                            <label for="faculty_account_id" class="text-md-right col-form-label col-md-4">Faculty :-</label>
                            <div class="col-md-6">
                                <select name="faculty_account_id" id="faculty_account_id" class="form-control selectpicker">
                                    <option value=""></option>
                                    @foreach($faculties as $faculty)
                                        <option {{$batch->faculty_account_id==$faculty->id?"selected":""}} value="{{$faculty->id}}">{{$faculty->faculty_code}} - {{$faculty->faculty->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="batch_name" class="text-md-right col-form-label col-md-4">Batch Name :-</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{$batch->batch_name}}" name="batch_name" id="batch_name">
                            </div>
                        </div>
                        <input type="hidden" name="button_action" value="update_batch">
                        <div class="form-group row">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                <button class="btn btn-warning">Update</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="ftab-container pl-0 pb-0 pr-0">
                            <ul class="pt-2 pb-2 pr-0 batch-action-tabs">
                                <li class="pl-3 pr-3 pt-2 pb-2 batch-task-tab" data-url="{{route('batch.edit',$batch->id)}}?franchisee_id={{request('franchisee_id')}}&button_action=students_list">Students List</li>
                                <li class="pl-3  pr-3 pt-2 pb-2 batch-task-tab" data-url="{{route('batch.edit',$batch->id)}}?franchisee_id={{request('franchisee_id')}}&button_action=batch_transfer">Batch Transfer</li>
                                <li class="pl-3  pr-3 pt-2 pb-2 batch-task-tab" data-url="{{route('batch.edit',$batch->id)}}?franchisee_id={{request('franchisee_id')}}&button_action=student_promotion">Student Promotion</li>
                            </ul>
                            <div class="ftab-body p-0 col-12" id="body">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
    $(document).ready(function () {

        var field = 'button_action';
        var url = window.location.href;
        if(url.indexOf('&' + field + '=') != -1)
            $(".batch-task-tab[data-url='"+window.location+"']").addClass('active');
        else
        {
            element     =   $(".batch-task-tab:first");
            $(element).addClass("active");
            history.pushState(null,"", element.data("url"));
        }
        pageload();

        $(document).on('submit','.update-batch', function (event) {
            event.preventDefault();
            $.ajax({
                url:        $(this).attr("action"),
                method:     "PUT",
                dataType:   "JSON",
                data:       $(this).serialize(),
                beforeSend: function () {
                    $("#load").show();
                },
                success:    function (result) {
                    $("#load").hide();
                    if(result.errors)
                    {
                        let error_html  =   "";
                        $.each(result.errors, function (key, value) {
                            error_html      =   "<div class='alert-danger alert font-weight-bold'><i class='fa fa-exclamation-circle'></i> "+value+"</div>";
                        });
                        $(".result").html(error_html);
                    }
                    else
                    {
                        $(".result").html("<div class='alert alert-success font-weight-bold'><i class='fa fa-check-circle'></i> "+result['success']+"</div>");
                        setTimeout(function () {
                            $(".result").html('');
                        },2500);
                        pageload();
                    }
                }
            });
        });

        $(document).on('submit','.update-student-progress', function (event) {
            event.preventDefault();
            $.ajax({
                url:        $(this).attr("action"),
                method:     "PUT",
                dataType:   "JSON",
                data:       $(this).serialize(),
                beforeSend: function () {
                    $("#load").show();
                },
                success:    function (result) {
                    $("#load").hide();
                    if(result.errors)
                    {
                        let error_html  =   "";
                        $.each(result.errors, function (key, value) {
                            error_html      =   "<div class='alert-danger alert font-weight-bold'><i class='fa fa-exclamation-circle'></i> "+value+"</div>";
                        });
                        $(".result").html(error_html);
                    }
                    else
                    {
                        $(".result").html("<div class='alert alert-success font-weight-bold'><i class='fa fa-check-circle'></i> "+result['success']+"</div>");
                        setTimeout(function () {
                            $('.result').html('');
                        }, 3000);
                        pageload();
                    }
                }
            });
        });

        $(document).on("click", ".batch-task-tab", function (event) {
            event.preventDefault();
            let element    =   $(this);
            history.pushState(null, '', element.data("url"));
            $(".batch-task-tab").removeClass("active");
            $(".batch-task-tab[data-url='"+window.location+"']").addClass('active');
            pageload();
        });

        $(document).on("submit",".remove-batch", function (event) {
            event.preventDefault();
            $.ajax({
                url:        $(this).attr("action"),
                method:     "DELETE",
                dataType:   "JSON",
                data:       $(this).serialize(),
                beforeSend: function () {
                    $("#load").show();
                },
                success:    function (result) {
                    $("#load").hide();
                    if(result.errors)
                    {
                        let error_html  =   "";
                        $.each(result.errors, function (key, value) {
                            error_html      =   "<div class='alert-danger alert font-weight-bold'><i class='fa fa-exclamation-circle'></i> "+value+"</div>";
                        });
                        $(".result").html(error_html);
                    }
                    else
                    {
                        $(".result").html("<div class='alert alert-success font-weight-bold'><i class='fa fa-exclamation-circle'></i> "+result['success']+"</div>");
                        window.history.back();
                    }
                }
            });
        });

        $(document).on("click",".promote-student",function (event) {
            event.preventDefault();
            $.ajax({
                url:        $(this).attr('href'),
                method:     "GET",
                data:       "button_action=student_promotion_form",
                beforeSend: function () {
                    $("#load").show();
                },
                success:    function (result) {
                    $("#load").hide();
                    $(".student-promote-form-content").html(result);
                    $("#promoteStudentModal").modal("show");
                }
            });
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
            }
        });
    }
    </script>
@endsection