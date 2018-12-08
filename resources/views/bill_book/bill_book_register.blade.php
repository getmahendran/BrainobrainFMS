@extends('layouts.bobkar')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-uppercase">{{ __('Fee revision') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{route('bill_book.store')}}" id="addFeeForm">
                            @csrf
                            @if(session()->has('success'))
                                <div class="row">
                                    <div class="alert alert-success col-sm-12 text-center">
                                        <strong><i class="fa fa-check-circle"></i> {{ session()->get('success') }}</strong>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group row">
                                <label for="franchisee_id" class="col-md-4 col-form-label text-md-right">{{__('Franchisee Branch')}}</label>
                                <div class="col-md-6">
                                    <select name="franchisee_id" id="franchisee_id" class="form-control selectpicker">
                                        <option></option>
                                        @foreach($franchisees as $franchisee)
                                        <option {{old('franchisee_id')==$franchisee->id?'selected':''}} value="{{$franchisee->id}}">{{$franchisee->center_code}} - {{$franchisee->franchisee_name}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('franchisee_id'))
                                        <strong><span class="invalid-feedback">{{ $errors->first('franchisee_id') }}</span></strong>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="fee_type_id" class="col-md-4 col-form-label text-md-right">{{__('Fee Type')}}</label>
                                <div class="col-md-6">
                                    <select name="fee_type_id" id="fee_type_id" class="form-control select-picker-no-search">
                                        <option></option>
                                        @foreach($fee_types as $fee_type)
                                        <option {{old('fee_type_id')==$fee_type->id?'selected':''}} value="{{$fee_type->id}}">{{$fee_type->name}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('fee_type_id'))
                                        <strong><span class="invalid-feedback">{{ $errors->first('fee_type_id') }}</span></strong>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="from" class="col-md-4 col-form-label text-md-right">{{__('Receipt No.(From)')}}</label>
                                <div class="col-md-6">
                                    <input name="from" value="{{old('from')}}" id="from" class="form-control" type="text">
                                    @if($errors->has('from'))
                                        <strong><span class="invalid-feedback">{{ $errors->first('from') }}</span></strong>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="till" class="col-md-4 col-form-label text-md-right">{{__('Receipt No.(Till)')}}</label>
                                <div class="col-md-6">
                                    <input value="{{old('till')}}" name="till" id="till" class="form-control" type="text">
                                    @if($errors->has('till'))
                                        <strong><span class="invalid-feedback">{{ $errors->first('till') }}</span></strong>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row ">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn-success btn">Issue Book</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection