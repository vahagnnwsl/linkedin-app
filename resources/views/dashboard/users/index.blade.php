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
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('Admin'))

                        <a class="btn btn-success btn-md float-right" href="{{route('users.create')}}">
                            <i class="fas fa-plus"></i>
                            Add
                        </a>

                    @endif
                </div>
                <div class="card-body p-0" style="display: block;">
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th>
                                ID
                            </th>

                            <th>
                                Full name
                            </th>
                            <th>
                                Email
                            </th>
                            <th>
                                Role
                            </th>

                            <th class="text-center">
                                Status
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    {{$user->id}}
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


                                <td class="project-state">
                                    @if($user['status'])
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="project-actions text-right">

                                    <div class="btn-group btn-group-md">

                                        <a class="btn btn-success btn-sm" href="{{route('users.login',$user->id)}}"
                                           title="Login as">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </a>

                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('Admin'))

                                            <a class="btn btn-info btn-sm"
                                               href="{{route('users.updatePasswordForm',$user->id)}}"
                                               title="Change password">
                                                <i class="fas fa-key"></i>
                                            </a>

                                            <a class="btn btn-primary btn-sm" href="{{route('users.edit',$user->id)}}"
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
                {!! $users->links('vendor.pagination') !!}


            </div>

        </div>

    </section>
@endsection

