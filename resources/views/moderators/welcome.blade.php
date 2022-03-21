@extends('moderators.app')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{url(request()->path())}}">
                    <div class="row">
                        <div class="col-8">
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="status"
                                           @if((int)request()->status === 1) checked @endif
                                           value="1">With statuses
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="status"
                                           @if((int)request()->status === 0) checked @endif
                                           value="0">Without
                                    statuses
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input"
                                           @if((int)request()->status === 2 | !request()->has('status')) checked @endif
                                           name="status" value="2">All
                                </label>
                            </div>
                        </div>
                        <div class="col-4 text-right">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border">
                        <thead>
                        <tr>
                            <th>hash</th>
                            <th>connection</th>
                            <th>status</th>
                            <th>actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($conversations as $conversation)
                            <tr>
                                <td>{{$conversation->entityUrn}}</td>
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
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <ul class="pagination pagination-md m-0 mb-2  flex-wrap" style="justify-content: center;">
                    @for($x = 0; $x<$pagesCount; $x+=1)
                        <li class="page-item  @if((int)request()->page === $x) active @endif "><a class="page-link"
                                                                                                  href="/moderators/welcome?page={{$x}}&status={{request()->status??2}}">{{$x+1}}</a>
                        </li>
                    @endfor
                </ul>
            </div>
        </div>
    </div>
@endsection
