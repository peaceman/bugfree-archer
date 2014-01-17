<!DOCTYPE html>
<html>
<head>
	<title>EDM Market</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Bootstrap -->
	<link rel="stylesheet" href="{{ asset('/assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/selectize/css/selectize.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/selectize/css/selectize.bootstrap3.css') }}"/>
	<link rel="stylesheet" href="{{ asset('/assets/styles.css') }}"/>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
</head>
<body>
<div id="wrap">
	@include('common.navbar', ['categories' => [], 'user' => null])
	<div class="container">
		@include('common.messages')
		@yield('content', 'no content available')
	</div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//code.jquery.com/jquery.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{ asset('/assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/assets/selectize/js/standalone/selectize.js') }}"></script>
<script src="{{ asset('/assets/scripts.js') }}"></script>
</body>
</html>
