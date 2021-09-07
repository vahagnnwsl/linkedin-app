@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        Categories <span style="float: right" class="text-blue  mr-2">Total: {{$categories->total()}}</span>
                    </h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    <a class="btn btn-success btn-md float-right"  href="{{route('categories.create')}}">
                        <i class="fas fa-plus"></i>
                        Add
                    </a>

                </div>
                <div class="card-body p-0">

                    <table class="table table-striped ">
                        <thead>
                        <tr>
                            <th style="width: 1%">
                                #
                            </th>
                            <th style="width: 5%">
                                Name
                            </th>
                            <th style="width: 20%">
                                Parent
                            </th>

                            <th style="width: 20%">

                            </th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>
                                    #{{$category->id}}
                                </td>
                                <td class="text-black-50 text-bold">
                                    {{$category->name}}
                                </td>
                                <td class="text-info text-bold">
                                    {{$category->parent()->exists()?$category->parent->name:''}}
                                </td>
                                <td class="text-right">
                                    <form method="POST"
                                          action="{{ route('categories.destroy',  $category->id) }}"
                                          accept-charset="UTF-8"
                                          style="display:inline">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                title="Delete Permission"
                                                onclick="return confirm(&quot;Confirm delete?&quot;)">
                                            <i class="fas fa-trash"> </i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>

                </div>

                {!! $categories->appends($_GET)->links('vendor.pagination') !!}


            </div>
        </div>

    </section>
@endsection



