
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Page Not Found</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #f7921c;
            font-family: 'Raleway', sans-serif;
            font-weight: bold;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .content {
            text-align: center;
            display: block;
            border: 1px solid #000000;
            padding: 10px;
        }

        .title {
            font-size: 56px;
            padding: 20px;
        }
        .description{
            font-size: 36px;
            padding: 20px;
            color: #01662f;
        }
        .same-row{
            /*display: inline !important;*/
        }
        a{
            font-size: 20px;
            padding: 5px;
        }
        .button {
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }

        .home{
            color: #ffffff;
            background-color: #4CAF50; /* Green */
        }
        .back{
            color: #ffffff;
            background-color: rgba(234, 40, 55, 0.86); /* Green */
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title-img">
            <img alt="BrainOBrain Logo" title="BOB Logo" class="site_logo" height="80" src="{{ asset('logo/logo.png') }}"/>
        </div>
        <div class="title">
            404 - Not Found
        </div>
        <div class="description">
            Sorry, the page you are looking for could not be found.<br>
        </div>
        <div class="same-row">
            <a href="{{route("home")}}" class="home button">Home</a>
            <a href="{{URL::previous()}}" class="back button">Back</a>
        </div>
    </div>
</div>
</body>
</html>
