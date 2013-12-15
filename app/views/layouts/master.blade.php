<!DOCTYPE html>
<html>
<head>
	<title>EDM Market</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Bootstrap -->
	<link href="{{ asset('/assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<style>
		/* Sticky footer styles
		-------------------------------------------------- */

		html,
		body {
			height: 100%;
			/* The html and body elements cannot have any padding or margin. */
		}

		/* Wrapper for page content to push down footer */
		#wrap {
			min-height: 100%;
			height: auto !important;
			height: 100%;
			/* Negative indent footer by its height */
			margin: 0 auto -60px;
			/* Pad bottom by footer height */
			padding: 0 0 60px;
		}

		/* Set the fixed height of the footer here */
		#footer {
			height: 60px;
			background-color: #f5f5f5;
		}

		/* Custom page CSS
		-------------------------------------------------- */
		/* Not required for template or sticky footer method. */

		#wrap > .container {
			padding: 60px 15px 0;
		}

		.container .credit {
			margin: 20px 0;
		}

		#footer > .container {
			padding-left: 15px;
			padding-right: 15px;
		}

		code {
			font-size: 80%;
		}
	</style>
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
</body>
</html>
