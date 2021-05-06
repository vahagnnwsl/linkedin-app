@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create user</h1>
                </div>

            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    <a class="btn btn-primary btn-md float-right" id="um" href="{{route('users.index')}}">
                        <i class="fas fa-arrow-alt-circle-left"></i>
                        Back
                    </a>
                </div>

                <div class="card-body p-2">
                    <form method="POST" action="{{route('users.store')}}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mx-auto mt-2">

                                <div class="card  card-primary">
                                    <div class="card-body">


                                        <div class="form-group">
                                            <label for="first_name">First name *</label>
                                            <input type="text" id="first_name" class="form-control" name="first_name" value="{{old('first_name')}}">

                                            @error('first_name')
                                            <span class="invalid-feedback d-block" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="first_name">Last name *</label>
                                            <input type="text" id="last_name" class="form-control" name="last_name" value="{{old('last_name')}}">

                                            @error('last_name')
                                            <span class="invalid-feedback d-block" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                                            @enderror
                                        </div>



                                        <div class="form-group">
                                            <label for="person_email">Email *</label>
                                            <input type="email" id="email" class="form-control" name="email" value="{{old('email')}}">

                                            @error('email')
                                            <span class="invalid-feedback d-block" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="person_email">Password *</label>
                                            <input type="text" id="password" class="form-control" name="password" value="{{old('password')}}">

                                            @error('password')
                                            <span class="invalid-feedback d-block" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password *</label>
                                            <input type="text" id="password_confirmation" class="form-control" name="password_confirmation" value="{{old('password_confirmation')}}">

                                            @error('password_confirmation')
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


