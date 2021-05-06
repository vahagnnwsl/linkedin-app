@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Users</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    @can('users')
                        <a class="btn btn-success btn-md float-right" href="{{route('users.create')}}">
                            <i class="fas fa-plus"></i>
                            Add
                        </a>
                    @endcan
                </div>
                <div class="card-body p-0" style="display: block;">
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th style="width: 1%">
                                #
                            </th>

                            <th style="width: 20%">
                                Full name
                            </th>
                            <th style="width: 15%">
                                Email
                            </th>
                            <th style="width: 20%">
                                Role
                            </th>
                            <th>
                                Join
                            </th>
                            <th style="width: 8%" class="text-center">
                                Status
                            </th>
                            <th style="width: 20%">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    #
                                </td>

                                <td>
                                    {{$user['full_name']}}
                                </td>
                                <td>
                                    {{$user['email']}}
                                </td>

                                <td>
                                    <p class="text-muted ">
                                        @if($user->role)
                                            <i class="{{$user->role->icon}}"
                                               title="{{$user->role->name}}"> {{$user->role->name}}</i>
                                        @endif
                                    </p>
                                </td>

                                <td>
                                    {{$user['created_at']->format('Y-m-d')}}
                                </td>
                                <td class="project-state">
                                    @if($user['status'])
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="project-actions text-right">


                                    @can('login_via_anther_user')
                                        @if($user->id !== \Illuminate\Support\Facades\Auth::id())
                                            <a class="btn btn-success btn-sm" href="{{route('users.login',$user->id)}}">
                                                <i class="fas fa-sign-in-alt"></i>
                                            </a>
                                        @endif
                                    @endcan

                                    @can('users')
                                        <a class="btn btn-primary btn-sm" href="{{route('users.edit',$user->id)}}"
                                           title="Edit">
                                            <i class="fas fa-user-edit"></i>
                                        </a>
                                    @endcan



                                    {{--                                    @can('users')--}}
                                    {{--                                        <form method="POST" action="{{ route('users.destroy',  $user->id) }}"--}}
                                    {{--                                              accept-charset="UTF-8"--}}
                                    {{--                                              style="display:inline">--}}
                                    {{--                                            {{ method_field('DELETE') }}--}}
                                    {{--                                            {{ csrf_field() }}--}}
                                    {{--                                            <button type="submit" class="btn btn-danger btn-sm"--}}
                                    {{--                                                    title="Delete Permission"--}}
                                    {{--                                                    onclick="return confirm(&quot;Confirm delete?&quot;)">--}}
                                    {{--                                                <i class="fas fa-trash"> </i>--}}
                                    {{--                                            </button>--}}
                                    {{--                                        </form>--}}
                                    {{--                                    @endcan--}}

                                </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>

                </div>
                {!! $users->links('vendor.pagination') !!}


            </div>

        </div>

    </section>
@endsection

