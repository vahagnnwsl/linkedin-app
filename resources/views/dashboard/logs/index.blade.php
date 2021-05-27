@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Error logs</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">

                <div class="card-body p-2">
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-striped projects">

                            <tbody>
                            @foreach($logs as $log)
                                <tr>


                                    <ul class="list-group mt-2">
                                        <li class="list-group-item">{{$log->status}}</li>
                                        <li class="list-group-item">{{$log->msg}}</li>
                                        <li class="list-group-item">{{$log->request_url}}</li>
                                        <li class="list-group-item json" data-json="{{$log->request_data}}">
                                        <li class="list-group-item">{{$log->created_at}}</li>


                                    </ul>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $logs->links('vendor.pagination') !!}

                </div>
            </div>

        </div>
    </section>

@endsection
@push('js')
    <script>
        $.each($('.json'),function (){

            $(this).html(  '<pre>'+JSON.stringify($(this).attr('data-json'), null, 2)+'</pre>')
        })

    </script>

@endpush
