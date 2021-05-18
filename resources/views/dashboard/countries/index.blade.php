@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Countries</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    <a class="btn btn-success btn-md float-right"  data-toggle="modal" data-target="#createKeyModal">
                        <i class="fas fa-plus"></i>
                        Add
                    </a>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-striped projects">
                            <thead>
                            <tr>
                                <th style="width: 1%">
                                    #
                                </th>
                                <th style="width: 20%">
                                    Name
                                </th>

                                <th>
                                    EntityUrn
                                </th>
                                <th>

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($countries as $country)
                                <tr>
                                    <td>
                                        #
                                    </td>
                                    <td>
                                        {{$country->name}}
                                    </td>

                                    <td>
                                        {{$country->entityUrn}}
                                    </td>
                                    <td class="float-right">
                                        <form method="POST"
                                              action="{{ route('countries.destroy',  $country->id) }}"
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
                    {!! $countries->links('vendor.pagination') !!}

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
                            <h3 class="card-title">Create country</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{route('countries.store')}}">
                                @csrf
                                <input name="name"  data-vv-as="Name"
                                       class="form-control"
                                       type="text" placeholder="Type name">

                                <br/>
                                <input name="entityUrn"  data-vv-as="entityUrn"
                                       class="form-control"
                                       type="text" placeholder="Type entityUrn">
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
