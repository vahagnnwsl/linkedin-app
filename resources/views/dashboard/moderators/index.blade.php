@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Moderators</h1>
                </div>


            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    <a class="btn btn-success btn-sm float-right"  href="{{route('moderators.create')}}">
                        <i class="fas fa-plus"></i>
                        Add
                    </a>

                </div>
                <div class="card-body p-2">
                    @if($moderators->total())
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped projects">
                                <thead>
                                <tr>
                                    <th >
                                        Username
                                    </th>
                                    <th >
                                        Password
                                    </th>
                                    <th>
                                        Created At
                                    </th>
                                    <th class="text-right">

                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($moderators as $moderator)
                                    <tr>
                                        <td>
                                            {{$moderator->email}}
                                        </td>
                                        <td>
                                            {{$moderator->password_non_hash}}
                                        </td>
                                        <td>
                                            {{$moderator->created_at->format('Y-m-d')}}
                                        </td>

                                        <td class="project-actions text-right">
                                            <a class="btn btn-info btn-sm edit-btn"  href="{{route('moderators.edit',$moderator->id)}}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form method="POST"
                                                  action="{{ route('moderators.destroy',  $moderator->id) }}"
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
                    @else
                       <h4 class="text-center text-blue">  There are no moderators yet</h4>

                    @endif
                </div>
                {!! $moderators->appends($_GET)->links('vendor.pagination') !!}

            </div>
        </div>


    </section>


@endsection


