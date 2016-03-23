<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta property="qc:admins" content="36103474056747676375" />
    <title>Lawood</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="//cdn.bootcss.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <!--[if IE 7]>
    <link href="//cdn.bootcss.com/font-awesome/3.2.1/css/font-awesome-ie7.min.css" rel="stylesheet">
    <![endif]-->
    <!-- Ionicons -->
    <link href="//cdn.bootcss.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
    @yield('css')
    <link rel="stylesheet" href="{{URL::asset('/')}}css/AdminLTE.min.css">
    <link rel="stylesheet" href="{{URL::asset('/')}}css/skins/skin-green.min.css">
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition skin-green sidebar-mini">
<!-- Header -->
@include('base.area.header')
@yield('content')
<!-- Footer -->
@include('base.area.footer')
<script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
@yield('script')
</body>
</html>