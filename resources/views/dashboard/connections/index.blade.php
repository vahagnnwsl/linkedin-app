@extends('dashboard.layouts')

@push('js')
    <script src="/components/connection/request.js"></script>
    <script src="/components/connection/message.js"></script>
    <script src="/components/connection/info.js"></script>
    <script src="/components/linkedin/conversation.js"></script>

    <script>
        $(document).on("click", ".setConnectionRequest", function () {
            $(document).trigger('sendConnectionRequest', $(this).attr('data-connectionId'));
        });

        $(document).on("click", ".sendMessage", function () {
            $(document).trigger('sendMessage', $(this).attr('data-connectionId'));
        });

        $(document).on("click", ".getConversationMessages", function () {
            $(document).trigger('getConversationMessages', $(this).attr('data-conversdationId'));
        });

        $(document).on("click", ".getInfo", function () {
            $(document).trigger('getConnectionInfo', $(this).attr('data-connectionId'));
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
                    <div class="btn-group">
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
                </div>
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

                                        @if($account->type === 2)
                                            <span class="text-info text-bold">
                                               {{$account->full_name}}

                                                @if(in_array($account->id,$relatedAccountsIdes) && $relConversation = (new \App\Repositories\ConversationRepository())->getConnectionConversationByConnectionAndAccount($connection->id,$account->id))
                                                    @if($relConversation)
                                                        <span class="badge badge-info getConversationMessages"
                                                              style="cursor: pointer"
                                                              data-conversdationId="{{$relConversation->id}}"
                                                              title="{{$relConversation->account->full_name}}">
                                                              <i class="fa fa-envelope"></i>
                                                        </span>
                                                    @endif
                                                @endif
                                           </span>

                                        @else
                                            <span class="text-blue text-bold">
                                                @if($userAccount->id === $account->id)
                                                    YOUR'S

                                                    @if($selfConversation = (new \App\Repositories\ConversationRepository())->getConnectionConversationByConnectionAndAccount($connection->id,$account->id))

                                                        <span class="badge badge-primary getConversationMessages"
                                                              data-conversdationId="{{$selfConversation->id}}"
                                                              title="{{$selfConversation->account->full_name}}">
                                                                  <i class="fa fa-envelope"></i>
                                                        </span>


                                                    @endif
                                                @else
                                                    {{$account->full_name}}
                                                @endif

                                            </span>
                                            @endif


                                            </br>
                                            @endforeach

                                </td>


                                <td>
                                    @foreach($connection->keys as $key)
                                        <span class="badge badge-secondary">#{{$key->name}}</span>
                                    @endforeach
                                </td>

                                <td>
                                    <div class="dropdown dropleft">
                                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-universal-access"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            @if($connection->requestByAccount($userAccount->id)->first())
                                                <h5 class="dropdown-header">Pending</h5>
                                            @endif

                                            @if($connection->canWrite($userAccount->id))
                                                <a class="sendMessage dropdown-item" href="javascript:void(0)"
                                                   data-connectionId="{{$connection->id}}">
                                                    <span class="text-bold text-black-50">Send message</span>
                                                </a>
                                            @endif
                                            <a class=" dropdown-item" target="_blank"
                                               href="https://www.linkedin.com/in/{{$connection->entityUrn}}">
                                                <span class="text-bold text-black-50">Got to Linkedin</span>
                                            </a>
                                            <a class="getInfo dropdown-item" data-connectionId="{{$connection->id}}"
                                               href="javascript:void(0)">
                                                <span class="text-bold text-black-50">View info</span>
                                            </a>
                                            <a class="dropdown-item"
                                               href="{{route('connections.getSkillsAndPositions',$connection->id)}}">
                                                <span class="text-bold text-black-50">Get skills/positions</span>
                                            </a>
                                            <a class="dropdown-item"
                                               href="{{route('connections.edit',$connection->id)}}">
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

                {!! $connections->appends($_GET)->links('vendor.pagination') !!}

                <send-connection-request></send-connection-request>
                <send-message></send-message>
                <linkedin-conversation></linkedin-conversation>
                <connection-info></connection-info>

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



