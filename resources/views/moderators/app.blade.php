<!DOCTYPE html>
<html lang="en">
<head>
    <title>Conversation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
      @stack('css')
</head>
<body >
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="margin:24px 0;">

    <div class="collapse navbar-collapse" id="navb">

        <a href="/logout" class="btn btn-danger my-2 my-sm-0"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
        <form id="logout-form" action="/logout" method="POST" style="display: none;">
            {{csrf_field()}}
        </form>

    </div>
</nav>
@yield('content')
@stack('js')
</body>
</html>

