<nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">{{ trans('navbar.toggle_navigation') }}</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">{{ trans('navbar.brand') }}</a>
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
					<a href="{{ route('start-selling') }}">{{ trans('navbar.start_selling') }}</a>
				</li>
				@if (Auth::check())
				<li class="dropdown">
					<a class="dropdown-toggle" href="" data-toggle="dropdown">{{ $user->username }} <b
							class="caret"></b></a>
					<ul class="dropdown-menu">
						<li>
							<a href="{{ route('user.dashboard', ['username' => Auth::user()->username]) }}">{{ trans('navbar.dashboard') }}</a>
						</li>
						<li><a href="{{ route('user.password') }}">{{ trans('navbar.password_settings') }}</a></li>
						<li class="divider"></li>
						<li>
							<a href="{{ route('auth.log-out') }}">{{ trans('navbar.log_out') }}</a>
						</li>
					</ul>
				</li>
				@else
				<li><a href="{{ route('user.sign-up') }}">{{ trans('navbar.sign_up') }}</a></li>
				<li><a href="{{ route('auth.log-in') }}">{{ trans('navbar.log_in') }}</a></li>
				@endif
			</ul>
		</div>
		<!-- /.navbar-collapse -->
	</div>
</nav>
