<!DOCTYPE html>
<html>
<head>
	  <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/ionicons/css/ionicons.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/AdminLTE.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/_all-skins.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/main.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/jquery-ui/jquery-ui.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/js/clockpicker/clockpicker.css')}}">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<style type="text/css">

  .teste{

    background-size: cover;
    background-attachment: fixed;
    background-repeat: no-repeat;
    z-index:99999;
   }
</style>
<body class="hold-transition sidebar-mini skin-red-light">
<div class="wrapper">

  @include('includes.header-dashboard')

  @include('includes.sidebar-left-dashboard')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper teste">
    <div class="dashboard-central" >
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        @yield('title')
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"><i class="fa fa-dashboard"></i> Página Principal</a></li>
        <li class="{{ Request::is('dashboard') ? 'class="active"' : '' }}">@yield('title')</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	@yield('content')

    </section>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; <a href="http://www.mfconsulting.com.br">MF Consulting</a> - {{ date('Y') }}</strong> Todos os direitos reservados.
  </footer>

	<script src="{{URL::to('src/js/jquery.min.js')}}"></script>
	<script src="{{URL::to('src/css/bootstrap/js/bootstrap.min.js')}}"></script>
  <script src="{{URL::to('src/js/clockpicker/clockpicker.js')}}"></script>
	<script src="{{URL::to('src/js/app.min.js')}}"></script>
	<script src="{{URL::to('src/js/app.js')}}"></script>
  <script src="{{URL::to('src/js/jquery.maskMoney.min.js')}}"></script>
  <script src="{{URL::to('src/js/mascara.js')}}"></script>

	<!-- JQuery Validation -->
	<script src="{{URL::to('src/js/jquery-validation/dist/jquery.validate.min.js')}}"></script>
	<script src="{{URL::to('src/js/jquery-validation/dist/validation-forms.js')}}"></script>
  <script src="{{URL::to('src/js/jquery-ui.min.js')}}"></script>  

</body>
</html>


