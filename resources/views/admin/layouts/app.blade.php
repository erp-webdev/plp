<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Megaworld eFund</title>
    <link rel="shortcut icon" type="image-png" href="{{ url('/') }}/favicon.ico">
    <link rel="stylesheet" href="{{ url('/') }}/assets/fa/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/bootstrap.min.css">
    <link href="{{ url('/') }}/assets/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/css/custom.css">
    <script src="{{ url('/assets/js/angular.min.js') }}"></script>
</head>
<body id="app-layout">
        @yield('modals')
        <div id="wrapper" class="{{ Session::get('wrapper') }}"> 
            @include('admin.layouts.sidebar')
            <div id="page-content-wrapper">
            <div class="navbar page-title">
                <h2>Megaworld eFund <i style="font-size:10px">(v1.0)</i></h2>
            </div>
                @if ($message = Session::get('success'))
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    </div>
                @elseif ($message = Session::get('error'))
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="alert alert-danger">
                            <p>{{ $message }}</p>
                        </div>
                    </div>
                @elseif ($message = Session::get('info'))
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="alert alert-info">
                            <p>{{ $message }}</p>
                        </div>
                    </div>
                @elseif ($message = Session::get('warning'))
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="alert alert-warning">
                            <p>{{ $message }}</p>
                        </div>
                    </div>
                @elseif(count($errors)>0)
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="alert alert-danger col-xs-12 col-sm-5 col-md-5">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>    
                @endif
                @yield('content')
            </div>
        </div>
        <div id="loading-wheel" class="loading style-2" style="display: none"><div class="loading-wheel"></div></div>
    <script src="{{ url('/') }}/assets/js/jquery.min.js"></script>
    <script src="{{ url('/') }}/assets/js/bootstrap.min.js"></script>
    <script src="{{ url('/') }}/assets/js/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/js/dataTables.bootstrap.min.js"></script>
    <script src="{{ url('/') }}/assets/js/functions.js" ></script>
    @yield('scripts')
</body>
</html>