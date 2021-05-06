<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>NWS LAB</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="shortcut icon" href="/favicon.png">

    <link rel="stylesheet" href="/plugins/toastr/toastr.css">
    <!-- Font Awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>

    @stack('css')

    <link rel="stylesheet" href="/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">

    <link href="/dist/css/loader.css"  rel="stylesheet">

</head>
<body class="{{request()->is('login*')|| request()->is('user-invitation*')  ?'hold-transition login-page':'sidebar-mini'}}">
@yield('content')

<script src="/plugins/jquery/jquery.min.js"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/dist/js/adminlte.min.js"></script>
<script src="/plugins/vue/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vee-validate@<3.0.0/dist/vee-validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
<script src="/plugins/toastr/toastr.min.js"></script>
<script src="https://unpkg.com/vue-select@latest"></script>

@stack('js')

<script>

    @foreach (['success', 'warning', 'error', 'info'] as $key)
    @if ($value = session($key))
    toastr.{{$key}}('{{session()->get($key)}}');
    @endif
    @endforeach

</script>

</body>
</html>
