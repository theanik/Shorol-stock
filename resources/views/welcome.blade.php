<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Shorol | Stock</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: "Courier New", Courier, monospace;
                font-weight: 200;
                height: 100vh;
                margin: 0;
                font-weight: bold;
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

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

             a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 17px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
       
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md text-success">
                    Sh<span class="text-danger">o</span>rol Stock
                </div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                            @if (Route::has('login'))
                            <div class="font-weight-bold">
                                @auth
                                    <a class="btn btn-primary" href="{{ url('/dashboard') }}">Dashboard</a>
                                @else
                                    <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
            
                                    {{-- @if (Route::has('register'))
                                        <a href="{{ route('register') }}">Register</a>
                                    @endif --}}
                                @endauth
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4"></div>
                </div>

            </div>
        </div>
    </body>
</html>
