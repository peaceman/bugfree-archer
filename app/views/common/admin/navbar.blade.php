<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#collapsable-navbar">
				<span class="sr-only">{{{ trans('navbar.toggle_navigation') }}}</span>
				<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/admin">{{{ trans('admin.navbar.brand') }}}</a>
		</div>
		<div class="collapse navbar-collapse" id="collapsable-navbar">
			<ul class="nav navbar-nav">
				<li>
					<a href="{{{ route('admin.users.index') }}}">
						{{{ trans('admin.navbar.users') }}}
					</a>
				</li>
				<li>
					<a href="{{{ route('admin.shop-items.index') }}}">
						{{{ trans('admin.navbar.shop_items') }}}
					</a>
				</li>
				<li>
					<a href="{{{ route('admin.shop-categories.index') }}}">
						{{{ trans('admin.navbar.shop_categories') }}}
					</a>
				</li>
				<li>
					<a href="{{{ route('admin.reviews.index') }}}">
						{{{ trans('admin.navbar.reviews') }}}
						<span class="badge">{{{ $amountOfWaitingReviews or '' }}}</span>
					</a>
				</li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="">
						{{{ trans('admin.navbar.music_software') }}} <b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="{{{ route('admin.music-programs.index') }}}">
								{{{ trans('admin.navbar.music_programs') }}}
							</a>
						</li>
						<li>
							<a href="{{{ route('admin.music-plugins.index') }}}">
								{{{ trans('admin.navbar.music_plugins') }}}
							</a>
						</li>
						<li>
							<a href="{{{ route('admin.music-plugin-banks.index') }}}">
								{{{ trans('admin.navbar.music_plugin_banks') }}}
							</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="{{{ route('admin.music-genres.index') }}}">
						{{{ trans('admin.navbar.music_genres') }}}
					</a>
				</li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="">
						{{{ trans('admin.navbar.resources') }}} <b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="{{{ route('admin.resource-locations.index') }}}">
								{{{ trans('admin.navbar.resource_locations') }}}
							</a>
						</li>
						<li>
							<a href="{{{ route('admin.resource-files.index') }}}">
								{{{ trans('admin.navbar.resource_files') }}}
							</a>
						</li>
					</ul>
				</li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="/">{{{ trans('admin.navbar.frontend') }}}</a>
				</li>
				<li class="dropdown">
					<a class="dropdown-toggle" href="" data-toggle="dropdown">
						{{{ $user->username }}} <b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="{{{ route('user.dashboard', ['username' => $user->username]) }}}">
								{{{ trans('navbar.dashboard') }}}
							</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="{{{ route('auth.log-out') }}}">{{{ trans('navbar.log_out') }}}</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>
