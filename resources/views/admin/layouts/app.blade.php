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
    <link href="{{ url('/assets/css/bootstrap-tour.min.css') }}" rel="stylesheet">
    <link href="{{ url('/assets/css/jquery-ui.css') }}" rel="stylesheet">
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
                
                @yield('content')
            </div>
        </div>
        <div id="loading-wheel" class="loading style-2" style="display: none"><div class="loading-wheel"></div></div>
    <script src="{{ url('/') }}/assets/js/jquery.min.js"></script>
    <script src="{{ url('/') }}/assets/js/jquery-ui.js"></script>
    <script src="{{ url('/') }}/assets/js/bootstrap.min.js"></script>
    <script src="{{ url('/') }}/assets/js/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/js/dataTables.bootstrap.min.js"></script>
    <script src="{{ url('/assets/js/bootstrap-tour.min.js') }}"></script>
    <script src="{{ url('/') }}/assets/js/functions.js" ></script>
    <script type="text/javascript">
        var username = "{{ Auth::user()->name }}";
    </script>
    <script src="{{ url('/') }}/assets/js/tour.js" ></script>
    <script type="text/javascript">
        // My Efunds Index

        function nextTour() {
           this.tour.next();
        }

        $(function() {
            $( "input.datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
          
        });
    </script>
    
    <style type="text/css">
        .popover[class*=tour-]{
          z-index: 15000;
        }
        
    </style>
    @yield('scripts')
    
</body>
</html>