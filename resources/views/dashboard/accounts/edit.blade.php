@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit <span class="text-blue">{{$account->full_name}}</span> linkedin account</h1>
                </div>

            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    <a class="btn btn-primary btn-md float-right" id="um" href="{{route('accounts.index')}}">
                        <i class="fas fa-arrow-alt-circle-left"></i>
                        Back
                    </a>
                </div>

                <div class="card-body p-2">
                    <form method="POST" action="{{route('accounts.update',$account->id)}}">
                        @csrf
                        <input name="_method" type="hidden" value="PUT">

                        <div class="row">
                            <div class="col-md-12 mx-auto mt-2">

                                <div class="card  card-primary">
                                    <div class="card-body">


                                        <div class="form-group">
                                            <label for="full_name">Full name *</label>
                                            <input type="text" id="full_name" class="form-control" name="full_name" value="{{$account->full_name}}">

                                            @error('full_name')
                                            <span class="invalid-feedback d-block" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                                            @enderror
                                        </div>




                                        <div class="form-group">
                                            <label for="person_email">Login *</label>
                                            <input type="text" id="login" class="form-control" name="login" value="{{$account->login}}">

                                            @error('login')
                                            <span class="invalid-feedback d-block" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="person_email">Password *</label>
                                            <input type="text" id="password" class="form-control" name="password" value="{{$account->password}}">

                                            @error('password')
                                            <span class="invalid-feedback d-block" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="person_email">EntityUrn *</label>
                                            <input type="text" id="entityUrn" class="form-control" name="entityUrn" value="{{$account->entityUrn}}">

                                            @error('entityUrn')
                                            <span class="invalid-feedback d-block" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                            @enderror
                                        </div>


                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success float-right"><i class="fa fa-check-circle"></i> Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection


