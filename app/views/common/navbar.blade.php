<nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">EDM Market</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				@foreach ($categories as $category)
				<li>
					<a href="/{{ $category->slug }}">{{ $category->name }}</a>
				</li>
				@endforeach
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="/sell">{{ trans('navbar.start-selling') }}</a>
				</li>
				@if ($user)
				<li class="dropdown">
					<a class="dropdown-toggle" href="" data-toggle="dropdown">{{ $user->username }} <b
							class="caret"></b></a>
					<ul class="dropdown-menu">
						<li>
							<a href="{{ route('user.settings') }}">{{ trans('navbar.settings') }}</a>
						</li>
						<li><a href="{{ route('user.password') }}">{{ trans('navbar.change-password') }}</a></li>
						<li class="divider"></li>
						<li>
							<a href="{{ route('auth.log-out') }}">{{ trans('navbar.log-out') }}</a>
						</li>
					</ul>
				</li>
				@else
				<li><a href="{{ route('user.sign-up') }}">{{ trans('navbar.sign-up') }}</a></li>
				<li><a href="{{ route('auth.log-in') }}">{{ trans('navbar.log-in') }}</a></li>
				@endif
			</ul>
		</div>
		<!-- /.navbar-collapse -->
	</div>
</nav>
