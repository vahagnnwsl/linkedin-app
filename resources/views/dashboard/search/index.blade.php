@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Searches</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <table class="table table-striped ">
                    <thead>
                    <tr>
                        <th> # </th>
                        <th> User </th>
                        <th> Name</th>
                        <th> Keys</th>
                        <th></th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($searches as $search)
                        <tr>
                            <td>
                                # {{$search->id}}
                            </td>
                            <td>
                                {{$search->user->email}}
                            </td>
                            <td>
                                {{$search->name}}
                            </td>
                            <td>
                                <button data-toggle="collapse" data-target="#demo{{$search->id}}">Show</button>

                                <div id="demo{{$search->id}}" class="collapse">
                                    @foreach($search->params as $key=> $val)
                                        {{$key}} -
                                        @if(is_array($val))
                                            @foreach($val as  $v)
                                                {{$v}} ,
                                            @endforeach
                                        @else
                                            {{$val}} /
                                        @endif

                                    @endforeach
                                </div>
                            </td>

                            <td>
                                <form method="POST"
                                      action="{{ route('searches.destroy',  $search->id) }}"
                                      accept-charset="UTF-8"
                                      style="display:inline">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            title="Delete Permission"
                                            onclick="return confirm(&quot;Confirm delete?&quot;)">
                                        <i class="fas fa-trash"> </i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @endforeach

                    </tbody>
                </table>
                {!! $searches->appends($_GET)->links('vendor.pagination') !!}

            </div>
        </div>


    </section>


@endsection


