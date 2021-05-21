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
                                    <td style="text-align: right">
                                        <a class="btn btn-primary btn-sm" href="{{route('proxies.edit',$proxy->id)}}"
                                           title="Edit">
                                            <i class="fas fa-user-edit"></i>
                                        </a>
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
                                <span aria-hidden="true">×</span>
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
