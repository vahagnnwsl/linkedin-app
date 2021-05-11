@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Search in Linkedin </h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                   <linkedin-search :keys="{{$keys}}" :companies="{{$companies}}"></linkedin-search>
            </div>
        </div>

    </section>


@endsection


@push('js')
    <script src="/components/linkedin/search.js"></script>

@endpush
