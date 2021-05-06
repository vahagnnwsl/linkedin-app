@extends('layouts.app')
@push('css')
    <style>
        body, html {
            height: 100%!important;
            margin: 0!important;;
        }

        .bg {
            /* The image used */
            background-image: url("/banner_2.jpg");

            /* Full height */
            height: 100%!important;

            /* Center and scale the image nicely */
            background-position: center!important;
            background-repeat: no-repeat!important;
            background-size: cover!important;
        }
        .tim{
            font-size: 18px;
            border-radius: 100px;
            width: 50px;
            height: 50px; background-image: url(/logo.png);
            background-repeat: no-repeat!important;
            background-size: cover!important;

        }
    </style>

@endpush
@section('content')
    <div class="row" style="position: relative;width: 95%">

        @auth()
            <a href="{{route('dashboard.index')}}"  class="tim" style="position: absolute;top: 10px;right: -10px" title="Go to dashboard"></a>
        @else
            <a href="{{route('login')}}"  class="tim" style="position: absolute;top: 10px;right: -10px" title="Go to login"></a>
        @endauth

    </div>
    <div class="bg"></div>

@endsection
