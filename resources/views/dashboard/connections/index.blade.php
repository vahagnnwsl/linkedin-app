@extends('dashboard.layouts')

@push('css')
    {{--    <link rel="stylesheet" href="/plugins/json-browse/jquery.json-browse.js"></li>--}}
@endpush


@push('js')
    {{--    <script src="/plugins/json-browse/jquery.json-browse.js"></script>--}}
    <script src="/components/connection/request.js"></script>
    <script src="/components/connection/message.js"></script>
    <script src="/components/connection/info.js"></script>
    <script src="/components/connection/fullInfo.js"></script>
    <script src="/components/connection/relative-conversation.js"></script>

    <script>
        $(document).on("click", ".setConnectionRequest", function () {
            $(document).trigger('sendConnectionRequest', $(this).attr('data-connectionId'));
        });

        $(document).on("click", ".sendMessage", function () {
            $(document).trigger('sendMessage', $(this).attr('data-connectionId'));
        });

        $(document).on("click", ".getConversationMessages", function () {
            $(document).trigger('getConversationMessages', $(this).attr('data-conversationId'));
        });

        $(document).on("click", ".getInfo", function () {
            $(document).trigger('getConnectionInfo', $(this).attr('data-connectionId'));
        });
        $(document).on("click", ".fullInfo", function () {
            $(document).trigger('getConnectionFullInfo', $(this).attr('data-connectionId'));
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

                <div class="card-body text-right">
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('Admin'))
                        <div class="btn-group">

                            <a href="{{route('connections.exportCvs',$hash ? [ 'hash'=>$hash ]: $req )}}"
                               class="btn btn-outline-info"
                               onclick="return confirm(&quot;Export cvs?&quot;)"
                            >
                                Export CVS
                            </a>
                            <a href="{{route('connections.carrierInterest')}}"
                               class="btn btn-outline-info"
                               onclick="return confirm(&quot;Run job?&quot;)"
                            >
                                Get Each Carrier interest
                            </a>
                            <a href="{{route('connections.getSkills')}}"
                               class="btn btn-outline-info"
                               onclick="return confirm(&quot;Run job?&quot;)"
                            >
                                Get Each Skills
                            </a>
                            <a href="{{route('connections.getPositions')}}" class="btn btn-outline-info"
                               onclick="return confirm(&quot;Run job?&quot;)"
                            >
                                Get Each Positions
                            </a>
                            <a href="{{route('connections.calcExperience')}}" class="btn btn-outline-info"
                               onclick="return confirm(&quot;Run job?&quot;)"
                            >
                                Calculate experience
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
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
                                    Open to work
                                </th>
                                <th>
                                    Requests
                                </th>
                                <th>

                                </th>
                                <th class="float-right">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
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
                                        @foreach($connection->accounts as $ac)
                                            <span class="badge badge-primary">  {{$ac->full_name}}</span>
                                        @endforeach
                                    </td>

                                    <td>
                                        @foreach($connection->keys as $key)
                                            <span class="badge badge-success">#{{$key->name}}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($connection->career_interest)
                                            <span class="badge badge-success">Open</span>
                                        @else
                                            <span class="badge badge-danger">Close</span>

                                        @endif
                                    </td>
                                    <td>
                                        @foreach($connection->requests as $requests)
                                            @if($requests->account)
                                               <span class="badge badge-warning">{{$requests->account->full_name}}</span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($connection->conversations as $conversation)
                                            @if($userAccount)
                                                @if($userAccount && $conversation->account_id ===$userAccount->id)
                                                    <a title="Go to chat" target="_blank"
                                                       href="{{route('linkedin.chat')}}#entityUrn:{{$conversation->entityUrn}}">
                                                        <i class="fa fa-envelope"></i>
                                                    </a>
                                                @else
                                                    <a>
                                                <span style="cursor: pointer"
                                                      class="fa fa-envelope getConversationMessages"
                                                      title=""
                                                      data-conversationId="{{$conversation->entityUrn}}"></span>
                                                    </a>
                                                    <br/>
                                                @endif
                                            @endif
                                        @endforeach

                                    </td>

                                    <td class="float-right">
                                        <div class="dropdown dropleft">
                                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-universal-access"></i>
                                            </a>
                                            <div class="dropdown-menu">


                                                <a class=" dropdown-item" target="_blank"
                                                   href="https://www.linkedin.com/in/{{$connection->entityUrn}}">
                                                    <span class="text-bold text-black-50">Got to Linkedin</span>
                                                </a>
                                                <a class="getInfo dropdown-item" data-connectionId="{{$connection->id}}"
                                                   href="javascript:void(0)">
                                                    <span class="text-bold text-black-50">View info</span>
                                                </a>
                                                @if(\Illuminate\Support\Facades\Auth::user()->hasRole('Admin'))
                                                    <a class="dropdown-item"
                                                       href="{{route('connections.getSkillsAndPositions',$connection->id)}}">
                                                        <span
                                                            class="text-bold text-black-50">Get skills/positions</span>
                                                    </a>

                                                @endif
                                                <a class="dropdown-item"
                                                   href="{{route('connections.edit',$connection->id)}}">
                                                    <span class="text-bold text-black-50">Edit</span>
                                                </a>
                                                <a class="dropdown-item fullInfo"
                                                   data-connectionId="{{$connection->id}}"
                                                   href="javascript:void(0)">
                                                    <span class="text-bold text-black-50"> Get full info as JSON</span>
                                                </a>
                                                @if($userAccount && !count($connection->requests) && $connection->accounts()->count() === 0 && $userAccount->getSendRequestCount() < $userAccount->limit_connection_request )
                                                    <a class="dropdown-item setConnectionRequest"
                                                       data-connectionId="{{$connection->id}}"
                                                       href="javascript:void(0)">
                                                        <span class="text-bold text-black-50">Send request</span>
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

                </div>

                {!! $connections->appends($_GET)->links('vendor.pagination') !!}

                <send-connection-request></send-connection-request>
                <send-message></send-message>
                <relative-conversation></relative-conversation>
                <connection-info></connection-info>
                <connection-full-info></connection-full-info>
            </div>
        </div>
        <div class="modal" id="infoModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="info-modal-title">Modal Heading</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <h5 class="mb-2  ml-1">Skills</h5>
                            <div class="col-12" id="skills"></div>
                        </div>
                        <hr/>

                        <div class="row">
                            <h5 class="mb-2  ml-1">Positions</h5>
                            <div class="col-12" id="positions"></div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
