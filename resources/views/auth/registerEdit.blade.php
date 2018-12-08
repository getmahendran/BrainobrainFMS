@extends('layouts/bobkar')

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="adminPasswordResetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">New Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="result"></div>
                    <form id="passwordResetForm" method="POST" action="/brainmanage/public/register/{{ $usr->id }}/reset">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group">
                            <label for="password" class="col-form-label">New Password</label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                        <div class="form-group">
                            <label for="password-confirm" class="col-form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password-confirm" id="password-confirm">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-danger">Reset</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Model Ends here-->

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="text-capitalize card-header">{{ __('Edit Details of ') }} {{ $usr->user_name }}</div>
                    <div class="card-body">
                        <form method="POST" id="adminEdit" action="/brainmanage/public/register/{{$usr->id}}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row">
                                <div class="col-12"><a href="#" id="passwordReset" class="float-right nounderline"><i class="fas fa-key"></i> Reset password</a></div>
                            </div>
                            <br>
                            <div class="form-group row">
                                <label for="name"  class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $usr->name }}" required>
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $usr->email }}" required>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Mobile Number') }}</label>
                                <div class="col-md-6">
                                    <input id="mobile" type="number" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" name="mobile" value="{{ $usr->mobile }}" required>
                                    @if ($errors->has('mobile'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('mobile') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-success">
                                        {{ __('Update') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            /*AJAX update admin details*/
            $('#adminEdit').submit(function (event) {
                event.preventDefault();
                var form_data = $(this).serialize();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'PUT',
                    dataType: 'JSON',
                    data: form_data,
                    beforeSend: function () {
                        $('.message-box').show().html('Updating');
                    },
                    success: function (result) {
                        $('.message-box').html(result);
                        setTimeout(function() { $(".message-box").hide(); }, 1000);
                    }
                });
            });
            /*Ends here*/

            /*AJAX Reset admin password*/
            $('#adminPasswordResetModal #passwordResetForm').submit(function (event) {
                event.preventDefault();
                var errorHtml='';
                $.ajax({
                    url:        $(this).attr('action'),
                    type:       'PUT',
                    dataType:   'JSON',
                    data:       $(this).serialize(),
                    beforeSend: function () {
                        $('#result').html('<div class="text-center"><i class="fa fa-spinner"></i></div>');
                    },
                    success:function (result) {
                        if(result.errors)
                        {
                            $.each(result.errors, function (key, value) {
                                errorHtml+='<div class="alert alert-danger font-weight-bold"> <i class="fa fa-exclamation-circle"></i> '+value+'</div>';
                            });
                            $('#adminPasswordResetModal #passwordResetForm')[0].reset();
                            $('#result').html(errorHtml);
                        }
                        else
                        {
                            $('#result').html('<div class="alert alert-success font-weight-bold"><i class="fa fa-check-circle"></i> '+result["success"]+'</div>');
                            $('#adminPasswordResetModal #passwordResetForm')[0].reset();
                        }
                    },
                });
            });
            /*Ends here*/
        });
    </script>
@endsection