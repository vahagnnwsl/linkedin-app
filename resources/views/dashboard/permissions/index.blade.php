@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Permissions</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">

                <div class="card-body p-2">
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-striped projects">
                            <thead>
                            <tr>
                                <th style="width: 1%">
                                    #
                                </th>
                                <th style="width: 20%">
                                     Name
                                </th>
                                <th style="width: 30%">
                                    Guard Name
                                </th>
                                <th>
                                   Created At
                                </th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissions as $permission)
                            <tr>
                                <td>
                                    #
                                </td>
                                <td>
                                    {{$permission->name}}
                                </td>
                                <td>
                                    {{$permission->guard_name}}
                                </td>
                                <td>
                                    {{$permission->created_at->format('Y-m-d')}}
                                </td>


                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
