@extends('dashboard.layouts')
@push('css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        @media (min-width: 992px) {
            .dropdown-menu .dropdown-toggle:after {
                border-top: .3em solid transparent;
                border-right: 0;
                border-bottom: .3em solid transparent;
                border-left: .3em solid;
            }

            .dropdown-menu .dropdown-menu {
                margin-left: 0;
                margin-right: 0;
            }

            .dropdown-menu li {
                position: relative;
            }

            .nav-item .submenu {
                display: none;
                position: absolute;
                left: 100%;
                top: -7px;
            }

            .nav-item .submenu-left {
                right: 100%;
                left: auto;
            }

            .dropdown-menu > li:hover {
                background-color: #f1f1f1
            }

            .dropdown-menu > li:hover > .submenu {
                display: block;
            }
        }
    </style>
@endpush
@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Linkedin accounts</h1>
                </div>

            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('Admin'))


                        <div class="btn-group float-right">
                            <a class="btn btn-success btn-md float-right" href="{{route('accounts.create')}}">
                                <i class="fas fa-plus"></i>
                                Add
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body p-0 table-responsive" style="min-height: 1000px">
                    <table class="table table-striped ">
                        <thead>
                        <tr>
                            <th>
                                Login
                            </th>

                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('Admin'))
                                <th>
                                    Users
                                </th>
                            @endif

                            <th>
                                Status
                            </th>
                            <th>
                                Type
                            </th>
                            <th>
                                Cookie life
                            </th>
                            <th>
                                Is online
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($accounts as $account)
                            <tr>

                                <td>
                                    {{$account->login}}
                                </td>

                                @if(\Illuminate\Support\Facades\Auth::user()->hasRole('Admin'))
                                    <td>
                                        @foreach($account->users as $user)
                                            <a href="{{route('users.edit',$user->id)}}">{{$user->full_name}}</a>
                                        @endforeach
                                    </td>
                                @endif

                                <td>
                                    @if($account->status)
                                        <span class="badge badge-success"><em
                                                style="letter-spacing: 2px">ACTIVE</em></span>
                                    @else
                                        <span class="badge badge-danger"><em
                                                style="letter-spacing: 2px">INACTIVE</em></span>

                                    @endif
                                </td>
                                <td>
                                    @if($account->type===1)
                                        <span class="badge badge-primary"><em
                                                style="letter-spacing: 2px">REAL</em></span>
                                    @else
                                        <span class="badge badge-info"><em
                                                style="letter-spacing: 2px">UNREAL</em></span>

                                    @endif
                                </td>
                                <td id="life_{{$account->id}}">
                                    <img src="/Spinner.gif" width="50">
                                </td>
                                <td>
                                    <p id="online_{{$account->id}}"></p>
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input customSwitch"
                                                   data_account_id="{{$account->id}}" id="customSwitch{{$account->id}}">
                                            <label class="custom-control-label"
                                                   for="customSwitch{{$account->id}}"></label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <nav class="navbar navbar-expand-lg ">


                                        <div class="collapse navbar-collapse">

                                            <ul class="navbar-nav ml-auto">
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link  dropdown-toggle" href="#" data-toggle="dropdown"
                                                       aria-expanded="false">
                                                        <i class="fa fa-universal-access"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item"
                                                           href="{{route('accounts.checkLife',$account->id)}}"
                                                           title="Sync Conversations">
                                                            <span class="text-bold text-black-50">   Check life</span>
                                                        </a>

                                                        <a class="dropdown-item"
                                                           href="{{route('accounts.syncConversations',$account->id)}}"
                                                           title="Sync Conversations">
                                                            <span
                                                                class="text-bold text-black-50">   Sync Conversations</span>
                                                        </a>

                                                        <a class="dropdown-item"
                                                           href="{{route('accounts.syncConnections',$account->id)}}"
                                                           title="Sync Connections">
                                                            <span
                                                                class="text-bold text-black-50">     Sync Connections</span>
                                                        </a>

                                                        <a class="dropdown-item"
                                                           href="{{route('accounts.conversations',$account->id)}}"
                                                           title="Conversations List">
                                                            <span
                                                                class="text-bold text-black-50">Conversations List</span>
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{route('accounts.requests',$account->id)}}"
                                                           title="Request List">
                                                            <span class="text-bold text-black-50">Request List</span>
                                                        </a>

                                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('Admin'))
                                                            <a class="dropdown-item"
                                                               href="{{route('accounts.edit',$account->id)}}"
                                                               title="Edit">
                                                                <span class="text-bold text-black-50">  Edit</span>
                                                            </a>
                                                        @endif
                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <span class="text-bold text-black-50">
                                                                    Sync conversations messages
                                                                </span>
                                                            </a>
                                                            <ul class="submenu submenu-left dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                       href="{{route('accounts.syncConversationsMessages',$account->id)}}"
                                                                       title="Sync conversations messages">
                                                                        <span class="text-bold text-black-50">All messages</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                       href="{{route('accounts.syncConversationsLastMessages',$account->id)}}"
                                                                       title="Sync conversations messages">
                                                                        <span class="text-bold text-black-50">Last messages</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                       href="{{route('accounts.syncConversationsMessages',['limit'=>20,'id'=>$account->id])}}"
                                                                       title="Sync conversations messages">
                                                                        <span class="text-bold text-black-50">Last 20 connections </span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                       href="{{route('accounts.syncConversationsMessages',['limit'=>50,'id'=>$account->id])}}"
                                                                       title="Sync conversations messages">
                                                                        <span class="text-bold text-black-50">Last 50 connections </span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                       href="{{route('accounts.syncConversationsMessages',['limit'=>100,'id'=>$account->id])}}"
                                                                       title="Sync conversations messages">
                                                                        <span class="text-bold text-black-50">Last 100 connections </span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </nav>

                                </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>

                </div>
                {!! $accounts->links('vendor.pagination') !!}


            </div>

        </div>

    </section>
@endsection
@push('js')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"
            type="text/javascript"></script>
    <script type="text/javascript">
        /// some script

        // jquery ready start
        $(document).ready(function () {
            // jQuery code

            //////////////////////// Prevent closing from click inside dropdown
            $(document).on('click', '.dropdown-menu', function (e) {
                e.stopPropagation();
            });

            // make it as accordion for smaller screens
            if ($(window).width() < 992) {
                $('.dropdown-menu a').click(function (e) {
                    e.preventDefault();
                    if ($(this).next('.submenu').length) {
                        $(this).next('.submenu').toggle();
                    }
                    $('.dropdown').on('hide.bs.dropdown', function () {
                        $(this).find('.submenu').hide();
                    })
                });
            }

        }); // jquery end
    </script>
    <script>
        $(document).ready(function () {

            $('.customSwitch').change(function () {
                var id = $(this).attr('data_account_id');
                if ($(this).is(':checked')) {
                    // Do something...
                    setOnline(id, 1)
                } else {
                    setOnline(id, 0)
                }
            })

            function setOnline(accountId, status) {
                $.ajax({
                    url: "/dashboard/accounts/" + accountId + "/setOnlineParameter?status=" + status,
                    success: function (data) {
                        if (data.success) {
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                        }
                    }, error: function () {
                        toastr.error('something  went wrong');

                    }
                })
            }

            function check() {
                $.ajax({
                    url: "/dashboard/accounts/checkAllLife",
                    success: function (data) {
                        for (let i in data) {
                            if (data[i].success) {
                                $('#life_' + data[i].id).html('<span class="badge badge-success">' + data[i].life + '</span>');
                            } else {
                                $('#life_' + data[i].id).html('<span class="badge badge-danger">' + data[i].life + '</span>');
                            }
                        }
                    }
                })
            }

            function online() {
                $.ajax({
                    url: "/dashboard/accounts/checkOnline",
                    success: function (data) {
                        for (let i in data) {
                            if (data[i].success) {
                                $('#customSwitch' + data[i].id).attr('checked', 'checked');
                                $('#online_' + data[i].id).html('<span class="badge badge-success">' + data[i].online + '</span> </br><small>' + data[i].lastActivityAt + '</small>');
                            } else {
                                $('#customSwitch' + data[i].id).removeAttr('checked');

                                $('#online_' + data[i].id).html('<span class="badge badge-danger">' + data[i].online + '</span></br> <small>' + data[i].lastActivityAt + '</small>');
                            }
                        }
                    }
                })
            }

            setInterval(function () {
                check();
            }, 30000)
            setInterval(function () {
                online();
            }, 10000)
            check();
            online();

        })
    </script>
@endpush

