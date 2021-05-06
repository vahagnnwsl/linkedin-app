@extends('dashboard.layouts')



@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Linkedin Search</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="container-fluid">
                        @if($account)

                            <linkedin-search></linkedin-search>
                        @else
                            <h2 class="text-center"><span class="text-danger">Attention!</span> On your user not
                                connected any linkedin account</h2>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection



@push('js')
    <script src="/components/linkedin/search.js"></script>


@endpush
