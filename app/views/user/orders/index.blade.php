@extends('layouts.dashboard')
@section('dashboard.header')
<h1>{{ trans('user.orders.index.dashboard_header') }}</h1>
@overwrite
@section('content.dashboard')
<table class="table">
	<thead>
	<tr>
		<th>{{{ trans('common.table.headers.id') }}}</th>
		<th>{{{ trans('common.table.headers.product') }}}</th>
		<th class="price">{{{ trans('common.table.headers.price') }}}</th>
		<th>{{{ trans('common.table.headers.buyer') }}}</th>
		<th>{{{ trans('common.table.headers.payment_state') }}}</th>
		<th>{{{ trans('common.table.headers.created_at') }}}</th>
		<th>{{{ trans('common.table.headers.actions') }}}</th>
	</tr>
	</thead>
	<tbody>
	@foreach($orders as $order)
	<tr>
		<td>{{{ $order->id }}}</td>
		<td>{{{ $order->shopItemRevision->title }}}</td>
		<td class="price">{{{ $order->shopItemRevision->price }}} EUR</td>
		<td>{{{ $order->buyer->username }}}</td>
		<td>{{{ $order->payment_state }}}</td>
		<td>{{{ $order->created_at }}}</td>
		<td>
			@if ($order->isProductDownloadable())
			{{ Form::post(route('user.orders.download', [$user->username, $order->id]), trans('common.table.actions.download'), [], ['class' => 'btn btn-default btn-xs']) }}
			@endif
		</td>
	</tr>
	@endforeach
	</tbody>
</table>
{{ $orders->links() }}
@stop
