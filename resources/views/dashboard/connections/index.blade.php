@extends('dashboard.layouts')
@push('js')
    <script src="/components/connection/request.js"></script>
    <script src="/components/connection/message.js"></script>

    <script>
        $(document).on("click", ".setConnectionRequest", function () {
            $(document).trigger('sendConnectionRequest', $(this).attr('data-connectionId'));
        });

        $(document).on("click", ".sendMessage", function () {
            $(document).trigger('sendMessage', $(this).attr('data-connectionId'));
        });
    </script>

@endpush

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Connections <span style="float: right" class="text-blue">Total: {{$connections->total()}}</span>
                    </h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                @include('dashboard.connections.filter')
                <div class="card-body p-0">
                    <table class="table table-striped ">
                        <thead>
                        <tr>
                            <th>
                                ID
                            </th>
                            <th>
                                Avatar
                            </th>
                            <th>
                                Full name
                            </th>
                            <th>
                                Occupation
                            </th>

                            <th>
                                Accounts
                            </th>
                            <th>
                                Keys
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        {{dd($connections)}}
                        @foreach($connections as $connection)
                            <tr>
                                <td>
                                    {{$connection->id}}
                                </td>
                                <td>
                                    <img class="table-avatar" src="{{$connection->image}}"
                                         onerror="this.src='/dist/img/lin_def_image.svg'" width="50">
                                </td>
                                <td>
                                    {{$connection->fullName}}
                                </td>

                                <td>
                                    {{$connection->occupation}}
                                </td>

                                    <td>
                                        @foreach($connection->accounts as $account)
                                            {{$account->full_name}} <br>
                                        @endforeach
                                    </td>


                                <td>
                                    @foreach($connection->keys as $key)
                                        <span class="badge badge-secondary">#{{$key->name}}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="btn-group">

                                        @if($userAccount)
                                            @if($connection->canSendConnectionRequest() && !$connection->requestByAccount($userAccount->id)->first())
                                                <a class="btn btn-primary setConnectionRequest"
                                                   title="Sent Connection Request" href="javascript:void(0)"
                                                   data-connectionId="{{$connection->id}}">
                                                    <i class="fa fa-plus-circle"></i>
                                                </a>
                                            @endif

                                            @if($connection->requestByAccount($userAccount->id)->first())
                                                <span class="badge badge-warning">Pending</span>
                                            @endif

                                            @if($connection->canWrite($userAccount->id))
                                                <a class="btn btn-info sendMessage" href="javascript:void(0)"
                                                   data-connectionId="{{$connection->id}}">
                                                    <i class="fa fa-envelope"></i>
                                                </a>
                                            @endif
                                        @endif

                                    </div>
                                </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>

                </div>

                {!! $connections->appends($_GET)->links('vendor.pagination') !!}

                <send-connection-request></send-connection-request>
                <send-message></send-message>

            </div>
        </div>

    </section>
@endsection


