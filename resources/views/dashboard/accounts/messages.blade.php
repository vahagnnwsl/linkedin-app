@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Messages </h1>
                </div>

            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    <a class="btn btn-primary btn-md float-right"
                       href="{{route('accounts.conversations',$account->id)}}">
                        <i class="fas fa-arrow-alt-circle-left"></i>
                        Back
                    </a>
                </div>
                <div class="card-body p-2" style="display: block;">
                    <div class="row p-2">
                        @foreach($messages as $message)

                            <div class="col-md-12 border-bottom pt-2">
                                @if($message->connection)
                                    <img class="table-avatar mb-2" src="{{$message->connection->image}}"
                                         onerror="this.src='/dist/img/lin_def_image.svg'" width="30">
                                    <span class="text-bold">  {{$message->connection->full_name}} </span>

                                @else
                                    <img class="table-avatar mb-2" src="/dist/img/lin_def_image.svg" width="30">
                                    <span class="text-bold"> {{$account->full_name}} </span>
                                @endif
                                <span class="text-bold float-right">
                                        <i class="fa fa-clock"
                                           style="color: grey"></i>
                                        {{$message->date->diffForHumans()}}
                                    </span>

                                @if($message->text)
                                    <p class="text-bold p-2"
                                       style="background-color: lightblue;border-radius: 10px">{{$message->text}}</p>
                                @endif

                                @if($message->media)
                                    <p class="text-bold p-2" style="background-color: lightblue;border-radius: 10px">
                                        <img src="{{$message->media['url']}}" width="100">
                                    </p>
                                @endif

                                @if($message->attachments)
                                    <p class="text-bold p-2" style="background-color: lightblue;border-radius: 10px">
                                        @if(strpos($message->attachments['mediaType'], 'application') !== false)

                                            <a href="{{$message->attachments['reference']}}"> {{$message->attachments['name']}}</a>

                                        @else
                                            <img v-else src="{{$message->attachments['reference']}}" width="100">

                                        @endif
                                    </p>
                                @endif


                            </div>
                        @endforeach

                    </div>

                    {!! $messages->links('vendor.pagination') !!}

                </div>
            </div>

        </div>

    </section>
@endsection


