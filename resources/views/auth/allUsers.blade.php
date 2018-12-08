@extends('layouts/bobkar')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8 col-11 p-2 col-sm-11">
            <div class="card">
                <div class="card-header text-uppercase">{{ __('Manage master users') }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 table-block table-striped pt-1">
                            <table class="table table-bordered dataTable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Mobile</th>
                                    <th>Edit</th>
                                </tr>
                                </thead>
                                <tbody class="master-list">
                                @foreach($admins as $admin)
                                <tr>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->user_name }}</td>
                                    <td>{{ $admin->mobile }}</td>
                                    <td class="text-center"><a class="text-success" href="{{ route('register.edit',$admin->id) }}"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
@section("script")
    <script>
        $(document).ready(function () {
        });
    </script>
@endsection