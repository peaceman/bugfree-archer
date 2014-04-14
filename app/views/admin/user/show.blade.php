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
<div class="col-sm-12">
	<div class="row">
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

			@if($user->address)
			<address style="margin-top: 10px;">
				<strong>{{{ $user->real_name }}}</strong><br>
				{{{ $user->address->address_lines }}}<br>
				{{{ $user->address->postcode }}} {{{ $user->address->locality }}}<br>
				{{{ $user->address->country->name }}}
			</address>
			@endif
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<fieldset>
				<legend>{{{ trans('common.table.headers.products') }}}</legend>
				<table class="table table-condensed">
					<thead>
					<tr>
						<th>{{{ trans('common.table.headers.id') }}}</th>
						<th>{{{ trans('common.table.headers.title') }}}</th>
						<th>{{{ trans('common.table.headers.state') }}}</th>
						<th>{{{ trans('common.table.headers.review_state') }}}</th>
						<th>{{{ trans('common.table.headers.category') }}}</th>
						<th style="text-align: right;">{{{ trans('common.table.headers.price') }}}</th>
						<th>{{{ trans('common.table.headers.actions') }}}</th>
					</tr>
					</thead>
					<tbody>
					@foreach($shopItems as $shopItem)
					<tr>
						<td>{{{ $shopItem->id }}}</td>
						<td>{{{ $shopItem->latestRevision()->title }}}</td>
						<td>{{{ $shopItem->state }}}</td>
						<td>{{{ trans('admin.review.states.' . $shopItem->latestRevision()->review->state) }}}</td>
						<td>{{{ $shopItem->latestRevision()->shopCategory->name }}}</td>
						<td style="text-align: right;">{{{ $shopItem->latestRevision()->price }}} EUR</td>
						<td>
							<a class="btn btn-primary btn-xs table-action-button"
							   href="{{{ route('user.items.edit', ['username' => $user->username, 'item_id' => $shopItem->id]) }}}">
								{{{ trans('common.table.actions.edit') }}}
							</a>
							{{ Form::delete(route('user.items.delete', ['username' => $user->username, 'item_id' => $shopItem->id]), trans('common.table.actions.delete'), [], ['class' => 'btn btn-danger btn-xs']) }}
						</td>
					</tr>
					@endforeach
					</tbody>
				</table>
				{{ $shopItems->links() }}
			</fieldset>
		</div>
	</div>
</div>
@stop
