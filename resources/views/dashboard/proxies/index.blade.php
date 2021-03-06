@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Proxies</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    <a class="btn btn-success btn-md float-right" data-toggle="modal" data-target="#createKeyModal">
                        <i class="fas fa-plus"></i>
                        Add
                    </a>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-striped projects">
                            <thead>
                            <tr>

                                <th >
                                    Login
                                </th>
                                <th>
                                    Password
                                </th>

                                <th>
                                    Ip
                                </th>
                                <th >
                                    Port
                                </th>
                                <th >
                                    Country
                                </th>
                                <th >
                                    Type
                                </th>
                                <th >
                                    Is worked
                                </th>
                                <th >

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($proxies as $proxy)
                                <tr>

                                    <td>
                                        {{$proxy->login}}
                                    </td>
                                    <td>
                                        {{$proxy->password}}
                                    </td>
                                    <td>
                                        {{$proxy->ip}}
                                    </td>
                                    <td>
                                        {{$proxy->port}}
                                    </td>
                                    <td>
                                        {{$proxy->country}}
                                    </td>
                                    <td>
                                        {{$proxy->type}}
                                    </td>
                                    <td id="life_{{$proxy->id}}">
                                     <img src="/Spinner.gif" width="50">
                                    </td>
                                    <td style="text-align: right">
                                        <a class="btn btn-primary btn-sm" href="{{route('proxies.edit',$proxy->id)}}"
                                           title="Edit">
                                            <i class="fas fa-user-edit"></i>
                                        </a>
                                        <form method="POST"
                                              action="{{ route('proxies.destroy',  $proxy->id) }}"
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
                    </div>
                    {!! $proxies->links('vendor.pagination') !!}

                </div>
            </div>

        </div>
    </section>
    <div class="modal" id="createKeyModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Create new proxy</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">??</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{route('proxies.store')}}">
                                @csrf
                                <div class="form-group">
                                    <label>Login *</label>
                                    <input name="login" required class="form-control" type="text" placeholder="Type login">
                                </div>
                                <div class="form-group">
                                    <label>Password *</label>
                                    <input name="password" required class="form-control" type="text" placeholder="Type password">
                                </div>
                                <div class="form-group">
                                    <label>Ip *</label>
                                    <input name="ip" required class="form-control" type="text" placeholder="Type ip">
                                </div>

                                <div class="form-group">
                                    <label>Port *</label>
                                    <input name="port" required class="form-control" type="text" placeholder="Type port">
                                </div>
                                <div class="form-group">
                                    <label>Country *</label>
                                    <input name="country" required class="form-control" type="text" placeholder="Type country">
                                </div>
                                <div class="form-group">
                                    <label>Type *</label>
                                    <input name="type" required class="form-control" type="text" placeholder="Type type">
                                </div>
                                <br/>
                                <div class="btn-group float-right">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-info">Submit</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function check(id) {
            $.ajax({
                url: "/dashboard/proxies/"+id+"/check",
                success: function (data) {
                        $('#life_' + id).html('<span class="badge badge-success">worked</span>');

                },
                error: function (e){
                    $('#life_' +id).html('<span class="badge badge-danger">error</span>');
                }
            })
        }
        @foreach($proxies as $p)
            check({{$p->id}})
        @endforeach
    </script>
@endpush
