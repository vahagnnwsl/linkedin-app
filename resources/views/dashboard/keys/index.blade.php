@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Search keys</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    @can('keys')
                        <a class="btn btn-success btn-md float-right"  data-toggle="modal" data-target="#createKeyModal">
                            <i class="fas fa-plus"></i>
                            Add
                        </a>
                    @endcan
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
                                    Created At
                                </th>
                                <th>

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($keys as $key)
                                <tr>
                                    <td>
                                        #
                                    </td>
                                    <td>
                                        {{$key->name}}
                                    </td>

                                    <td>
                                        {{$key->created_at->format('Y-m-d')}}
                                    </td>
                                    <td class="text-right">
                                     <a class="btn btn-primary " href="{{route('keys.search',$key->id)}}">
                                         <i class="fa fa-search"></i>
                                     </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $keys->links('vendor.pagination') !!}

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
                            <h3 class="card-title">Create search key</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{route('keys.store')}}">
                                @csrf
                                <input name="name"  data-vv-as="Name"
                                       class="form-control"
                                       type="text" placeholder="Type key">


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
