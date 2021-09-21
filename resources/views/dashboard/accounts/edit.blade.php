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

{{--                                        @if($account->type === 1)--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="limit_connection_request">Limit connection request *</label>--}}
{{--                                            <input type="number" min="1" id="limit_connection_request" class="form-control" name="limit_connection_request" value="{{$account->limit_connection_request}}">--}}

{{--                                            @error('limit_connection_request')--}}
{{--                                            <span class="invalid-feedback d-block" role="alert">--}}
{{--                                          <strong>{{ $message }}</strong>--}}
{{--                                      </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="limit_conversation">Limit conversation *</label>--}}
{{--                                            <input type="number" min="1" id="limit_conversation" class="form-control" name="limit_conversation" value="{{$account->limit_conversation}}">--}}

{{--                                            @error('limit_conversation')--}}
{{--                                            <span class="invalid-feedback d-block" role="alert">--}}
{{--                                          <strong>{{ $message }}</strong>--}}
{{--                                      </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                        @else--}}
{{--                                            <input type="hidden"  name="limit_connection_request" value="1">--}}
{{--                                            <input type="hidden"  name="limit_conversation" value="1">--}}
{{--                                        @endif--}}
                                        <div class="form-group">
                                            <label>Cookie </label>
                                            <textarea class="form-control" name="cookie_web_str" rows="3">{{$account->cookie_web_str}}</textarea>
                                            @error('cookie_web_str')
                                            <span class="invalid-feedback d-block" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Cookie socket </label>
                                            <textarea class="form-control" name="cookie_socket_str" rows="3">{{$account->cookie_socket_str}}</textarea>
                                            @error('cookie_socket_str')
                                            <span class="invalid-feedback d-block" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Proxies </label>
                                            <select  class="form-control w-100" required id="proxy_id" name="proxy_id">
                                                <option selected disabled>Select one </option>
                                                @foreach($proxies as $item)
                                                    <option value="{{$item['id']}}" @if($account->proxy_id === $item['id']) selected @endif>{{$item['text']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Type </label>
                                            <select class="form-control" name="type" required>
                                                <option value="1" @if($account->type === 1) selected @endif> Real </option>
                                                <option value="2" @if($account->type === 2) selected @endif> Unreal </option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Status </label>
                                            <select class="form-control" name="status" required>
                                                <option value="0"  @if($account->status === 0) selected @endif>
                                                    Inactive
                                                </option>
                                                <option value="1"  @if($account->status === 1) selected @endif>
                                                    Active
                                                </option>
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

    <script>

        $(function () {

            $('.select2').select2({
                multiple: true,
                width: '100%'
            })

        });
    </script>
@endpush

