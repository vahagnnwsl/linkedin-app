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
                    <h1>Edit key</h1>
                </div>

            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    <a class="btn btn-primary btn-md float-right" id="um" href="{{route('keys.index')}}">
                        <i class="fas fa-arrow-alt-circle-left"></i>
                        Back
                    </a>
                </div>

                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-12 p-3">
                            <form method="POST" action="{{route('keys.update',$key->id)}}">
                                @csrf
                                <input name="_method" type="hidden" value="PUT">

                                <div class="form-group">
                                    <label>Name *</label>
                                    <input name="name" required class="form-control" type="text" value="{{$key->name}}">
                                </div>


                                <div class="form-group">
                                    <label>Account *</label>
                                    <select multiple="multiple" class="select2 form-control" required data-placeholder="Select something" id="accounts_id" name="accounts_id[]">
                                        @foreach($accounts as $item)
                                            <option value="{{$item['id']}}"
                                                    @if(in_array($item['id'],$key->accounts()->pluck('id')->toArray())) selected @endif
                                            >{{$item['text']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Country *</label>
                                    <select class="form-control" name="country_id" required>
                                        <option selected disabled value="">Select one</option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}"  @if($key->country_id === $country->id) selected @endif>
                                                {{$country->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>



                                <div class="form-group">
                                    <label>Status </label>
                                    <select class="form-control" name="status" required>
                                        <option value="0"  @if($key->status === 0) selected @endif>
                                           Inactive
                                        </option>
                                        <option value="1"  @if($key->status === 1) selected @endif>
                                            Active
                                        </option>
                                    </select>
                                </div>
                                <br/>
                                <div class="btn-group float-right">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>

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
