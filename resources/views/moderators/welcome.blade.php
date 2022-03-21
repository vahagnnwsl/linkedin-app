@extends('moderators.app')
@section('content')
    <div class="container">
        <div class="table-responsive">
            <table class="table border">
                <thead>
                <tr>
                    <th>hash</th>
                    <th>account</th>
                    <th>connection</th>
                    <th>status</th>
                    <th>actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($conversations as $conversation)
                    <tr>
                        <td>{{$conversation->entityUrn}}</td>
                        <td>{{$conversation->account_id}}</td>
                        <td>{{$conversation->connection_id}}</td>
                        <td>
                            @foreach($conversation->connection->statuses as $status)
                                <span class="badge badge-primary">
                                       @foreach($status->categories as $category)
                                        {{$category->name}}
                                    @endforeach
                               </span> <br>
                            @endforeach
                        </td>
                        <td>
                            <a class="btn btn-info"
                               href="{{ route('moderators.conversation',$conversation->entityUrn)}}">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
           <div class="col-12">
               <ul class="pagination pagination-md m-0 mb-2  flex-wrap" style="justify-content: center;">
                   @for($x = 0; $x<$pagesCount; $x+=1)
                       <li class="page-item  @if((int)request()->page === $x) active @endif "><a class="page-link"
                                                                                                 href="/moderators/welcome?page={{$x}}">{{$x+1}}</a>
                       </li>
                   @endfor
               </ul>
           </div>
        </div>
    </div>
@endsection
