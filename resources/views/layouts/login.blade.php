<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/main.css')}}">
</head>
<body>
	<div class="container">
		@yield('content')
	</div>
	<script src="{{URL::to('src/js/jquery.min.js')}}"></script>
	<script src="{{URL::to('src/css/bootstrap/js/bootstrap.min.js')}}"></script>
	<script src="{{URL::to('src/js/app.js')}}"></script>
	<!-- JQuery Validation -->
	<script src="{{URL::to('src/js/jquery-validation/dist/jquery.validate.min.js')}}"></script>
	<script src="{{URL::to('src/js/jquery-validation/dist/validation-forms.js')}}"></script>
</body>
</html>
