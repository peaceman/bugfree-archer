@extends('layouts.dashboard')
@section('content.dashboard')
<table class="table">
	<thead>
	<tr>
		<th>{{ trans('common.table.headers.id') }}</th>
		<th>{{ trans('common.table.headers.title') }}</th>
		<th>{{ trans('common.table.headers.state') }}</th>
		<th>{{ trans('common.table.headers.review_state') }}</th>
		<th>{{ trans('common.table.headers.category') }}</th>
		<th>{{ trans('common.table.headers.price') }}</th>
		<th>{{ trans('common.table.headers.actions') }}</th>
	</tr>
	</thead>
	<tbody>
	@foreach($shopItems as $shopItem)
	<tr>
		<td>{{ $shopItem->id }}</td>
		<td>{{ $shopItem->latestRevision()->title }}</td>
		<td>{{ $shopItem->state }}</td>
		<td>{{ $shopItem->latestRevision()->review->state }}</td>
		<td>{{ $shopItem->latestRevision()->shopCategory->name }}</td>
		<td style="text-align: right;">{{ $shopItem->latestRevision()->price }} EUR</td>
		<td>
			<a class="btn btn-primary btn-xs table-action-button"
			   href="{{ route('user.items.edit', ['username' => $user->username, 'item_id' => $shopItem->id]) }}">
				{{ trans('common.table.actions.edit') }}
			   </a>
			{{ Form::delete(route('user.items.delete', ['username' => $user->username, 'item_id' => $shopItem->id]), trans('common.table.actions.delete'), [], ['class' => 'btn btn-danger btn-xs']) }}
		</td>
	</tr>
	@endforeach
	</tbody>
</table>
{{ $shopItems->links() }}
@stop
