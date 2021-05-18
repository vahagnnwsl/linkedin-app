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
                <form method="POST" action="{{route('search.linkedin')}}" class="w-100 p-2">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <select class="form-control" name="key_id" required>
                                <option value="" selected>Select one</option>
                                @foreach($keys as $key)
                                    <option value="{{$key->id}}">{{ $key->name }}</option>

                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <select class="form-control" name="company_id"  required>
                                <option value="" selected>Select one</option>
                                @foreach($companies as $company)
                                    <option value="{{$company->id}}">{{ $company->name }}</option>

                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <select class="form-control" name="country_id"  required>
                                <option value="" selected>Select one</option>
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}">{{ $country->name }}</option>

                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mt-2">
                            <button class="btn btn-primary float-right">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </section>


@endsection


