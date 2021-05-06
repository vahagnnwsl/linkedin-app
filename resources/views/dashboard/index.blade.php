@extends('dashboard.layouts')

@section('sub_content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="card w-100">
                    <div class="card-body">
                        <h1 class="text-purple text-center">Welcome</h1>
                        <h2 class="text-purple text-center">{{\Illuminate\Support\Facades\Auth::user()->fullName}}</h2>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection
