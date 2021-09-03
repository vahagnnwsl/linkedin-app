@extends('dashboard.layouts')
@push('js')
    <script src="/components/connection/request.js"></script>
    <script src="/components/connection/message.js"></script>
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
                                    <div class="btn-group">

                                        @if($userAccount)
                                            @if((!$connection->account_i || $connection->account_id !== $userAccount->id) && (!$connection->until_disabled || date('Y-m-d') > $connection->until_disabled->format('Y-m-d'))   &&  !$connection->canWrite($userAccount->id) && !$connection->requestByAccount($userAccount->id)->first())
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
                                                <a class="btn btn-success sendMessage" href="javascript:void(0)"
                                                   data-connectionId="{{$connection->id}}">
                                                    <i class="fa fa-envelope"></i>
                                                </a>
                                            @endif
                                        @endif
                                        <a class="btn btn-info ml-2"  target="_blank"
                                           href="https://www.linkedin.com/in/{{$connection->entityUrn}}">
                                            <i class="nav-icon fab fa-linkedin"></i>
                                        </a>
                                        <a class="btn btn-warning ml-2 getInfo" data-root="{{route('connections.getInfo',$connection->id)}}">
                                            <i class="fa fa-info"></i>
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
                <send-message></send-message>
                <linkedin-conversation></linkedin-conversation>

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


@push('js')
    <script>
        $(document).ready(function (){
            $('.getInfo').click(function () {
                var root = $(this).attr('data-root');
                $.ajax({
                    type: "GET",
                    url: root,
                    beforeSend:function (){
                        $('#positions').html('')
                        $('#skills').html('')
                        $('#educations').html('')

                        $(document).trigger('loader.update', true);
                    },
                    success: function (data){
                        $('#info-modal-title').text( data.firstName +' '+  data.lastName);
                        if(data.data){
                            if(data.data.skills){
                                let skills = '';
                                for(let i in data.data.skills){
                                    skills+='<span class="btn btn-outline-primary p-1 mr-2">'+data.data.skills[i]+'</span>'
                                }
                                $('#skills').html(skills)
                            }

                            if(data.data.positions){
                                let positions = '';
                                for(let i in data.data.positions){
                                    positions+='<div class="card p-2">' +
                                        '<h5 class="card-title  mb-2" style="color: saddlebrown">'+data.data.positions[i].title+'</h5>' +
                                        '<h6 class="card-subtitle mb-2 "  style="font-size: 12px;color: sandybrown">'+data.data.positions[i].companyName+'</h6>' +
                                        '<h6 class="card-subtitle mb-2 text-muted" style="font-size: 12px;">'+data.data.positions[i].timePeriod.start+' -  '+data.data.positions[i].timePeriod.end+'</h6>' +
                                        '</div>'
                                }
                                $('#positions').html(positions)
                            }


                        }
                        $('#infoModal').modal('show')

                    },error:function () {
                        toastr.error('Something went wrong');
                    },
                    complete: function(data) {
                        $(document).trigger('loader.update', false);
                    }
                });


                console.log(root)
            })
        })

    </script>
@endpush
