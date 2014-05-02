@extends('layouts.master')
@section('dashboard.header')
<h1>{{{ trans('dashboard.page_header.big') }}}
	<small>{{{ trans('dashboard.page_header.small') }}}</small>
</h1>
@stop
@section('content')
<div class="page-header">
	@yield('dashboard.header')
</div>
<div class="row">
	<div class="col-md-2">
		<ul class="nav nav-pills nav-stacked">
			<li class="nav-header disabled">
				<a>{{{ trans('dashboard.nav.header') }}}</a>
			</li>
			<li>
				<a href="{{{ route('user.dashboard', [$user->username]) }}}">{{{ trans('dashboard.nav.home') }}}</a>
			</li>
			<li>
				<a href="{{{ route('user.profile', [$user->username]) }}}">{{{ trans('dashboard.nav.profile') }}}</a>
			</li>
			<li>
				<a href="{{{ route('user.private-messages', [$user->username]) }}}">{{{ trans('dashboard.nav.private_messages') }}}</a>
			</li>
			<li>
				<a href="{{{ route('users.orders.index', [$user->username]) }}}">{{{ trans('dashboard.nav.my_orders') }}}</a>
			</li>

			<li class="nav-header disabled">
				<a>{{{ trans('dashboard.nav.sales_header') }}}</a>
			</li>
			<li>
				<a href="{{{ route('users.sales.index', $user->username) }}}">{{{ trans('dashboard.nav.sales.history') }}}</a>
			</li>
			<li>
				<a href="{{{ route('user.sales-conflicts', [$user->username]) }}}">{{{ trans('dashboard.nav.sales.conflicts') }}}</a>
			</li>

			<li class="nav-header disabled">
				<a>{{{ trans('dashboard.nav.items_header') }}}</a>
			</li>
			<li>
				<a href="{{{ route('user.items.create', [$user->username]) }}}">{{{ trans('dashboard.nav.items.upload') }}}</a>
			</li>
			<li>
				<a href="{{{ route('user.items', [$user->username]) }}}">{{{ trans('dashboard.nav.items.list') }}}</a>
			</li>
			<li>
				<a href="{{{ route('user.customer-questions', [$user->username]) }}}">{{{ trans('dashboard.nav.items.customer_questions') }}}</a>
			</li>
		</ul>
	</div>
	<div class="col-md-10">
		@yield('content.dashboard')
	</div>
</div>
@stop
