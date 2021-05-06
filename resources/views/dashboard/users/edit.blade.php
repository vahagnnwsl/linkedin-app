@extends('dashboard.layouts')
@push('css')

    <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
@endpush
@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit user <span class="text-blue">{{$user->full_name}}</span></h1>
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
                    <form method="POST" action="{{route('users.update',$user->id)}}">
                        @csrf
                        <input name="_method" type="hidden" value="PUT">

                        <div class="row">
                            <div class="col-md-12 mx-auto mt-2">

                                <div class="card  card-primary">
                                    <div class="card-body">


                                        <div class="form-group">
                                            <label for="first_name">First name *</label>
                                            <input type="text" id="first_name" class="form-control" name="first_name" value="{{$user->first_name}}">

                                            @error('first_name')
                                            <span class="invalid-feedback d-block" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="first_name">Last name *</label>
                                            <input type="text" id="last_name" class="form-control" name="last_name" value="{{$user->last_name}}">

                                            @error('last_name')
                                            <span class="invalid-feedback d-block" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                                            @enderror
                                        </div>



                                        <div class="form-group">
                                            <label for="person_email">Email *</label>
                                            <input type="email" id="email" class="form-control" name="email" value="{{$user->email}}">

                                            @error('email')
                                            <span class="invalid-feedback d-block" role="alert">
                                          <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="roles">Roles *</label>
                                            <select class="form-control" name="role_id">
                                                <option selected disabled>Select</option>
                                                @foreach($roles as $role)
                                                    <option @if($user->role && $user->role->id === $role->id) selected @endif value="{{$role->id}}">{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="roles">Account </label>
                                            <select class="form-control" name="account_id">
                                                <option selected disabled>Select</option>
                                                @foreach($accounts as $account)
                                                    <option @if($user->account && $user->account->id === $account->id) selected @endif value="{{$account->id}}">{{$account->full_name}}</option>
                                                @endforeach
                                            </select>
                                            @error('account_id')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="keys_ides">Keys </label>
                                            <select class="select2" style="width: 100%;" name="keys_ides[]" id="keys_ides" multiple="multiple">

                                                @foreach($keys as $key)
                                                    <option value="{{$key->id}}" @if(in_array($key->id,$user->keys()->pluck('id')->toArray())) selected @endif>#{{$key->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('stacks')
                                            <span class="invalid-feedback d-block" role="alert">
                                                 <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputRoles"
                                                   class="col-sm-2 col-form-label">Status</label>
                                            <div class="col-md-10">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="active__status" type="radio" name="status" {{$user->status ===1?'checked':''}} value="1">
                                                    <label class="form-check-label" for="active__status">Active</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input"   id="inactive__status"type="radio" name="status" {{$user->status===0?'checked':''}} value="0">
                                                    <label class="form-check-label" for="inactive__status">Inactive</label>
                                                </div>
                                            </div>
                                            @error('status')
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

@push('js')
    <script src="/plugins/select2/js/select2.full.min.js"></script>
    <script>

        $(function () {

            $('.select2').select2()
        });
    </script>
@endpush
