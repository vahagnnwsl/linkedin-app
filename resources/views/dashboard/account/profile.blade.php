@extends('dashboard.layouts')
@push('css')
    <link rel="stylesheet" href="/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">

@endpush
@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Your profile</h1>
                </div>

            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">

                <div class="card-body p-2">
                    <form class="form-horizontal" method="POST" action="{{route('account.profile.update')}}" enctype="multipart/form-data">
                        <input name="_method" type="hidden" value="PUT">
                        @csrf
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">First
                                name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName"
                                       placeholder="First name" value="{{\Illuminate\Support\Facades\Auth::user()->first_name}}" name="first_name">
                                @error('first_name')
                                <span class="invalid-feedback d-block" role="alert">
                                                                  <strong>{{ $message }}</strong>
                                                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputLatName" class="col-sm-2 col-form-label">Last
                                name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputLatName"
                                       placeholder="Last name" value="{{\Illuminate\Support\Facades\Auth::user()->last_name}}" name="last_name">
                                @error('last_name')
                                <span class="invalid-feedback d-block" role="alert">
                                      <strong>{{ $message }}</strong>
                                 </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail"
                                   class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="inputEmail"
                                       placeholder="Email" value="{{\Illuminate\Support\Facades\Auth::user()->email}}" readonly>
                                @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                                                  <strong>{{ $message }}</strong>
                                                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputFile"
                                   class="col-sm-2 col-form-label">Avatar</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" id="inputFile"
                                       name="avatar"  style="padding-top: 3px!important;">
                                @error('avatar')
                                <span class="invalid-feedback d-block" role="alert">
                                                                  <strong>{{ $message }}</strong>
                                                            </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Color :</label>

                            <div class="input-group my-colorpicker2 col-sm-10">
                                <input type="text" class="form-control colorpicker" name="color" value="{{\Illuminate\Support\Facades\Auth::user()->color}}">

                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-square"></i></span>
                                </div>
                            </div>
                            @error('color')
                            <span class="invalid-feedback d-block" role="alert">
                                                                  <strong>{{ $message }}</strong>
                                                            </span>
                            @enderror

                        </div>

{{--                        <div class="form-group row">--}}
{{--                            <label for="linkedin_login" class="col-sm-2 col-form-label">Linkedin login</label>--}}
{{--                            <div class="col-sm-10">--}}
{{--                                <input type="text" class="form-control" id="linkedin_login"--}}
{{--                                       placeholder="Linkedin login" value="{{\Illuminate\Support\Facades\Auth::user()->linkedin_login}}" name="linkedin_login">--}}
{{--                                @error('linkedin_login')--}}
{{--                                <span class="invalid-feedback d-block" role="alert">--}}
{{--                                      <strong>{{ $message }}</strong>--}}
{{--                                 </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="linkedin_password" class="col-sm-2 col-form-label">Linkedin password</label>--}}
{{--                            <div class="col-sm-10">--}}
{{--                                <input type="text" class="form-control" id="linkedin_password"--}}
{{--                                       placeholder="Linkedin password" value="{{\Illuminate\Support\Facades\Auth::user()->linkedin_password}}" name="linkedin_password">--}}
{{--                                @error('linkedin_password')--}}
{{--                                <span class="invalid-feedback d-block" role="alert">--}}
{{--                                      <strong>{{ $message }}</strong>--}}
{{--                                 </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

                            <!-- /.input group -->

                        <div class="form-group row">
                            <div class="offset-sm-2 col-sm-10">
                                <button type="submit" class="btn btn-success float-right"> <i class="fa fa-check-circle mr-1"></i>Submit </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
        <script>

        </script>
    </section>


@endsection
@push('js')
    <script src="/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>

    <script>
       $(document).ready(function (){
           $('.my-colorpicker2').colorpicker()
           $('.input-group-text').css({color : "{{\Illuminate\Support\Facades\Auth::user()->color}}"})

           $('.my-colorpicker2').on('colorpickerChange', function(event) {
               $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
           })
       })
    </script>
@endpush
