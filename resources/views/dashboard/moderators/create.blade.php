@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Moderators create</h1>
                </div>


            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    <a class="btn btn-primary btn-sm float-right"  href="{{route('moderators.index')}}">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                </div>
                <div class="card-body p-2">
                      <form method="POST" action="{{route('moderators.store')}}">
                          @csrf
                          <div class="form-group">
                              <label for="name">Email *</label>
                              <input type="email" id="username" class="form-control" name="email"
                                     value="{{old('email')}}">

                              @error('email')
                              <span class="invalid-feedback d-block" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                              @enderror
                          </div>
                          <div class="form-group">
                              <label for="name">Password *</label>
                              <input type="text" id="password" class="form-control" name="password"
                                     value="{{old('password')}}">

                              @error('password')
                              <span class="invalid-feedback d-block" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                              @enderror
                          </div>
                          <div class="form-group">
                              <button type="submit" class="btn btn-success float-right"><i
                                      class="fa fa-check-circle"></i> Submit
                              </button>
                          </div>
                      </form>
                </div>

            </div>
        </div>


    </section>


@endsection


