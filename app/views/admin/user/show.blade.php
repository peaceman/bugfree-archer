@extends('layouts.admin.dashboard')
@section('dashboard.header')
<h1>
	{{{ trans('admin.user.page_header.user') }}}
	<small>
		{{{ $user->username }}} (<a href="mailto:{{{ $user->email }}}">{{{ $user->email }}}</a>)
	</small>
</h1>
@stop
@section('content')
<div class="col-sm-3">
	<table class="table table-condensed sales-summary">
		<thead>
		<tr>
			<th colspan="2" class="h3">{{{ trans('admin.user.sales_summary') }}}</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<th class="text-muted">{{{ trans('admin.user.today_sold') }}}</th>
			<td>{{{ $user->getAmountOfSalesOfToday() }}}</td>
		</tr>
		<tr>
			<th class="text-muted">{{{ trans('admin.user.weekly_sales') }}}</th>
			<td>{{{ $user->getAmountOfSalesOfThisWeek() }}}</td>
		</tr>
		<tr>
			<th class="text-muted">{{{ trans('admin.user.total_sold') }}}</th>
			<td>{{{ $user->getAmountOfSales() }}}</td>
		</tr>
		<tr>
			<th class="text-muted">{{{ trans('common.table.headers.revenue') }}}</th>
			<td>EUR {{{ $user->getRevenue() }}}</td>
		</tr>
		</tbody>
	</table>
</div>
<div class="col-sm-6">
	<h3 style="margin-top: 10px;">{{{ $user->real_name }}}</h3>
	<p>{{{ $user->getProfile()->about or 'N/A' }}}</p>
	<p>
		<a href="{{{ $user->getProfile()->website }}}">{{{ $user->getProfile()->website }}}</a>
	</p>
</div>
<div class="col-sm-3">
	@if ($user->getProfile()->hasAvatar())
	<img src="{{{ $user->getProfile()->avatar->getUrl() }}}" class="img-rounded" alt="" style="width: 100%;"/>
	@endif
</div>
@stop
