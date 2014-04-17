@extends('layouts.admin.dashboard')
@section('dashboard.header')
<h1>
	{{{ trans('admin.shop_category.page_header.shop_categories') }}}
</h1>
@stop
@section('content')
<div class="col-sm-12">
	<table class="table table-condensed table-hover">
		<thead>
		<tr>
			<th>{{{ trans('common.table.headers.id') }}}</th>
			<th>{{{ trans('common.table.headers.name') }}}</th>
			<th>{{{ trans('common.table.headers.parent') }}}</th>
			<th>{{{ trans('common.table.headers.created_at') }}}</th>
			<th>{{{ trans('common.table.headers.updated_at') }}}</th>
		</tr>
		</thead>
		<tbody>
		@foreach($shopCategories as $sC)
		<tr>
			<td>{{{ $sC->id }}}</td>
			<td>{{{ $sC->name }}}</td>
			<td>{{{ $sC->parent ? $sC->parent->name : '-' }}}</td>
			<td>{{{ $sC->created_at }}}</td>
			<td>{{{ $sC->updated_at }}}</td>
		</tr>
		@endforeach
		</tbody>
	</table>
</div>
@stop
