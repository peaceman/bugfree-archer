@section('wrapped')
@include('common.navbar', ['categories' => []])
<div class="container">
	@include('common.messages')
	@yield('content', 'no content available')
</div>
@stop
<!DOCTYPE html>
<html>
<head>
	<title>@yield('title', 'EDM Market')</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Bootstrap -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">

    <link rel="stylesheet" href="{{ asset('/assets/selectize/css/selectize.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/selectize/css/selectize.bootstrap3.css') }}"/>
	<link rel="stylesheet" href="{{ asset('/assets/styles.css') }}"/>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="//code.jquery.com/jquery.js"></script>
	<script src="{{ asset('/assets/jquery-ui.js') }}"></script>
	@yield('scripts.header', '')
</head>
<body>
@yield('wrapped')

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//code.jquery.com/jquery.js"></script>
<script src="{{ asset('/assets/jquery-ui.js') }}"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script src="{{ asset('/assets/selectize/js/standalone/selectize.js') }}"></script>
<script src="{{ asset('/assets/scripts.js') }}"></script>
@yield('scripts.footer', '')
</body>
</html>
