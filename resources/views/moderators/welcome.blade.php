@extends('moderators.app')
@section('content')
    <div class="container">
        <div class="table-responsive">
            <table class="table border">
                <thead>
                <tr>
                    <th>id</th>
                    <th>hash</th>
                    <th>account</th>
                    <th>connection</th>
                    <th>status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($conversations as $conversation)
                    <tr>
                        <td>{{$conversation->id}}</td>
                        <td>{{$conversation->entityUrn}}</td>
                        <td>{{$conversation->account_id}}</td>
                        <td>{{$conversation->connection_id}}</td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <ul class="pagination">
                <?php $p = 1;?>
                @for($x = 0; $x<=$total; $x+=15)
                    <li class="page-item"><a class="page-link" href="/moderators/welcome?offset={{$x}}">{{$p}}</a></li>
                    <?php ++$p;?>
                @endfor
            </ul>
        </div>
    </div>
@endsection
