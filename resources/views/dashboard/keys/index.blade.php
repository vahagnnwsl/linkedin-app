@extends('dashboard.layouts')
@push('css')
    <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">

@endpush
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
                                <th style="width: 1%">
                                    #
                                </th>
                                <th style="width: 20%">
                                    Name
                                </th>
                                <th style="width: 20%">
                                    Account
                                </th>
                                <th style="width: 20%">
                                    Country
                                </th>

                                <th style="width: 20%">
                                    Status
                                </th>
                                <th>

                                </th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($keys as $key)
                                <tr>
                                    <td>
                                        {{$key->id}}
                                    </td>
                                    <td>
                                        {{$key->name}}
                                    </td>
                                    <td>
                                        @foreach($key->accounts as $account)
                                            {{$account->full_name}} <br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a> {{$key->country->name}}</a>
                                    </td>

                                    <td>
                                        @if($key->status)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>

                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{route('keys.edit',$key->id)}}"
                                           title="Edit">
                                            <i class="fas fa-user-edit"></i>
                                        </a>
                                        <a class="btn btn-warning btn-sm"
                                           onclick="return confirm(&quot;Run job?&quot;)"
                                           href="{{route('keys.search',$key->id)}}"
                                           title="Run Job">
                                            <i class="fas fa-running"></i>
                                        </a>
                                        <a class="btn btn-danger btn-sm"
                                           onclick="return confirm(&quot;Run job?&quot;)"
                                           href="{{route('keys.searchByCompanies',$key->id)}}"
                                           title="Run Job with companies">
                                            <i class="fas fa-running"></i>
                                            <i class="fas fa-award"></i>
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
                                <div class="form-group">
                                    <label>Name *</label>
                                    <input name="name" required class="form-control" type="text" placeholder="Type key">
                                </div>

                                <div class="form-group">
                                    <label>Account *</label>
                                    <select multiple="multiple" class="select2 form-control" required
                                            data-placeholder="Select something" id="accounts_id" name="accounts_id[]">
                                        @foreach($accounts as $key)
                                            <option value="{{$key['id']}}">{{$key['text']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Country *</label>
                                    <select class="form-control" name="country_id" required>
                                        <option selected disabled value="">Select one</option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}">
                                                {{$country->name}}
                                            </option>
                                        @endforeach
                                    </select>
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
    <script src="/plugins/select2/js/select2.full.min.js"></script>
    <script src="/plugins/moment/moment.min.js"></script>



    <script>

        $(function () {

            $('.select2').select2({
                multiple: true,
                width: '100%'
            })

        });
    </script>
@endpush
