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
                        <div class="form-check-inline" style="padding: 0.75rem 0.5rem">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="checkAll" value="">
                            </label>
                        </div>
                        <button class="btn badge-danger btn-sm" id="deleteChecked">Delete</button>
                    </div>

                    <div class="btn-group float-right">
                        <a href="?type=process"
                           class="btn {{!request()->type || request()->type ==='process'? 'btn-info': 'btn-default'}}">In
                            process</a>
                        <a href="?type=failed" class="btn {{request()->type ==='failed'? 'btn-info': 'btn-default'}}">Failed</a>
                    </div>

                </div>
                <div class="card-body p-2">
                    <ul class="list-group list-group-flush">
                        @foreach($jobs as $job)
                            @if($type ==='process')
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-1">
                                            <div class="form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input ids" name="jobs[]"
                                                           value="{{$job->id}}">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <strong class="text-info">ID:</strong> {{$job->id}}<br/>
                                            @if($job->queue === 'default')
                                                @foreach($job->display as $key=>$value)
                                                    <strong class="text-info">{{$key}}:</strong>  <em> {{$value}} </em>
                                                    <br/>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @else
                                <li class="list-group-item">
                                    <strong class="text-info">ID:</strong> {{$job->id}}
                                    <button type="button"
                                            class="btn btn-outline-light text-black-50 text-black text-bold"
                                            data-toggle="collapse" data-target="#exception{{$job->id}}">View Exception
                                    </button>
                                    <div id="exception{{$job->id}}" class="collapse">
                                        {{$job->exception}}
                                    </div>

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

@push('js')
    <script>
        $(document).ready(function () {

            $("#deleteChecked").click(function () {
                var values = $("input[name='jobs[]']").filter(function () {
                    return $(this).is(':checked');
                }).map(function () {
                    return $(this).val();
                }).get();
                if (values.length && confirm('Delete?')) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        method: 'POST',
                        url: "/dashboard/jobs/delete",
                        data: {jobs: values, type: '{{$type}}'},
                        success: function () {
                            location.reload();
                        },
                        error: function () {
                            toastr.error('Something went wrong');
                        }
                    });
                }

            });

            $("#checkAll").click(function () {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });
        })
    </script>
@endpush
