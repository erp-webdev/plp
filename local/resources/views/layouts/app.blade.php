<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PLP</title>
    <link rel="shortcut icon" type="image-png" href="{{ url('/') }}/favicon.ico">

    <link rel="stylesheet" href="{{ url('/') }}/assets/fa/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/bootstrap.min.css">
    <link href="{{ url('/') }}/assets/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <script src="{{ url('/assets/js/angular.min.js') }}"></script>
</head>
<body id="app-layout">

    <nav class="navbar navbar-default navbar-static-top" hidden>
        <div class="container">
            <div class="navbar-header">
                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Template
                </a>
            </div>
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/home') }}">Home</a></li>
                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                    <!-- TODO: add permission to view settings -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ route('account.edit', Auth::user()->id) }}"><i class="fa fa-btn fa-user"></i> My Account</a></li>
                                <li><a href="{{ route('users.index') }}"><i class="fa fa-users"></i> Manage Accounts</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{ url('/') }}/assets/js/jquery.min.js"></script>
    <script src="{{ url('/') }}/assets/js/bootstrap.min.js"></script>
    <!-- DataTable 
         Note: Do not change the sequence of scripts-->
    <script src="{{ url('/') }}/assets/js/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/js/dataTables.bootstrap.min.js"></script>
    <script src="{{ url('/') }}/assets/js/functions.js" ></script>
</body>
</html>