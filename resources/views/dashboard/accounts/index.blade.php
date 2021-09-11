@extends('dashboard.layouts')

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

{{--                            <a href="{{route('accounts.login',1)}}" class="btn btn-primary">Login all real</a>--}}
{{--                            <a href="{{route('accounts.login',2)}}" class="btn btn-info">Login all unreal</a>--}}
                            <a class="btn btn-success btn-md float-right" href="{{route('accounts.create')}}">
                                <i class="fas fa-plus"></i>
                                Add
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped ">
                        <thead>
                        <tr>
                            <th>
                                Full name
                            </th>
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
                                LastActivityAt
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($accounts as $account)
                            <tr>
                                <td>
                                    {{$account->full_name}}
                                </td>
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
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>

                                    @endif
                                </td>
                                <td>
                                    @if($account->type===1)
                                        <span class="badge badge-warning">Real</span>
                                    @else
                                        <span class="badge badge-danger">Unreal</span>

                                    @endif
                                </td>
                                <td>{{$account->lastActivityAt}}</td>

                                <td>
                                    <div class="dropdown dropleft">
                                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-universal-access"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                               href="{{route('accounts.checkLife',$account->id)}}"
                                               title="Sync Conversations">
                                                <span class="text-bold text-black-50">   Check life</span>
                                            </a>

                                            <a class="dropdown-item"
                                               href="{{route('accounts.syncConversations',$account->id)}}"
                                               title="Sync Conversations">
                                                <span class="text-bold text-black-50">   Sync Conversations</span>
                                            </a>

                                            <a class="dropdown-item"
                                               href="{{route('accounts.syncConnections',$account->id)}}"
                                               title="Sync Connections">
                                                <span class="text-bold text-black-50">     Sync Connections</span>
                                            </a>

{{--                                            <a class="dropdown-item"--}}
{{--                                               href="{{route('accounts.syncRequests',$account->id)}}"--}}
{{--                                               title="Sync send request">--}}
{{--                                                       <span class="text-bold text-black-50">--}}
{{--                                                           Sync send request--}}
{{--                                                       </span>--}}
{{--                                            </a>--}}

                                            <a class="dropdown-item"
                                               href="{{route('accounts.conversations',$account->id)}}"
                                               title="Conversations List">
                                                <span class="text-bold text-black-50">Conversations List</span>
                                            </a>


                                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('Admin'))
                                                <a class="dropdown-item"
                                                   href="{{route('accounts.edit',$account->id)}}"
                                                   title="Edit">
                                                    <span class="text-bold text-black-50">  Edit</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
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


