@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Jobs</h1>
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
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-striped projects">

                            <tbody>
                            @foreach($jobs as $job)
                                @if($type ==='process')

                                  <tr>
                                      <ul class="list-group mt-2">
                                          <li class="list-group-item">  {{$job->id}}</li>
                                          <li class="list-group-item json" data-json="{{$job->payload}}"></li>
                                      </ul>
                                  </tr>
                                @else
                                    <tr>
                                        <ul class="list-group mt-2">
                                            <li class="list-group-item">  {{$job->uuid}}</li>
                                            <li class="list-group-item">{{$job->failed_at}}</li>
                                            <li class="list-group-item json" data-json="{{$job->payload}}">
                                            <li class="list-group-item json" data-json="{{$job->exception}}">

                                            </li>
                                        </ul>
                                    </tr>
                                @endif

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $jobs->appends($_GET)->links('vendor.pagination') !!}

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
