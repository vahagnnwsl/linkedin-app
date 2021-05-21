@extends('dashboard.layouts')

@section('sub_content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit proxy</h1>
                </div>

            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-2">
                    <a class="btn btn-primary btn-md float-right" id="um" href="{{route('proxies.index')}}">
                        <i class="fas fa-arrow-alt-circle-left"></i>
                        Back
                    </a>
                </div>

                <div class="card-body p-2">
                     <div class="row">
                         <div class="col-md-12 p-3">
                             <form method="POST" action="{{route('proxies.update',$proxy->id)}}">
                                 @csrf
                                 <input name="_method" type="hidden" value="PUT">

                                 <div class="form-group">
                                     <label>Login *</label>
                                     <input name="login" required class="form-control" type="text" value="{{$proxy->login}}">
                                 </div>
                                 <div class="form-group">
                                     <label>Password *</label>
                                     <input name="password" required class="form-control" type="text" value="{{$proxy->password}}">
                                 </div>
                                 <div class="form-group">
                                     <label>Ip *</label>
                                     <input name="ip" required class="form-control" type="text" value="{{$proxy->ip}}">
                                 </div>

                                 <div class="form-group">
                                     <label>Port *</label>
                                     <input name="port" required class="form-control" type="text" value="{{$proxy->port}}">
                                 </div>
                                 <div class="form-group">
                                     <label>Country *</label>
                                     <input name="country" required class="form-control" type="text" value="{{$proxy->country}}">
                                 </div>
                                 <div class="form-group">
                                     <label>Type *</label>
                                     <input name="type" required class="form-control" type="text" value="{{$proxy->type}}">
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


