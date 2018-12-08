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
    <link rel="stylesheet" href="{{ asset('css/font-awesome/css/fontawesome-all.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}"/>
    <title>@yield("title")</title>
    <style>
        .navbar
        {
            background: #893dcd !important;
            border-bottom: 1px solid #f5f7f8;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand p-0 m-0" href="javascript:void(0)">
                <img src="{{asset('logo/rrdtr.png')}}" width="50" height="30" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{asset("js/select2/select2.js")}}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui-1.12.1/jquery-ui.js') }}"></script>
</body>
</html>