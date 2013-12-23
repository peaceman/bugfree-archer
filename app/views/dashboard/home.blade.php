@extends('layouts.master')
@section('content')
<div class="page-header">
	<h1>{{ trans('dashboard.page_header.big') }}
		<small>{{ trans('dashboard.page_header.small') }}</small>
	</h1>
</div>
<style>
	.nav > li.disabled.nav-header > a {
		cursor: default;
		font-size: 12px;
		font-weight: bold;
		text-transform: uppercase;
		padding-left: 0px;
	}
</style>
<div class="row">
	<div class="col-md-2">
		<ul class="nav nav-pills nav-stacked">
			<li class="nav-header disabled">
				<a>{{ trans('dashboard.nav.header') }}</a>
			</li>
			<li>
				<a href="{{ route('dashboard') }}">{{ trans('dashboard.nav.home') }}</a>
			</li>
			<li>
				<a href="{{ route('dashboard.private-messages.index') }}">{{ trans('dashboard.nav.private_messages')
					}}</a>
			</li>

			<li class="nav-header disabled">
				<a>{{ trans('dashboard.nav.orders_header') }}</a>
			</li>
			<li>
				<a href="{{ route('dashboard.orders.index') }}">{{ trans('dashboard.nav.orders.history') }}</a>
			</li>
			<li>
				<a href="{{ route('dashboard.order-conflicts.index') }}">{{ trans('dashboard.nav.orders.conflicts')
					}}</a>
			</li>

			<li class="nav-header disabled">
				<a>{{ trans('dashboard.nav.items_header') }}</a>
			</li>
			<li>
				<a href="{{ route('dashboard.items.create') }}">{{ trans('dashboard.nav.items.upload') }}</a>
			</li>
			<li>
				<a href="{{ route('dashboard.items.index') }}">{{ trans('dashboard.nav.items.list') }}</a>
			</li>
			<li>
				<a href="{{ route('dashboard.customer-questions.index') }}">{{
					trans('dashboard.nav.items.customer_questions') }}</a>
			</li>
		</ul>
	</div>
	<div class="col-md-9">
	</div>
</div>
@stop
