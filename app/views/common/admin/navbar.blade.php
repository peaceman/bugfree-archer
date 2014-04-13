<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#collapsable-navbar">
				<span class="sr-only">{{ trans('navbar.toggle_navigation') }}</span>
				<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/admin">{{ trans('admin.navbar.brand') }}</a>
		</div>
		<div class="collapse navbar-collapse" id="collapsable-navbar">
			<ul class="nav navbar-nav">

			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="/">{{ trans('admin.navbar.frontend') }}</a>
				</li>
				<li class="dropdown">
					<a class="dropdown-toggle" href="" data-toggle="dropdown">{{ $user->username }} <b
							class="caret"></b></a>
					<ul class="dropdown-menu">
						<li>
							<a href="{{ route('user.dashboard', ['username' => $user->username]) }}">{{ trans('navbar.dashboard') }}</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="{{ route('auth.log-out') }}">{{ trans('navbar.log_out') }}</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>
