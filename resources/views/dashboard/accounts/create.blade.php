@extends('dashboard.layouts')
@push('css')
    <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">

@endpush
@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create linkedin account</h1>
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
                    <form method="POST" action="{{route('accounts.store')}}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mx-auto mt-2">

                                <div class="card  card-primary">
                                    <div class="card-body">


                                        <div class="form-group">
                                            <label for="full_name">Full name *</label>
                                            <input type="text" id="full_name" class="form-control" name="full_name" value="{{old('full_name')}}">

                                            @error('full_name')
                                            <span class="invalid-feedback d-block" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                                            @enderror
                                        </div>




                                        <div class="form-group">
                                            <label for="person_email">Login *</label>
                                            <input type="text" id="login" class="form-control" name="login" value="{{old('login')}}">

                                            @error('login')
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

{{--                                        <div class="form-group">--}}
{{--                                            <label for="person_email">EntityUrn *</label>--}}
{{--                                            <input type="text" id="entityUrn" class="form-control" name="entityUrn" value="{{old('entityUrn')}}">--}}

{{--                                            @error('entityUrn')--}}
{{--                                            <span class="invalid-feedback d-block" role="alert">--}}
{{--                                          <strong>{{ $message }}</strong>--}}
{{--                                      </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
                                        <div class="form-group">
                                            <label for="limit_connection_request">Limit connection request *</label>
                                            <input type="number" min="1" id="limit_connection_request" class="form-control" name="limit_connection_request" value="{{old('limit_connection_request')}}">

                                            @error('limit_connection_request')
                                            <span class="invalid-feedback d-block" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                            @enderror
                                        </div>
{{--                                        <div class="form-group">--}}
{{--                                            <label for="limit_conversation">Limit conversation *</label>--}}
{{--                                            <input type="number" min="1" id="limit_conversation" class="form-control" name="limit_conversation" value="{{old('limit_conversation')}}">--}}

{{--                                            @error('limit_conversation')--}}
{{--                                            <span class="invalid-feedback d-block" role="alert">--}}
{{--                                          <strong>{{ $message }}</strong>--}}
{{--                                      </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}

{{--                                        <div class="form-group">--}}
{{--                                            <label>Cookie web *</label>--}}
{{--                                            <textarea class="form-control" name="cookie_str" rows="3">{{old('cookie_str')}}</textarea>--}}
{{--                                            @error('cookie_str')--}}
{{--                                            <span class="invalid-feedback d-block" role="alert">--}}
{{--                                          <strong>{{ $message }}</strong>--}}
{{--                                      </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}

{{--                                        <div class="form-group">--}}
{{--                                            <label>Cookie socket *</label>--}}
{{--                                            <textarea class="form-control" name="cookie_socket_str" rows="3">{{old('cookie_socket_str')}}</textarea>--}}
{{--                                            @error('cookie_socket_str')--}}
{{--                                            <span class="invalid-feedback d-block" role="alert">--}}
{{--                                          <strong>{{ $message }}</strong>--}}
{{--                                      </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
                                        <div class="form-group">
                                            <label>Proxies </label>
                                            <select  class="form-control w-100" required id="proxy_id" name="proxy_id">
                                                <option selected disabled>Select one </option>
                                                @foreach($proxies as $item)
                                                    <option value="{{$item['id']}}" @if(old('proxy_id') === $item['id']) selected @endif>{{$item['text']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Type </label>
                                            <select class="form-control" name="type" required>
                                                <option selected disabled value=""> Select one </option>
                                                <option value="1"> Real </option>
                                                <option value="2"> Unreal </option>
                                            </select>
                                        </div>
                                        <br/>
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

@push('js')
    <script src="/plugins/select2/js/select2.full.min.js"></script>
    <script src="/plugins/moment/moment.min.js"></script>



    <script>

        $(function () {

            $('.select2').select2({
                multiple: true,
                width: '100%'
            })

        });
    </script>
@endpush
