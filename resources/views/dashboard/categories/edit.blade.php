@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create category</h1>
                </div>

            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    <a class="btn btn-primary btn-md float-right" id="um" href="{{route('categories.index')}}">
                        <i class="fas fa-arrow-alt-circle-left"></i>
                        Back
                    </a>
                </div>

                <div class="card-body p-2">
                    <form method="POST" action="{{route('categories.update',$category->id)}}">
                        @csrf
                        <input name="_method" type="hidden" value="PUT">
                        <div class="row">
                            <div class="col-md-12 mx-auto mt-2">

                                <div class="card  card-primary">
                                    <div class="card-body">


                                        <div class="form-group">
                                            <label for="name">Name *</label>
                                            <input type="text" id="full_name" class="form-control" name="name"
                                                   value="{{$category->name}}">

                                            @error('name')
                                            <span class="invalid-feedback d-block" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                                            @enderror
                                        </div>


                                        <div class="form-group">
                                            <label>Parent</label>
                                            <select class="form-control w-100"  name="parent_id">
                                                <option selected value="">Select one </option>
                                                @foreach($categories as $item)
                                                    @if($item->id !==$category->id)
                                                    <option  @if ( $item->id === $category->parent_id) selected @endif  value="{{$item->id}}">{{$item->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('parent_id')
                                            <span class="invalid-feedback d-block" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="roles">Show oin moderators *</label>
                                            <select class="form-control" name="isShowModerators">
                                                <option selected disabled>Select</option>
                                                <option value="1" @if ( $category->isShowModerators === 1) selected @endif>Show</option>
                                                <option value="0" @if ( $category->isShowModerators === 0) selected @endif>Hide</option>
                                            </select>
                                            @error('isShowModerators')
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



