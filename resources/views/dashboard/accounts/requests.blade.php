@extends('dashboard.layouts')

@push('js')
    <script src="/components/connection/info.js"></script>

    <script>


        $(document).on("click", ".getInfo", function () {
            $(document).trigger('getConnectionInfo', $(this).attr('data-connectionId'));
        });
    </script>

@endpush

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><span class="text-info">{{$account->full_name}}</span> requests</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="card-header p-2">
                        <a class="btn btn-primary btn-md float-right" id="um" href="{{route('accounts.index')}}">
                            <i class="fas fa-arrow-alt-circle-left"></i>
                            Back
                        </a>
                    </div>
                    <div class="container-fluid">
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
                                        Date
                                    </th>
                                    <th>
                                        Accounts
                                    </th>
                                    <th>
                                        Keys
                                    </th>
                                    <th>
                                        Message
                                    </th>
                                    <th class="float-right">
                                        Actions
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($account->requests as $requests)
                                    <tr>
                                        <td>
                                            {{$requests->connection->id}}
                                        </td>
                                        <td>
                                            <img class="table-avatar" src="{{$requests->connection->image}}"
                                                 onerror="this.src='/dist/img/lin_def_image.svg'" width="50">
                                        </td>
                                        <td>
                                            {{$requests->connection->fullName}}
                                        </td>

                                        <td>
                                            {{$requests->connection->occupation}}
                                        </td>
                                        <td>
                                            {{$requests->date->diffForHumans()}}
                                        </td>
                                        <td>
                                            @foreach($requests->connection->accounts as $ac)
                                                <span  class="badge badge-secondary">  {{$ac->full_name}}</span>
                                            @endforeach
                                        </td>


                                        <td>
                                            @foreach($requests->connection->keys as $key)
                                                <span class="badge badge-secondary">#{{$key->name}}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{$requests->message}}
                                        </td>
                                        <td class="float-right">
                                            <div class="dropdown dropleft">
                                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-universal-access"></i>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class=" dropdown-item" target="_blank"
                                                       href="https://www.linkedin.com/in/{{$requests->connection->entityUrn}}">
                                                        <span class="text-bold text-black-50">Got to Linkedin</span>
                                                    </a>
                                                    <a class="getInfo dropdown-item" data-connectionId="{{$requests->connection->id}}"
                                                       href="javascript:void(0)">
                                                        <span class="text-bold text-black-50">View info</span>
                                                    </a>
                                                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('Admin'))
                                                        <a class="dropdown-item"
                                                           href="{{route('connections.getSkillsAndPositions',$requests->connection->id)}}">
                                                            <span class="text-bold text-black-50">Get skills/positions</span>
                                                        </a>
                                                    @endif
                                                    <a class="dropdown-item"
                                                       href="{{route('connections.edit',$requests->connection->id)}}">
                                                        <span class="text-bold text-black-50">Edit</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                @endforeach

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <connection-info></connection-info>
    </section>
@endsection



