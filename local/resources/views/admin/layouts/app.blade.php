<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="shortcut icon" type="image-png" href="{{ url('/') }}/favicon.ico">
    <link rel="stylesheet" href="{{ url('/') }}/assets/fa/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/bootstrap.min.css">
    <link href="{{ url('/') }}/assets/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/css/custom.css">
    <link href="{{ url('/assets/css/bootstrap-tour.min.css') }}" rel="stylesheet">
    <link href="{{ url('/assets/css/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ url('/assets/css/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ url('/assets/css/jquery.confirm.min.css') }}" rel="stylesheet">
    <script src="{{ url('/assets/js/angular.min.js') }}"></script>
    <style type="text/css">
        .daterangepicker.dropdown-menu{
            z-index: 10000000;
            min-height: 275px
        }

    </style>
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-159577526-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-159577526-1');
</script>
	
</head>
<body id="app-layout">
        @yield('modals')
        <div id="wrapper" class="{{ Session::get('wrapper') }}"> 
            @include('admin.layouts.sidebar')
            <div id="page-content-wrapper">
            <div class="navbar page-title">
                <h2>{{ env('APP_NAME') }} <i style="font-size:10px"></i></h2>
            </div>
               
                @yield('content')
            </div>
        </div>
        <div id="loading-wheel" class="loading style-2" style="display: none"><div class="loading-wheel"></div></div>
    <script src="{{ url('/assets/js/jquery.min.js') }}"></script>
    <script src="{{ url('/assets/js/jquery-ui.js') }}"></script>
    <script src="{{ url('/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('/assets/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ url('/assets/js/bootstrap-tour.min.js') }}"></script>
    <script src="{{ url('/assets/js/functions.js') }}" ></script>
    <script src="{{ url('/assets/js/moment.js') }}" ></script>
    <script src="{{ url('/assets/js/daterangepicker.js') }}" ></script>
    <script src="{{ url('/assets/js/jquery.confirm.min.js') }}" ></script>
    <script type="text/javascript">
        var username = "{{ Auth::user()->name }}";
    </script>
    <script src="{{ url('/assets/js/tour.js') }}" ></script>
    <script type="text/javascript">
        // Dashboard Tour Initialization
        tour.init();
        tour.start();

        function nextTour() {
           this.tour.next();
        }

        $(function() {
            $( "input.datepicker" ).datepicker({ dateFormat: 'mm/dd/yy' });
        });

        // Date Range picker by daterangepicker.com
        $(function(){
            $('input.datepicker-range').val('')
            $('input.datepicker-range').daterangepicker({
                autoApply: true,
                autoUpdateInput: false,
               
            });
        });
        $('input.datepicker-range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('input.datepicker-range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        // Single Date picker
        $(function(){
            $('input.datepicker-single').daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                autoUpdateInput: false,
            });
        });

        $('input.datepicker-single').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY'));
        });

        $('input.datepicker-single').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
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