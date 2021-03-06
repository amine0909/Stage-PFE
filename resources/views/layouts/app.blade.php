<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name', 'Stage PFE') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <link href="{{ asset('dashboard_assets/node_modules/calendar/fullcalendar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard_assets/node_modules/simple-line-icons/css/simple-line-icons.css') }}" rel="stylesheet">
    <link href=" {{ asset('css/sweetalert2.min.css')}} " rel="stylesheet">
    <!--nav fix by adem-->
    <style>


        .navbar-nav>li>.dropdown-menu {
          background-color: #636b6f;
        }

        .navbar-default .navbar-nav>.open>a, .navbar-default .navbar-nav>.open>a:focus, .navbar-default .navbar-nav>.open>a:hover {
          background-color: #636b6f !important;
        }

        .navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus {
          background-color: #636b6f !important;
        }
        ul.nav a:hover {
          background-color: #4a5053 !important;
        }
        .swal2-popup {
  font-size: 1.6rem !important;
}
    </style>
</head>
<body>
  @if (!Auth::guest())
    @if (Auth::user()->role==1)
      @include('../navbar/teachernav')
    @else
      @include('../navbar/navbar')
    @endif
    @else
      @include('../navbar/navbar')

 @endif

@yield('content')

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('dashboard_assets/node_modules/moment/moment.js') }}"></script>
<script src="{{ asset('dashboard_assets/node_modules/calendar/fullcalendar.min.js') }}"></script>
<!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

<!-- SCRIPTS AMINE BEJOUI-->
<script src="{{ asset('js/jquery.js')}}"></script>
<script src="{{ asset('js/script-admin-group.js')}}"></script>
<script src="{{ asset('js/script-admin-students.js')}}"></script>
<script src="{{ asset('js/information.js')}}"></script>
<script src="{{ asset('js/sweetalert2.min.js')}}"></script>
<script>
var success;
var successAddStudent;

{{-- <script>
$(function() {
  $( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd' });
});
</script> --}}
</script>
</body>
</html>
