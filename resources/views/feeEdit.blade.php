@extends('layouts/bobkar')

@section('content')
    <div class="container">
        <h1 class="text-uppercase text-center main_head">Edit Fee</h1><hr/>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit Details') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('fee.update',$fee_obj->id) }}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            @if($errors->any())
                                <div class="row">
                                    <div class="alert alert-success col-sm-12 text-center">
                                        <strong>{{ $errors->first() }}</strong>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Fee Name/Fee For') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="fee_for" value="{{ $fee_obj->fee_for }}" required autofocus>
                                    @if ($errors->has('fee_for'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('fee_for') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Admission Fee') }}</label>

                                <div class="col-md-6">
                                    <input id="text" type="text" class="form-control" name="admission_fee" value="{{ $fee_obj->admission_fee }}" required>

                                    @if ($errors->has('admission_fee'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('admission_fee') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Monthly Fee') }}</label>

                                <div class="col-md-6">
                                    <input id="text" type="text" class="form-control" name="monthly_fee" value="{{ $fee_obj->monthly_fee }}" required>

                                    @if ($errors->has('monthly_fee'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('monthly_fee') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Exam Fee') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="text" class="form-control" name="exam_fee" value="{{ $fee_obj->exam_fee }}" required>

                                    @if ($errors->has('exam_fee'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('exam_fee') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-info">
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
