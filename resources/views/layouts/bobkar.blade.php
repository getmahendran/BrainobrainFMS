<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{asset("css/select2/select2.css")}}">
    <link rel="stylesheet" href="{{asset("css/select2/select2-bootstrap.css")}}">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui-1.12.1/jquery-ui.css') }}"/>
    {{--<link rel="stylesheet" href="{{ asset('css/jquery-ui-1.12.1/jquery-ui.structure.css') }}"/>--}}
    {{--<link rel="stylesheet" href="{{ asset('css/jquery-ui-1.12.1/jquery-ui.theme.css') }}"/>--}}
    <link rel="stylesheet" href="{{ asset('css/font-awesome/css/fontawesome-all.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}"/>
    <title>BrainoBrain</title>
</head>
<body>
    <div id="load" style=""></div>
    <nav class="navbar fixed-top navbar-expand-lg navbar-light">
        <div class="message-box font-weight-bold text-center p-1" style="display: none">Loading...</div>
        <a class="navbar-brand" href="javascript:void(0)">
            <button class="left-toggler"  id="toggle"><i class="fas fa-list fa-lg"></i></button>
            {{--<button class="navbar-toggler" id="toggle" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">--}}
                {{--<span class="navbar-toggler-icon"></span>--}}
            {{--</button>--}}
            <img alt="BrainOBrain Logo" title="BOB Logo" class="site_logo" height="65" src="{{ asset('logo/logo.png') }}"/>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation"><i class="fas fa-angle-double-down fa-lg"></i></button>

        <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav ml-auto">
                    @guest
                        <li class="nav-item item-link-top">
                            <a class="nav-link item-link-top" href="{{ route('login') }}">Login</a>
                        </li>
                    @else
                        <li class="nav-item dropdown item-link-top">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->user_name }} </a>
                            <div class="dropdown-menu" aria-labelledby="dropdown03">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            @guest
            @else
            <nav class="col-lg-2 col-md-2 col-sm-6 col-12 d-none d-sm-none d-md-block sidebar" id="me">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item" style="background-color:#f7921c;">
                        <a class="nav-link text-uppercase" href="http://localhost/brainmanage/public/home" style="color:#ffffff;">DAshboard</a>
                    </li>

                    <li class="nav-item dropdown  list-stack">
                        <a class="nav-link left_drop" href="#" data-toggle="collapse" data-target="#navbarColor04" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">Master<i class="fas fa-chevron-right" style="float:right;padding-top:0.5rem;padding-bottom:0.5rem;"></i></i> </a>
                        <div class="collapse navbar-collapse" id="navbarColor04">
                            <ul class="mr-auto">
                                <a class="nav-link" href="{{ route('register') }}">New Registration</a>
                                <a class="nav-link" href="{{ route('register.index') }}">Manage Masters</a>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item dropdown  list-stack">
                        <a class="nav-link left_drop" href="#" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">Franchisee Master<i class="fas fa-chevron-right" style="float:right;padding-top:0.5rem;padding-bottom:0.5rem;"></i></a>
                        <div class="collapse navbar-collapse" id="navbarColor03">
                            <ul class="mr-auto">
                                <a class="nav-link" href="{{ route('franchisee.create') }}">New Registration</a>
                                <a class="nav-link" href="{{ route('franchisee.index') }}">Manage Franchisee</a>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item dropdown  list-stack">
                        <a class="nav-link left_drop" href="#" data-toggle="collapse" data-target="#navbarColor05" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">Faculty Master<i class="fas fa-chevron-right" style="float:right;padding-top:0.5rem;padding-bottom:0.5rem;"></i></i> </a>
                        <div class="collapse navbar-collapse" id="navbarColor05">
                            <ul class="mr-auto">
                                <a class="nav-link" href="{{ route('faculty.create') }}">New Registration</a>
                                <a class="nav-link" href="{{ route('faculty.index') }}">Manage Faculties</a>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item dropdown  list-stack">
                        <a class="nav-link left_drop" href="#" data-toggle="collapse" data-target="#navbarColor06" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">Fee Master<i class="fas fa-chevron-right" style="float:right;padding-top:0.5rem;padding-bottom:0.5rem;"></i></i> </a>
                        <div class="collapse navbar-collapse" id="navbarColor06">
                            <ul class="mr-auto">
                                <a class="nav-link" href="{{ route('fee.create') }}">New Fee Structure</a>
                                <a class="nav-link" href="{{ route('fee.index') }}">Manage Fee</a>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item dropdown  list-stack">
                        <a class="nav-link left_drop" href="#" data-toggle="collapse" data-target="#navbarColor07" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">Course Master<i class="fas fa-chevron-right" style="float:right;padding-top:0.5rem;padding-bottom:0.5rem;"></i></i> </a>
                        <div class="collapse navbar-collapse" id="navbarColor07">
                            <ul class="mr-auto">
                                <a class="nav-link" href="{{ route('course.create') }}">Program/Course Registration</a>
                                <a class="nav-link" href="{{ route('program.index') }}">Manage Programs/Courses</a>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item dropdown  list-stack">
                        <a class="nav-link left_drop" href="#" data-toggle="collapse" data-target="#navbarColor08" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">Student Master<i class="fas fa-chevron-right" style="float:right;padding-top:0.5rem;padding-bottom:0.5rem;"></i></i> </a>
                        <div class="collapse navbar-collapse" id="navbarColor08">
                            <ul class="mr-auto">
                                <a class="nav-link" href="{{ route('fee_collect.pay') }}">Fee Collection</a>
                                <a class="nav-link" href="{{ route('student.create') }}">New Registration</a>
                                <a class="nav-link" href="{{ route('student.index') }}">Manage Students</a>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item dropdown  list-stack">
                        <a class="nav-link left_drop" href="#" data-toggle="collapse" data-target="#navbarColor9" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">Bill Book Master<i class="fas fa-chevron-right" style="float:right;padding-top:0.5rem;padding-bottom:0.5rem;"></i></i> </a>
                        <div class="collapse navbar-collapse" id="navbarColor9">
                            <ul class="mr-auto">
                                <a class="nav-link" href="{{ route('bill_book.create') }}">Issue Bill Book</a>
                                <a class="nav-link" href="{{ route('bill_book.index') }}">Manage Bill Books</a>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item dropdown list-stack">
                        <a class="nav-link left_drop" href="#" data-toggle="collapse" data-target="#navbarColor10" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">Batch Master<i class="fas fa-chevron-right" style="float:right;padding-top:0.5rem;padding-bottom:0.5rem;"></i></i> </a>
                        <div class="collapse navbar-collapse" id="navbarColor10">
                            <ul class="mr-auto">
                                <a class="nav-link" href="{{ route('batch.create') }}">New Batch Registration/Add Students</a>
                                <a class="nav-link" href="{{ route('batch.index') }}">Manage Batches</a>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item dropdown  list-stack">
                        <a class="nav-link left_drop" href="#" data-toggle="collapse" data-target="#navbarColor11" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">Question Paper Master<i class="fas fa-chevron-right" style="float:right;padding-top:0.5rem;padding-bottom:0.5rem;"></i></i> </a>
                        <div class="collapse navbar-collapse" id="navbarColor11">
                            <ul class="mr-auto">
                                <a class="nav-link" href="{{ route('questionPaperRequest.create') }}">New Request</a>
                                <a class="nav-link" href="{{ route('questionPaperRequest.index') }}">Manage Requests</a>
                            </ul>
                        </div>
                    </li>
                </ul>
            </nav>
            @endguest
            <main class="offset-lg-2 offset-md-2 col-lg-10 col-md-10 col-sm-12 col-xs-6 pt-4" id="content">
                @yield('content')
            </main>

        </div>
    </div>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{asset("js/select2/select2.js")}}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui-1.12.1/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/style.js') }}"></script>
    <script src="{{ asset('js/easyJS.js') }}"></script>
    <script>
        var default_path_value        =       '{{ asset('images/initial_profile_pic.jpg') }}';
    </script>
    @yield ('script')
</body>
</html>