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
                        <a class="btn btn-success btn-md float-right" href="{{route('accounts.create')}}">
                            <i class="fas fa-plus"></i>
                            Add
                        </a>
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
                                    <div class="btn-group btn-group-md float-right">

{{--                                        @if($account->type === 1)--}}
                                            <a class="btn btn-default btn-sm"
                                               href="{{route('accounts.syncConversations',$account->id)}}"
                                               title="Sync Conversations">
                                                <i class="fas fa-sync"></i>
                                            </a>

                                            <a class="btn btn-info btn-sm"
                                               href="{{route('accounts.syncConnections',$account->id)}}"
                                               title="Sync Connections">
                                                <i class="fas fa-sync"></i>
                                            </a>

                                            <a class="btn btn-warning btn-sm"
                                               href="{{route('accounts.syncRequests',$account->id)}}"
                                               title="Sync send request">
                                                <i class="fas fa-sync"></i>
                                            </a>

{{--                                        @endif--}}
                                        <a class="btn btn-dark btn-sm"
                                           href="{{route('accounts.conversations',$account->id)}}"
                                           title="Conversations List">
                                            <i class="fas fa-envelope"></i>
                                        </a>


                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('Admin'))
                                            <a class="btn btn-primary btn-sm"
                                               href="{{route('accounts.edit',$account->id)}}"
                                               title="Edit">
                                                <i class="fas fa-user-edit"></i>
                                            </a>
                                        @endif

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


