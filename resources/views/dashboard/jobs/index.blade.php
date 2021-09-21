@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">

                    <h1>Jobs <span style="float: right" class="text-blue">Total: {{$jobs->total()}}</span>
                    </h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="btn-group">
                        <a href="?type=process" class="btn btn-primary">In process</a>
                        <a href="?type=failed" class="btn btn-danger">Failed</a>
                    </div>

                </div>
                <div class="card-body p-2">
                    <ul class="list-group list-group-flush">
                        @foreach($jobs as $job)
                            @if($type ==='process')
                                <li class="list-group-item">
                                    <strong class="text-info">ID:</strong> {{$job->id}}<br/>
                                    @foreach($job->display as $key=>$value)
                                        <strong class="text-info">{{$key}}:</strong>  <em> {{$value}} </em> <br/>
                                    @endforeach
                                </li>
                            @else
                                <li class="list-group-item">
                                    <strong class="text-info">ID:</strong> {{$job->id}}<br/>

                                </li>
                            @endif

                        @endforeach
                    </ul>
                    {!! $jobs->appends($_GET)->links('vendor.pagination') !!}

                </div>
            </div>

        </div>
    </section>

@endsection
