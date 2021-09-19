@extends('dashboard.layouts')
@push('css')
    <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">

@endpush
@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> {{$connection->fullName}}</h1>
                </div>

            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    <a class="btn btn-primary btn-md float-right" id="um" href="{{route('connections.index')}}">
                        <i class="fas fa-arrow-alt-circle-left"></i>
                        Back
                    </a>
                </div>

                <div class="card-body p-2">
                    <div class="row gutters-sm">
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img class="img-bordered" src="{{$connection->image}}"
                                             onerror="this.src='/dist/img/lin_def_image.svg'" width="150">

                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <ul class="list-group list-group-flush">
                                    @foreach($connection->skills as $skill)
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0 text-bold text-info">
                                                {{$skill->name}}
                                            </h6>
                                            <span class="text-secondary  text-bold">
                                             {{$skill->pivot->like_count }}
                                        </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0 text-bold">First name</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary text-bold">
                                            {{$connection->firstName}}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0 text-bold">Last name</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary  text-bold">
                                            {{$connection->lastName}}
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0 text-bold">Occupation</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary  text-bold">
                                            {{$connection->occupation}}
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>

                            <div class="row gutters-sm">
                                <div class="col-sm-12 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="row">
                                                <h5 class="text-bold pl-2 text-info">Positions</h5>
                                                @foreach($connection->positions as $position)
                                                    <div class="col-12 border">
                                                        @if($position->company)
                                                            <h5 class="text-bold text-black-50">
                                                                <a href="/dashboard/connections?companies%5B%5D={{ $position->company->id }}">
                                                                    {{ $position->company->name }}
                                                                </a>

                                                            </h5>
                                                        @endif

                                                        <p class="mt-2">
                                                            <mark>{{ $position->name }}</mark>
                                                            @if($position->start_date)
                                                                |  <em>{{ $position->start_date->format('F  Y') }}</em>
                                                            @endif
                                                            -
                                                            @if($position->end_date)
                                                                <em>{{ $position->end_date->format('F  Y') }}</em>
                                                            @else
                                                                <em> Now</em>
                                                            @endif
                                                            <em class="float-right">  {{ $position->duration }}</em>
                                                        </p>
                                                        <p><em>
                                                                <mark>{{ $position->description }}</mark>
                                                            </em></p>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gutters-sm">
                                <div class="col-sm-12 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h5 class="text-bold pl-2 text-info">Statuses
                                                <a href="#" class="btn btn-success float-right" data-toggle="modal"
                                                   data-target="#statusModal">
                                                    Add
                                                </a>
                                            </h5>

                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach($connection->statuses as $status)
                                                    <div
                                                        class="col-12  border-bottom  {{$status->is_last === 1 ? 'border-warning border' : ''}}">
                                                        <h5 class="text-bold text-black-50">{{$status->category->name}}</h5>
                                                        <p>
                                                            <em>
                                                                <mark>{{$status->comment}} </mark>
                                                            </em>
                                                        </p>
                                                    </div>
                                                @endforeach

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gutters-sm">
                                <div class="col-sm-12 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h5 class="text-bold pl-2 text-info">Keys</h5>

                                        </div>
                                        <div class="card-body">
                                            <form action="{{route('connections.addKeys',$connection->id)}}" method="POST">
                                                @csrf
                                                <div class="row">

                                                    <div class="col-md-12">
                                                        <div class="form-group">

                                                            <select multiple="multiple" class="select2 form-control"
                                                                    data-placeholder="Select something" id="keys"
                                                                    name="keys[]">
                                                                @foreach($keys as $key)
                                                                    <option
                                                                        @if(in_array($key->id,$connection->keys()->pluck('keys.id')->toArray())) selected
                                                                        @endif value="{{$key->id}}">{{$key->name}}</option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                        <div class="form-group">
                                                            <button class="btn btn-success float-right">Add</button>
                                                        </div>

                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="statusModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="min-height: 400px">
                <div class="modal-content" style="min-height: 400px">
                    <div class="modal-header  bg-info">
                        <h4 class="modal-title">Add status</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('connections.addStatus',$connection->id)}}">
                            @csrf

                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control w-100" name="category_id" aria-required="true" required>
                                    <option selected disabled value="">Select one</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}} </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <span class="invalid-feedback d-block" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Comment</label>
                                <textarea class="form-control" name="comment" rows="4"></textarea>
                                <div class="form-group">
                                    <button class="btn btn-success mt-2 float-right">Submit</button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
        `,
    </section>
@endsection

@push('js')
    <script src="/plugins/select2/js/select2.full.min.js"></script>
    <script src="/plugins/moment/moment.min.js"></script>

    <script src="/plugins/daterangepicker/daterangepicker.js"></script>


    <script>

        $(function () {

            $('.select2').select2({
                multiple: true,
            })
        });

    </script>
@endpush
