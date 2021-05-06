@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Conversations </h1>
                </div>

            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">

                <div class="card-body p-0" style="display: block;">
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th style="width: 1%">#
                            </th>
                            <th style="width: 15%">
                                EntityUrn
                            </th>
                            <th style="width: 20%">
                                LastActivityAt
                            </th>
                            <th style="width: 20%">
                                Connection
                            </th>
                            <th style="width: 20%">

                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($conversations as $conversation)
                            <tr>
                                <td>#</td>

                                <td>
                                    {{$conversation->entityUrn}}
                                </td>

                                <td>
                                    {{$conversation->lastActivityAt}}
                                </td>

                                <td>
                                    <img class="table-avatar" src="{{$conversation->connection->image}}"
                                         onerror="this.src='/dist/img/lin_def_image.svg'">
                                    {{$conversation->connection->full_name}}
                                </td>

                                <td class="text-right">
                                    <a href="{{route('accounts.conversationMessages',[$id,$conversation->id])}}" class="btn btn-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>

                </div>
                {!! $conversations->links('vendor.pagination') !!}


            </div>

        </div>

    </section>
@endsection


