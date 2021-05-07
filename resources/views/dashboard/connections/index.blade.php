@extends('dashboard.layouts')
@push('js')
    <script src="/components/connection/request.js"></script>

    <script>
        $(document).on("click", ".setConnectionRequest", function () {
            $(document).trigger('sendConnectionRequest',$(this).attr('data-connectionId'));
        });
    </script>

@endpush
@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Connections  <span style="float: right" class="text-blue">Total: {{$connections->total()}}</span></h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                @include('dashboard.connections.filter')
                <div class="card-body p-0" >
                    <table class="table table-striped ">
                        <thead>
                        <tr>
                            <th style="width: 1%">
                                #
                            </th>
                            <th style="width: 5%">
                                Avatar
                            </th>
                            <th style="width: 20%">
                                Full name
                            </th>

                            <th style="width: 20%">
                                Public identifier
                            </th>
                            <th style="width: 25%">
                                Occupation
                            </th>

                            @can('accounts')
                            <th style="width: 15%">
                                Accounts
                            </th>
                            @endcan
                            <th style="width: 15%;" class="text-center">
                                Keys
                            </th>
                            <th style="width: 15%">
                                 Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($connections as $connection)
                            <tr>
                                <td>
                                    #
                                </td>
                                <td>
                                    <img class="table-avatar" src="{{$connection->image}}"
                                         onerror="this.src='/dist/img/lin_def_image.svg'" width="50">
                                </td>
                                <td>
                                    {{$connection->fullName}}
                                </td>
                                <td>
                                    {{$connection->publicIdentifier}}
                                </td>
                                <td>
                                    {{$connection->occupation}}
                                </td>
                                @can('accounts')
                                <td>
                                    @foreach($connection->accounts as $account)
                                        <a href="{{route('accounts.edit',$account->id)}}">{{$account->full_name}}</a>
                                    @endforeach
                                </td>
                                @endcan
                                <th style="width: 20%">
                                <td class="text-center">
                                    @foreach($connection->keys as $key)
                                        <span class="badge badge-secondary">#{{$key->name}}</span>
                                    @endforeach
                                </td>
                                <td>
                                   <div class="btn-group">
                                       <a class="btn btn-info" title="Get info" href="{{route('connections.getInfo',$connection->id)}}">
                                           <i class="fa fa-info-circle"></i>
                                       </a>

                                       <a class="btn btn-primary setConnectionRequest" title="Sent Connection Request" href="javascript:void(0)" data-connectionId="{{$connection->id}}">
                                           <i class="fa fa-plus-circle"></i>
                                       </a>

                                   </div>
                                </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>

                </div>

                {!! $connections->appends($_GET)->links('vendor.pagination') !!}

                <send-connection-request></send-connection-request>

            </div>
        </div>

    </section>
@endsection



