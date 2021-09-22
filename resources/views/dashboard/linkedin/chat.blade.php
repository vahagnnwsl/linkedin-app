@extends('dashboard.layouts')


@push('css')
    <style type="text/css">svg:not(:root).svg-inline--fa {
            overflow: visible;
        }

        .svg-inline--fa {
            display: inline-block;
            font-size: inherit;
            height: 1em;
            overflow: visible;
            vertical-align: -0.125em;
        }
        .svg-inline--fa.fa-lg {
            vertical-align: -0.225em;
        }
        .svg-inline--fa.fa-w-1 {
            width: 0.0625em;
        }
        .svg-inline--fa.fa-w-2 {
            width: 0.125em;
        }
        .svg-inline--fa.fa-w-3 {
            width: 0.1875em;
        }
        .svg-inline--fa.fa-w-4 {
            width: 0.25em;
        }
        .svg-inline--fa.fa-w-5 {
            width: 0.3125em;
        }
        .svg-inline--fa.fa-w-6 {
            width: 0.375em;
        }
        .svg-inline--fa.fa-w-7 {
            width: 0.4375em;
        }
        .svg-inline--fa.fa-w-8 {
            width: 0.5em;
        }
        .svg-inline--fa.fa-w-9 {
            width: 0.5625em;
        }
        .svg-inline--fa.fa-w-10 {
            width: 0.625em;
        }
        .svg-inline--fa.fa-w-11 {
            width: 0.6875em;
        }
        .svg-inline--fa.fa-w-12 {
            width: 0.75em;
        }
        .svg-inline--fa.fa-w-13 {
            width: 0.8125em;
        }
        .svg-inline--fa.fa-w-14 {
            width: 0.875em;
        }
        .svg-inline--fa.fa-w-15 {
            width: 0.9375em;
        }
        .svg-inline--fa.fa-w-16 {
            width: 1em;
        }
        .svg-inline--fa.fa-w-17 {
            width: 1.0625em;
        }
        .svg-inline--fa.fa-w-18 {
            width: 1.125em;
        }
        .svg-inline--fa.fa-w-19 {
            width: 1.1875em;
        }
        .svg-inline--fa.fa-w-20 {
            width: 1.25em;
        }
        .svg-inline--fa.fa-pull-left {
            margin-right: 0.3em;
            width: auto;
        }
        .svg-inline--fa.fa-pull-right {
            margin-left: 0.3em;
            width: auto;
        }
        .svg-inline--fa.fa-border {
            height: 1.5em;
        }
        .svg-inline--fa.fa-li {
            width: 2em;
        }
        .svg-inline--fa.fa-fw {
            width: 1.25em;
        }

        .fa-layers svg.svg-inline--fa {
            bottom: 0;
            left: 0;
            margin: auto;
            position: absolute;
            right: 0;
            top: 0;
        }

        .fa-layers {
            display: inline-block;
            height: 1em;
            position: relative;
            text-align: center;
            vertical-align: -0.125em;
            width: 1em;
        }
        .fa-layers svg.svg-inline--fa {
            -webkit-transform-origin: center center;
            transform-origin: center center;
        }

        .fa-layers-counter, .fa-layers-text {
            display: inline-block;
            position: absolute;
            text-align: center;
        }

        .fa-layers-text {
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            -webkit-transform-origin: center center;
            transform-origin: center center;
        }

        .fa-layers-counter {
            background-color: #ff253a;
            border-radius: 1em;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            color: #fff;
            height: 1.5em;
            line-height: 1;
            max-width: 5em;
            min-width: 1.5em;
            overflow: hidden;
            padding: 0.25em;
            right: 0;
            text-overflow: ellipsis;
            top: 0;
            -webkit-transform: scale(0.25);
            transform: scale(0.25);
            -webkit-transform-origin: top right;
            transform-origin: top right;
        }

        .fa-layers-bottom-right {
            bottom: 0;
            right: 0;
            top: auto;
            -webkit-transform: scale(0.25);
            transform: scale(0.25);
            -webkit-transform-origin: bottom right;
            transform-origin: bottom right;
        }

        .fa-layers-bottom-left {
            bottom: 0;
            left: 0;
            right: auto;
            top: auto;
            -webkit-transform: scale(0.25);
            transform: scale(0.25);
            -webkit-transform-origin: bottom left;
            transform-origin: bottom left;
        }

        .fa-layers-top-right {
            right: 0;
            top: 0;
            -webkit-transform: scale(0.25);
            transform: scale(0.25);
            -webkit-transform-origin: top right;
            transform-origin: top right;
        }

        .fa-layers-top-left {
            left: 0;
            right: auto;
            top: 0;
            -webkit-transform: scale(0.25);
            transform: scale(0.25);
            -webkit-transform-origin: top left;
            transform-origin: top left;
        }

        .fa-lg {
            font-size: 1.3333333333em;
            line-height: 0.75em;
            vertical-align: -0.0667em;
        }

        .fa-xs {
            font-size: 0.75em;
        }

        .fa-sm {
            font-size: 0.875em;
        }

        .fa-1x {
            font-size: 1em;
        }

        .fa-2x {
            font-size: 2em;
        }

        .fa-3x {
            font-size: 3em;
        }

        .fa-4x {
            font-size: 4em;
        }

        .fa-5x {
            font-size: 5em;
        }

        .fa-6x {
            font-size: 6em;
        }

        .fa-7x {
            font-size: 7em;
        }

        .fa-8x {
            font-size: 8em;
        }

        .fa-9x {
            font-size: 9em;
        }

        .fa-10x {
            font-size: 10em;
        }

        .fa-fw {
            text-align: center;
            width: 1.25em;
        }

        .fa-ul {
            list-style-type: none;
            margin-left: 2.5em;
            padding-left: 0;
        }
        .fa-ul > li {
            position: relative;
        }

        .fa-li {
            left: -2em;
            position: absolute;
            text-align: center;
            width: 2em;
            line-height: inherit;
        }

        .fa-border {
            border: solid 0.08em #eee;
            border-radius: 0.1em;
            padding: 0.2em 0.25em 0.15em;
        }

        .fa-pull-left {
            float: left;
        }

        .fa-pull-right {
            float: right;
        }

        .fa.fa-pull-left,
        .fas.fa-pull-left,
        .far.fa-pull-left,
        .fal.fa-pull-left,
        .fab.fa-pull-left {
            margin-right: 0.3em;
        }
        .fa.fa-pull-right,
        .fas.fa-pull-right,
        .far.fa-pull-right,
        .fal.fa-pull-right,
        .fab.fa-pull-right {
            margin-left: 0.3em;
        }

        .fa-spin {
            -webkit-animation: fa-spin 2s infinite linear;
            animation: fa-spin 2s infinite linear;
        }

        .fa-pulse {
            -webkit-animation: fa-spin 1s infinite steps(8);
            animation: fa-spin 1s infinite steps(8);
        }

        @-webkit-keyframes fa-spin {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes fa-spin {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        .fa-rotate-90 {
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=1)";
            -webkit-transform: rotate(90deg);
            transform: rotate(90deg);
        }

        .fa-rotate-180 {
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=2)";
            -webkit-transform: rotate(180deg);
            transform: rotate(180deg);
        }

        .fa-rotate-270 {
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=3)";
            -webkit-transform: rotate(270deg);
            transform: rotate(270deg);
        }

        .fa-flip-horizontal {
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0, mirror=1)";
            -webkit-transform: scale(-1, 1);
            transform: scale(-1, 1);
        }

        .fa-flip-vertical {
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=2, mirror=1)";
            -webkit-transform: scale(1, -1);
            transform: scale(1, -1);
        }

        .fa-flip-both, .fa-flip-horizontal.fa-flip-vertical {
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=2, mirror=1)";
            -webkit-transform: scale(-1, -1);
            transform: scale(-1, -1);
        }

        :root .fa-rotate-90,
        :root .fa-rotate-180,
        :root .fa-rotate-270,
        :root .fa-flip-horizontal,
        :root .fa-flip-vertical,
        :root .fa-flip-both {
            -webkit-filter: none;
            filter: none;
        }

        .fa-stack {
            display: inline-block;
            height: 2em;
            position: relative;
            width: 2.5em;
        }

        .fa-stack-1x,
        .fa-stack-2x {
            bottom: 0;
            left: 0;
            margin: auto;
            position: absolute;
            right: 0;
            top: 0;
        }

        .svg-inline--fa.fa-stack-1x {
            height: 1em;
            width: 1.25em;
        }
        .svg-inline--fa.fa-stack-2x {
            height: 2em;
            width: 2.5em;
        }

        .fa-inverse {
            color: #fff;
        }

        .sr-only {
            border: 0;
            clip: rect(0, 0, 0, 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px;
        }

        .sr-only-focusable:active, .sr-only-focusable:focus {
            clip: auto;
            height: auto;
            margin: 0;
            overflow: visible;
            position: static;
            width: auto;
        }

        .svg-inline--fa .fa-primary {
            fill: var(--fa-primary-color, currentColor);
            opacity: 1;
            opacity: var(--fa-primary-opacity, 1);
        }

        .svg-inline--fa .fa-secondary {
            fill: var(--fa-secondary-color, currentColor);
            opacity: 0.4;
            opacity: var(--fa-secondary-opacity, 0.4);
        }

        .svg-inline--fa.fa-swap-opacity .fa-primary {
            opacity: 0.4;
            opacity: var(--fa-secondary-opacity, 0.4);
        }

        .svg-inline--fa.fa-swap-opacity .fa-secondary {
            opacity: 1;
            opacity: var(--fa-primary-opacity, 1);
        }

        .svg-inline--fa mask .fa-primary,
        .svg-inline--fa mask .fa-secondary {
            fill: black;
        }

        .fad.fa-inverse {
            color: #fff;
        }

        .chat-messages {
            display: flex;
            flex-direction: column;
            max-height: 520px;
            min-height: 500px;
            overflow-y: scroll;
        }
        .chat-list {
            display: flex;
            flex-direction: column;
            max-height: 520px;
            min-height: 500px;
            overflow-y: scroll;
        }
        .chat-message-right {
            flex-direction: row-reverse;
            margin-left: auto;
        }
        .chat-message-left {
            margin-right: auto;
        }
    </style>
{{--    <style>--}}
{{--        .chat-search-box {--}}
{{--            -webkit-border-radius: 3px 0 0 0;--}}
{{--            -moz-border-radius: 3px 0 0 0;--}}
{{--            border-radius: 3px 0 0 0;--}}
{{--            padding: .75rem 1rem;--}}
{{--        }--}}

{{--        .chat-search-box .input-group .form-control {--}}
{{--            -webkit-border-radius: 2px 0 0 2px;--}}
{{--            -moz-border-radius: 2px 0 0 2px;--}}
{{--            border-radius: 2px 0 0 2px;--}}
{{--            border-right: 0;--}}
{{--        }--}}

{{--        .chat-search-box .input-group .form-control:focus {--}}
{{--            border-right: 0;--}}
{{--        }--}}

{{--        .chat-search-box .input-group .input-group-btn .btn {--}}
{{--            -webkit-border-radius: 0 2px 2px 0;--}}
{{--            -moz-border-radius: 0 2px 2px 0;--}}
{{--            border-radius: 0 2px 2px 0;--}}
{{--            margin: 0;--}}
{{--        }--}}

{{--        .chat-search-box .input-group .input-group-btn .btn i {--}}
{{--            font-size: 1.2rem;--}}
{{--            line-height: 100%;--}}
{{--            vertical-align: middle;--}}
{{--        }--}}

{{--        @media (max-width: 767px) {--}}
{{--            .chat-search-box {--}}
{{--                display: none;--}}
{{--            }--}}
{{--        }--}}


{{--        .users-container {--}}
{{--            position: relative;--}}
{{--            padding: 1rem 0;--}}
{{--            border-right: 1px solid #e6ecf3;--}}
{{--            height: 100%;--}}
{{--            display: -ms-flexbox;--}}
{{--            display: flex;--}}
{{--            -ms-flex-direction: column;--}}
{{--            flex-direction: column;--}}
{{--        }--}}


{{--        /************************************************--}}
{{--            ************************************************--}}
{{--                                                    Users--}}
{{--            ************************************************--}}
{{--        ************************************************/--}}

{{--        .users {--}}
{{--            padding: 0;--}}
{{--        }--}}

{{--        .users .person {--}}
{{--            position: relative;--}}
{{--            width: 100%;--}}
{{--            padding: 10px 1rem;--}}
{{--            cursor: pointer;--}}
{{--            border: 1px solid #f0f4f8;--}}
{{--            margin-bottom: 2px!important;--}}
{{--            margin-top: 2px!important;--}}
{{--        }--}}

{{--        .users .person:hover {--}}
{{--            border: 1px solid darkcyan;--}}
{{--        }--}}

{{--        .users .person.active-user {--}}
{{--            background-color: #ffffff;--}}
{{--            /* Fallback Color */--}}
{{--            background-image: -webkit-gradient(linear, left top, left bottom, from(#f7f9fb), to(#ffffff));--}}
{{--            /* Saf4+, Chrome */--}}
{{--            background-image: -webkit-linear-gradient(right, #f7f9fb, #ffffff);--}}
{{--            /* Chrome 10+, Saf5.1+, iOS 5+ */--}}
{{--            background-image: -moz-linear-gradient(right, #f7f9fb, #ffffff);--}}
{{--            /* FF3.6 */--}}
{{--            background-image: -ms-linear-gradient(right, #f7f9fb, #ffffff);--}}
{{--            /* IE10 */--}}
{{--            background-image: -o-linear-gradient(right, #f7f9fb, #ffffff);--}}
{{--            /* Opera 11.10+ */--}}
{{--            background-image: linear-gradient(right, #f7f9fb, #ffffff);--}}
{{--        }--}}

{{--        .users .person:last-child {--}}
{{--            border-bottom: 0;--}}
{{--        }--}}

{{--        .users .person .user {--}}
{{--            display: inline-block;--}}
{{--            position: relative;--}}
{{--            margin-right: 10px;--}}
{{--        }--}}

{{--        .users .person .user img {--}}
{{--            width: 48px;--}}
{{--            height: 48px;--}}
{{--            -webkit-border-radius: 50px;--}}
{{--            -moz-border-radius: 50px;--}}
{{--            border-radius: 50px;--}}
{{--        }--}}

{{--        .users .person .user .status {--}}
{{--            width: 10px;--}}
{{--            height: 10px;--}}
{{--            -webkit-border-radius: 100px;--}}
{{--            -moz-border-radius: 100px;--}}
{{--            border-radius: 100px;--}}
{{--            background: #e6ecf3;--}}
{{--            position: absolute;--}}
{{--            top: 0;--}}
{{--            right: 0;--}}
{{--        }--}}

{{--        .users .person .user .status.online {--}}
{{--            background: #9ec94a;--}}
{{--        }--}}

{{--        .users .person .user .status.offline {--}}
{{--            background: #c4d2e2;--}}
{{--        }--}}

{{--        .users .person .user .status.away {--}}
{{--            background: #f9be52;--}}
{{--        }--}}

{{--        .users .person .user .status.busy {--}}
{{--            background: #fd7274;--}}
{{--        }--}}

{{--        .users .person p.name-time {--}}
{{--            font-weight: 600;--}}
{{--            font-size: .85rem;--}}
{{--            display: inline-block;--}}
{{--        }--}}

{{--        .users .person p.name-time .time {--}}
{{--            font-weight: 400;--}}
{{--            font-size: .7rem;--}}
{{--            text-align: right;--}}
{{--            color: #8796af;--}}
{{--        }--}}

{{--        @media (max-width: 767px) {--}}
{{--            .users .person .user img {--}}
{{--                width: 30px;--}}
{{--                height: 30px;--}}
{{--            }--}}

{{--            .users .person p.name-time {--}}
{{--                display: none;--}}
{{--            }--}}

{{--            .users .person p.name-time .time {--}}
{{--                display: none;--}}
{{--            }--}}
{{--        }--}}


{{--        /************************************************--}}
{{--            ************************************************--}}
{{--                                            Chat right side--}}
{{--            ************************************************--}}
{{--        ************************************************/--}}

{{--        .selected-user {--}}
{{--            width: 100%;--}}
{{--            padding: 0 15px;--}}
{{--            min-height: 64px;--}}
{{--            line-height: 73px;--}}
{{--            border-bottom: 1px solid #e6ecf3;--}}
{{--            -webkit-border-radius: 0 3px 0 0;--}}
{{--            -moz-border-radius: 0 3px 0 0;--}}
{{--            border-radius: 0 3px 0 0;--}}
{{--        }--}}

{{--        .selected-user span {--}}
{{--            line-height: 100%;--}}
{{--        }--}}

{{--        .selected-user span.name {--}}
{{--            font-weight: 700;--}}
{{--        }--}}

{{--        .chat-container {--}}
{{--            position: relative;--}}
{{--            padding: 1rem;--}}
{{--        }--}}

{{--        .chat-container li.chat-left,--}}
{{--        .chat-container li.chat-right {--}}
{{--            display: flex;--}}
{{--            flex: 1;--}}
{{--            flex-direction: row;--}}
{{--            margin-bottom: 40px;--}}
{{--        }--}}

{{--        .chat-container li img {--}}
{{--            width: 48px;--}}
{{--            height: 48px;--}}
{{--            -webkit-border-radius: 30px;--}}
{{--            -moz-border-radius: 30px;--}}
{{--            border-radius: 30px;--}}
{{--        }--}}

{{--        .chat-container li .chat-avatar {--}}
{{--            margin-right: 20px;--}}
{{--        }--}}

{{--        .chat-container li.chat-right {--}}
{{--            justify-content: flex-end;--}}
{{--        }--}}

{{--        .chat-container li.chat-right > .chat-avatar {--}}
{{--            margin-left: 20px;--}}
{{--            margin-right: 0;--}}
{{--        }--}}

{{--        .chat-container li .chat-name {--}}
{{--            font-size: .75rem;--}}
{{--            color: #999999;--}}
{{--            text-align: center;--}}
{{--        }--}}

{{--        .chat-container li .chat-text {--}}
{{--            padding: .4rem 1rem;--}}
{{--            -webkit-border-radius: 4px;--}}
{{--            -moz-border-radius: 4px;--}}
{{--            border-radius: 4px;--}}
{{--            background: #ffffff;--}}
{{--            font-weight: 300;--}}
{{--            line-height: 150%;--}}
{{--            position: relative;--}}
{{--        }--}}

{{--        .chat-container li .chat-text:before {--}}
{{--            content: '';--}}
{{--            position: absolute;--}}
{{--            width: 0;--}}
{{--            height: 0;--}}
{{--            top: 10px;--}}
{{--            left: -20px;--}}
{{--            border: 10px solid;--}}
{{--            border-color: transparent #ffffff transparent transparent;--}}
{{--        }--}}

{{--        .chat-container li.chat-right > .chat-text {--}}
{{--            text-align: right;--}}
{{--        }--}}

{{--        .chat-container li.chat-right > .chat-text:before {--}}
{{--            right: -20px;--}}
{{--            border-color: transparent transparent transparent #ffffff;--}}
{{--            left: inherit;--}}
{{--        }--}}

{{--        .chat-container li .chat-hour {--}}
{{--            padding: 0;--}}
{{--            font-size: .75rem;--}}
{{--            display: flex;--}}
{{--            flex-direction: row;--}}
{{--            align-items: center;--}}
{{--            justify-content: center;--}}
{{--            margin: 0 0 0 15px;--}}
{{--        }--}}

{{--        .chat-container li .chat-hour > span {--}}
{{--            font-size: 16px;--}}
{{--            color: #9ec94a;--}}
{{--        }--}}

{{--        .chat-container li.chat-right > .chat-hour {--}}
{{--            margin: 0 15px 0 0;--}}
{{--        }--}}

{{--        @media (max-width: 767px) {--}}
{{--            .chat-container li.chat-left,--}}
{{--            .chat-container li.chat-right {--}}
{{--                flex-direction: column;--}}
{{--                margin-bottom: 30px;--}}
{{--            }--}}

{{--            .chat-container li img {--}}
{{--                width: 32px;--}}
{{--                height: 32px;--}}
{{--            }--}}

{{--            .chat-container li.chat-left .chat-avatar {--}}
{{--                margin: 0 0 5px 0;--}}
{{--                display: flex;--}}
{{--                align-items: center;--}}
{{--            }--}}

{{--            .chat-container li.chat-left .chat-hour {--}}
{{--                justify-content: flex-end;--}}
{{--            }--}}

{{--            .chat-container li.chat-left .chat-name {--}}
{{--                margin-left: 5px;--}}
{{--            }--}}

{{--            .chat-container li.chat-right .chat-avatar {--}}
{{--                order: -1;--}}
{{--                margin: 0 0 5px 0;--}}
{{--                align-items: center;--}}
{{--                display: flex;--}}
{{--                justify-content: right;--}}
{{--                flex-direction: row-reverse;--}}
{{--            }--}}

{{--            .chat-container li.chat-right .chat-hour {--}}
{{--                justify-content: flex-start;--}}
{{--                order: 2;--}}
{{--            }--}}

{{--            .chat-container li.chat-right .chat-name {--}}
{{--                margin-right: 5px;--}}
{{--            }--}}

{{--            .chat-container li .chat-text {--}}
{{--                font-size: .8rem;--}}
{{--            }--}}
{{--        }--}}

{{--        .chat-form {--}}
{{--            padding: 15px;--}}
{{--            width: 100%;--}}
{{--            left: 0;--}}
{{--            right: 0;--}}
{{--            bottom: 0;--}}
{{--            background-color: #ffffff;--}}
{{--            border-top: 1px solid white;--}}
{{--        }--}}

{{--        ul {--}}
{{--            list-style-type: none;--}}
{{--            margin: 0;--}}
{{--            padding: 0;--}}
{{--        }--}}

{{--        .card {--}}
{{--            border: 0;--}}
{{--            background: #f4f5fb;--}}
{{--            -webkit-border-radius: 2px;--}}
{{--            -moz-border-radius: 2px;--}}
{{--            border-radius: 2px;--}}
{{--            margin-bottom: 2rem;--}}
{{--            box-shadow: none;--}}
{{--        }--}}

{{--        .loadMoreButton:hover{--}}
{{--            background-color: lightgrey;--}}
{{--            color: #0a0e14;--}}
{{--        }--}}
{{--        .active2{--}}
{{--            background-color: darkcyan;--}}
{{--            color: white;--}}

{{--        }--}}
{{--        .active2:hover{--}}
{{--            background-color: darkcyan!important;--}}
{{--            color: white;--}}

{{--        }--}}
{{--    </style>--}}
@endpush
@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Linkedin Chat</h1>
                </div>

            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">

                    @if($account)
                    <div class="container-fluid">
{{--                        <linkedin-chat :account="{{json_encode($account)}}"></linkedin-chat>--}}
                        <chat-index   :account="{{json_encode($account)}}" ></chat-index>
                    </div>
                    @else
                     <h2 class="text-center"><span class="text-danger">Attention!</span> On your user not connected any linkedin account</h2>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection



@push('js')
    <script src="/scrollpagination.js"></script>

    <script src="/components/linkedin/chat.js"></script>
    <script src="/components/chat/index.js"></script>
    <script src="/components/chat/list.js"></script>
    <script src="/components/chat/messages.js"></script>
    <script src="/components/chat/message.js"></script>
    <script src="/plugins/moment/moment.min.js"></script>
    <script>
        $(document).ready(function (){

            $('body').on('mouseover','.no-send-message',function (){
                $(this).parent().parent().parent().css('border','1px solid black')
            })

            $('body').on('mouseleave','.no-send-message',function (){
                $(this).parent().parent().parent().css('border','1px solid #f7f7f7')
            })
        })
    </script>
@endpush
