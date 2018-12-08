@extends('layouts.bobkar')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-uppercase">{{ __('Fee revision') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{route('fee.store')}}" id="addFeeForm">
                            @csrf
                            @if(session()->has('success'))
                                <div class="row">
                                    <div class="alert alert-success col-sm-12 text-center">
                                        <strong><i class="fa fa-check-circle"></i> {{ session()->get('success') }}</strong>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group row">
                                <label for="fee_type_id" class="col-md-4 col-form-label text-md-right">{{__('Fee type')}}</label>
                                <div class="col-md-6">
                                    <select name="fee_type_id" id="fee_type_id" class="form-control">
                                        <option selected hidden disabled>--- Select ---</option>
                                        @foreach($fee_types as $fee_type)
                                            <option {{old("fee_type_id")==$fee_type->id ? "selected":""}} value="{{$fee_type->id}}">{{$fee_type->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('fee_type_id'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('fee_type_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="area_id" class="col-md-4 col-form-label text-md-right">{{ __('Rural/Urban') }}</label>
                                <div class="col-md-6">
                                    <select name="area_id" class="form-control" id="area_id" {{old("fee_type_id")!=null ? (old("fee_type_id")==2 ? "":"disabled"):"disabled"}}>
                                        <option selected hidden disabled>--- Select ---</option>
                                        @foreach($areas as $area)
                                            <option value="{{$area->id}}" {{old("area_id")==$area->id?"selected":""}}>{{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('area_id'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('area_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="effective_from" class="col-md-4 col-form-label text-md-right">{{ __('Effective from') }}</label>
                                <div class="col-md-6">
                                    <input readonly value="{{old("effective_from")}}" type="text" data-min-date="{{date("Y-m-d")}}" id="effective_from" name="effective_from" class="date form-control">
                                    @if ($errors->has('effective_from'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('effective_from') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('Price') }}</label>
                                <div class="col-md-6">
                                    <input value="{{old("price")}}" type="text" id="price" name="price" class="form-control">
                                    @if ($errors->has('price'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-success">
                                        {{ __('Add') }}
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
            $("#addFeeForm #fee_type_id").change(function () {
                if($("option:selected",this).val() == 2)
                    $("#addFeeForm #area_id").prop("disabled", false);
                else
                    $("#addFeeForm #area_id").prop("disabled", true).prop('selectedIndex',0);
            });
        });
    </script>
@endsection