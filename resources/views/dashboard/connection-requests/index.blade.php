@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Connection Requests</h1>
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
                                    Connection
                                </th>
                                <th style="width: 20%">
                                    Account
                                </th>
                                <th style="width: 20%">
                                    User
                                </th>
                                <th>
                                    Message
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Created At
                                </th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td>
                                        #
                                    </td>
                                    <td>
                                        <span class="text-gray-dark text-bold">
                                            {{$request->connection->fullName}}
                                            <br/> ID: {{$request->connection->id}}</span>
                                    </td>
                                    <td>
                                        <span class="text-gray-dark text-bold"> {{$request->account->full_name}}</span>
                                    </td>
                                    <td>
                                        <span class="text-gray-dark text-bold">  {{$request->user?$request->user->fullName:''}}</span>
                                    </td>
                                    <td>
                                        <span class="text-gray-dark text-bold">   {{$request->message}}</span>
                                    </td>
                                    <td>
                                        <span class="text-gray-dark text-bold">  {{$request->status}}</span>
                                    </td>
                                    <td>
                                        <span class="text-gray-dark text-bold">  {{$request->created_at->format('Y-m-d')}}</span>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $requests->links('vendor.pagination') !!}

                </div>
            </div>

        </div>
    </section>
@endsection
