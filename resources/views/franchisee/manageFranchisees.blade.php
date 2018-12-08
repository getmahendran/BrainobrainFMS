@extends('layouts.bobkar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-11 p-2 col-sm-12">
            <div class="card">
                <div class="card-header text-uppercase">manage franchisees</div>
                <div class="card-body">
                    <div class="col-12 pt-1 table-block">
                    <table id="franchiseeList" class="table table-striped table-bordered">
                        <thead>
                        <tr class="text-center">
                            <th>Center code</th>
                            <th>Branch Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Status</th>
                            <th>Edit</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($franchisees))
                            @foreach($franchisees as $franchisee)
                                <tr>
                                    <td>{{ $franchisee->center_code }}</td>
                                    <td>{{ $franchisee->franchisee_name }}</td>
                                    <td>{{ $franchisee->email }}</td>
                                    <td>{{ $franchisee->contact_no1 }}</td>
                                    <td class="{{ $franchisee_status[$franchisee->status][1] }}">{{ $franchisee_status[$franchisee->status][0] }}</td>
                                    <td class="text-center"><a href="{{ route('franchisee.edit', $franchisee->id) }}" class="text-success"><i class="fa fa-edit"></i></a></td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td class="text-capitalize text-center" colspan="100%">No records found</td></tr>
                        @endif
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#franchiseeList').DataTable({
                columnDefs: [{"targets": [2,3,4,5], orderable: false}]
            });
        } );
    </script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
@endsection