@extends('layouts.dashboard')
@section('dashboard.header')
<h1>{{ trans('user.sales.index.dashboard_header') }}</h1>
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
	</tr>
	</thead>
	<tbody>
	@foreach($sales as $sale)
	<tr>
		<td>{{{ $sale->id }}}</td>
		<td>{{{ $sale->shopItemRevision->title }}}</td>
		<td class="price">{{{ $sale->shopItemRevision->price }}} EUR</td>
		<td>{{{ $sale->buyer->username }}}</td>
		<td>{{{ $sale->payment_state }}}</td>
		<td>{{{ $sale->created_at }}}</td>
		<td>
		</td>
	</tr>
	@endforeach
	</tbody>
</table>
{{ $sales->links() }}
@stop
