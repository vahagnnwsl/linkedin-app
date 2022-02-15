@extends('dashboard.layouts')
@push('css')
    <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@endpush
@push('js')
    <script src="/plugins/select2/js/select2.full.min.js"></script>
    <script src="/plugins/moment/moment.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>

        $(function () {

            $('.select2').select2({
                multiple: true,
            });
            $('input[name="interval"]').daterangepicker({
                opens: 'left',
                timePicker: true,
                timePicker24Hour: true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss'
                }
            });
        });
    </script>
@endpush
@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Logs</h1>
                </div>

            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="card  card-info ">
                        <div class="card-header">
                            <h3 class="card-title">Filter</h3>

                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{url(request()->path())}}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="accounts">Accounts</label>
                                        <select multiple="multiple" class="select2 form-control"
                                                data-placeholder="Select something"
                                                id="accounts" name="accounts[]">
                                            @foreach($accounts as $ac)
                                                <option
                                                    @if(isset($req['accounts']) && count($req['accounts']) && in_array($ac->id,$req['accounts'])) selected
                                                    @endif value="{{$ac->id}}"
                                                >{{$ac->full_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="proxies">Proxies</label>
                                        <select multiple="multiple" class="select2 form-control"
                                                data-placeholder="Select something"
                                                id="proxies" name="proxies[]">
                                            @foreach($proxies as $proxy)
                                                <option
                                                    @if(isset($req['proxies']) && count($req['proxies']) && in_array($proxy->id,$req['proxies'])) selected
                                                    @endif value="{{$proxy->id}}"
                                                >{{$proxy->ip}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="status">Status</label>
                                        <input id="status" type="number" name="status" value="{{request('status')}}" min="100" max="520" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="interval">Interval</label>
                                        <input id="interval" type="text" name="interval"  class="form-control" @if($interval !== ' - ')value="{{  $interval }}"@endif>
                                    </div>
                                    <div class="col-md-12 mt-2">


                                        <div class="btn-group btn-group-sm float-right">
                                            <a href="{{url(request()->path())}}" class="btn btn-default float-right mr-1">Clear</a>
                                            <button type="submit" class="btn btn-info float-right"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive " style="font-size: 14px">
                            <table class="table table-striped ">
                                <thead>
                                <tr>
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        Method
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Req Url
                                    </th>
                                    <th>
                                        Message
                                    </th>
                                    <th>
                                        Req Body
                                    </th>

                                    <th>
                                        Account
                                    </th>
                                    <th>
                                        Proxy
                                    </th>
                                    <th>
                                        Date
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($logs as $log)
                                    <tr>
                                        <td>
                                            {{$log->id}}
                                        </td>
                                        <td>
                                            {{$log->method}}
                                        </td>
                                        <td>
                                            {{$log->status}}
                                        </td>
                                        <td>
                                            {{$log->request_url}}
                                        </td>
                                        <td>
                                            {{$log->msg}}
                                        </td>
                                        <td>
                                            {{$log->request_data}}
                                        </td>

                                        <td>
                                            {{$log->account->login}}
                                        </td>
                                        <td>
                                            {{$log->proxy->ip}}
                                        </td>
                                        <td>
                                            {{$log->created_at}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {!! $logs->appends($_GET)->links('vendor.pagination') !!}

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


