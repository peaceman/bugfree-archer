@extends('layouts.admin.dashboard')
@section('dashboard.header')
<h1>
	{{{ trans('admin.shop_item.page_header.shop_items') }}}
	<small>
		{{{ trans('common.total_amount') }}} {{{ $amountOfShopItems }}}
	</small>
</h1>
@stop
@section('content')
<div class="col-sm-12">
	<table class="table table-condensed table-hover">
		<thead>
		<tr>
			<th>{{{ trans('common.table.headers.id') }}}</th>
			<th>{{{ trans('common.table.headers.owner') }}}</th>
			<th>{{{ trans('common.table.headers.title') }}}</th>
			<th>{{{ trans('common.table.headers.state') }}}</th>
			<th>{{{ trans('common.table.headers.review_state') }}}</th>
			<th>{{{ trans('common.table.headers.category') }}}</th>
			<th style="text-align: right;">{{{ trans('common.table.headers.price') }}}</th>
			<th>{{{ trans('common.table.headers.updated_at') }}}</th>
			<th>{{{ trans('common.table.headers.actions') }}}</th>
		</tr>
		</thead>
		<tbody>
		@if(!$shopItems->count())
		<tr>
			<td colspan="9" style="text-align: center">{{{ trans('common.table.no_entries') }}}</td>
		</tr>
		@endif
		@foreach($shopItems as $sI)
		<tr>
			<td>{{{ $sI->id }}}</td>
			<td>{{{ $sI->owner->email }}}</td>
			<td>{{{ $sI->latestRevision()->title }}}</td>
			<td>{{{ $sI->state }}}</td>
			<td>{{{ trans('admin.review.states.' . $sI->latestRevision()->review->state) }}}</td>
			<td>{{{ $sI->latestRevision()->shopCategory->name }}}</td>
			<td style="text-align: right;">{{{ $sI->latestRevision()->price }}} EUR</td>
			<td>{{{ $sI->updated_at }}}</td>
			<td>
				<a class="btn btn-primary btn-xs" href="{{{ route('admin.shop-items.show', [$sI->id]) }}}">
					{{{ trans('common.table.actions.details') }}}
				</a>
			</td>
		</tr>
		@endforeach
		</tbody>
	</table>
	{{ $shopItems->links() }}
</div>
@stop
