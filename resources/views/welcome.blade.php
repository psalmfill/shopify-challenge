<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
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

        .links>a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        // Within style tags in your html file
        * {
            box-sizing: border-box;
        }

        .grid:after {
            content: '';
            display: block;
            clear: both;
        }

        .grid-sizer,
        .grid-item {
            width: 33.333%;
        }

        @media (max-width: 575px) {

            .grid-sizer,
            .grid-item {
                width: 100%;
            }
        }

        @media (min-width: 576px) and (max-width: 767px) {

            .grid-sizer,
            .grid-item {
                width: 50%;
            }
        }

        /* To change the amount of columns on larger devices, uncomment the code below */

        /* @media (min-width: 768px) and (max-width: 991px) {
  .grid-sizer,
  .grid-item {
    width: 33.333%;
  }
}
@media (min-width: 992px) and (max-width: 1199px) {
  .grid-sizer,
  .grid-item {
    width: 25%;
  }
}
@media (min-width: 1200px) {
  .grid-sizer,
  .grid-item {
    width: 20%;
  }
} */

        .grid-item {
            float: left;
        }

        .grid-item img {
            display: block;
            max-width: 100%;
        }

    </style>
</head>

<body>
    <div class="flex-center position-ref ">
        @if (Route::has('login'))
            <div class="top-right links">
                @auth
                    <a href="{{ url('/home') }}">Home</a>
                @else
                    <a href="{{ route('login') }}">Login</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                @endauth
            </div>
        @endif
    </div>

    <div class="container">
        <div class="container-fluid">

            <h1 class="my-4 font-weight-bold">Image Repository</h1>
            <hr>

            <div class="d-flex justify-content-center m-5 bg-light p-5">
                <form class="form-inline w-75">
                    <div class="input-group w-100">
                        <input class="form-control w-75 rounded-0 mr-sm-2" name="search"
                            value="{{ request()->query('search') }}" type="search" placeholder="Search"
                            aria-label="Search">
                        <button class="btn btn-outline-secoundary rounded-0 my-2 my-sm-0" type="submit">Search</button>
                    </div>
                </form>
            </div>

            <div class="grid">
                <div class="grid-sizer"></div>
                @foreach ($images as $image)
                    <div class="grid-item p-1 mt-3">
                    <img src="{{ $image->url }}" onclick="showImage('{{$image->url}}')" />
                        <h5 class="text-secondary">{{$image->caption}} - <small>{{$image->user->name}}</small></h5>
                    </div>
                @endforeach

            </div>
            <div class="float-left">
                {{$images->links()}}
            </div>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <script>
        // init Masonry
        // var $grid = $('.grid').masonry({
        //     itemSelector: '.grid-item',
        //     percentPosition: true,
        //     columnWidth: '.grid-sizer'
        // });

        // // layout Masonry after each image loads
        // $grid.imagesLoaded().progress(function() {
        //     $grid.masonry();
        // });

        function showImage(url){
            window.location = url;
        }
    </script>
</body>

</html>
