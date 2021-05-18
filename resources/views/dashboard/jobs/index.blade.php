@extends('dashboard.layouts')
<?php


?>
@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Active jobs</h1>
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
                            <thead>
                            <tr>

                                <th style="width: 20%">
                                    Uuid
                                </th>
                                <th style="width: 20%">
                                    Payload
                                </th>
                                <th>
                                    Created At
                                </th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($jobs as $job)
                                <tr>
                                    <td>
                                        #
                                    </td>
                                    <td>
                                        {{$job->uuid}}
                                    </td>
                                    <td>
                                        {!! $job->payload !!}
                                    </td>
                                    <td>
                                        {{$job->created_at->format('Y-m-d')}}
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $jobs->links('vendor.pagination') !!}

                </div>
            </div>

        </div>
    </section>

@endsection
