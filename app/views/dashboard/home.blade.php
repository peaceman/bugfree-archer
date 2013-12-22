@extends('layouts.master')
@section('content')
<div class="page-header">
	<h1>{{ trans('user.settings.page_header') }} <small>{{ trans('user.settings.page_header_small') }}</small></h1>
</div>
<style>
	.nav > li.disabled.nav-header > a {
		cursor: default;
		font-size: 12px;
		font-weight: bold;
		text-transform: uppercase;
	}
</style>
<div class="row">
	<div class="col-md-3">
		<ul class="nav nav-pills nav-stacked">
			<li class="nav-header disabled">
				<a>{{ trans('dashboard.nav.header') }}</a>
			</li>
			<li>
				<a href="{{ route('user.dashboard') }}">dashboard.nav.home</a>
			</li>
			<li>
				<a href="{{ route('dashboard.private-messages') }}">dashboard.nav.private_messages</a>
			</li>

			<li class="nav-header disabled">
				<a>{{ trans('dashboard.nav.orders_header') }}</a>
			</li>
			<li>
				<a href="{{ route('dashboard.order.history') }}">{{ trans('dashboard.nav.order.history') }}</a>
			</li>
			<li>
				<a href="{{ route('dashboard.order.conflicts') }}">{{ trans('dashboard.nav.order.conflicts') }}</a>
			</li>

			<li class="nav-header disabled">
				<a>{{ trans('dashboard.nav.items_header') }}</a>
			</li>
			<li>
				<a href="{{ route('dashboard.item-upload') }}">{{ trans('dashboard.nav.items.upload') }}</a>
			</li>
			<li>
				<a href="{{ route('dashboard.items') }}">{{ trans('dashboard.nav.items.list') }}</a>
			</li>
			<li>
				<a href="{{ route('dashboard.customer-questions') }}">{{ trans('dashboard.nav.items.customer_questions') }}</a>
			</li>
		</ul>
	</div>
	<div class="col-md-9">
	</div>
</div>
@stop
