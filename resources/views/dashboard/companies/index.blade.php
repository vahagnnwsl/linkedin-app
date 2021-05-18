@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>

                        <a class="btn btn-success btn-md float-right" href="{{route('companies.sync')}}">
                            <i class="fas fa-sync"></i>
                        </a>

                        Companies <span style="float: right"
                                        class="text-blue  mr-2">Total: {{$companies->total()}}</span>
                    </h1>

                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0">
                    <div class="card-body" style="display: block;" data-select2-id="31">
                        <form method="GET" action="{{url(request()->path())}}">
                            <div class="row">


                                <div class="col-md-12">
                                    <label for="keys_ids">Key</label>
                                    <input type="text" class="form-control" name="key" placeholder="Search key"
                                           value="{{request()->get('key')}}">

                                </div>


                                <div class="col-md-12 mt-2">
                                    <div class="btn-group btn-group-sm float-right">
                                        <a href="{{url(request()->path())}}" class="btn btn-default float-right mr-1">Clear</a>
                                        <button type="submit" class="btn btn-info float-right"><i
                                                class="fa fa-search"></i></button>

                                    </div>


                                </div>
                            </div>
                        </form>

                    </div>

                    <table class="table table-striped ">
                        <thead>
                        <tr>
                            <th style="width: 1%">
                                #
                            </th>
                            <th style="width: 5%">
                                Logo
                            </th>
                            <th style="width: 20%">
                                Name
                            </th>

                            <th style="width: 20%">
                                Entity Urn
                            </th>
                            <th style="width: 20%;text-align: center">
                                Keys
                            </th>
                            <th style="width: 20%">

                            </th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($companies as $company)
                            <tr>
                                <td>
                                    #
                                </td>
                                <td>
                                    <img class="table-avatar" src="{{$company->image}}"
                                         onerror="this.src='/dist/img/lin_def_image.svg'" width="50">
                                </td>
                                <td>
                                    {{$company->name}}
                                </td>
                                <td>
                                    {{$company->entityUrn}}
                                </td>
                                <td class="text-center">
                                    @foreach($company->keys as $key)
                                        <span class="badge badge-secondary">#{{$key->name}}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if($company->is_parsed === 1)
                                        <span class="badge badge-success">Success</span>

                                    @elseif($company->is_parsed === 2)
                                        <span class="badge badge-danger">Failed</span>
                                    @else
                                        <span class="badge badge-light">No parsed</span>
                                    @endif
                                </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>

                </div>

                {!! $companies->appends($_GET)->links('vendor.pagination') !!}


            </div>
        </div>

    </section>
@endsection



