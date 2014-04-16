@extends('layouts.admin.dashboard')
@section('dashboard.header')
<h1>
	{{{ trans('admin.resource_location.page_header.resource_locations') }}}
	<small>
		{{{ trans('admin.resource_location.page_header.description') }}}
	</small>
</h1>
@stop
@section('content')
<div class="col-sm-12">
	<table class="table table-condensed table-hover">
		<thead>
		<tr>
			<th>{{{ trans('common.table.headers.id') }}}</th>
			<th>{{{ trans('common.table.headers.type') }}}</th>
			<th>{{{ trans('common.table.headers.state') }}}</th>
			<th>{{{ trans('common.table.headers.is_backup') }}}</th>
			<th>{{{ trans('common.files') }}}</th>
			<th>{{{ trans('common.used_space') }}}</th>
			<th>{{{ trans('common.table.headers.upload_order') }}}</th>
			<th>{{{ trans('common.table.headers.download_order') }}}</th>
			<th>{{{ trans('common.table.headers.updated_at') }}}</th>
			<th>{{{ trans('common.table.headers.actions') }}}</th>
		</tr>
		</thead>
		<tbody>
		@foreach($resourceLocations as $resourceLocation)
		<tr>
			<td>{{{ $resourceLocation->id }}}</td>
			<td>{{{ trans('admin.resource_location.types.' . $resourceLocation->type) }}}</td>
			<td>{{{ trans('admin.resource_location.states.' . $resourceLocation->state) }}}</td>
			<td><span class="glyphicon glyphicon-{{{ $resourceLocation->is_backup ? 'ok' : 'remove' }}}"></span></td>
			<td>{{{ $resourceLocation->getAmountOfFiles() }}}</td>
			<td>{{{ nice_bytesize($resourceLocation->getSpaceUsage()) }}}</td>
			<td>{{{ $resourceLocation->upload_order }}}</td>
			<td>{{{ $resourceLocation->download_order }}}</td>
			<td>{{{ $resourceLocation->updated_at }}}</td>
			<td>
				<a class="btn btn-default btn-xs" href="{{{ route('admin.resource-locations.show', [$resourceLocation->id]) }}}">
					{{{ trans('common.table.actions.details') }}}
				</a>
				<a class="btn btn-default btn-xs" href="{{{ route('admin.resource-locations.edit', [$resourceLocation->id]) }}}">
					{{{ trans('common.table.actions.edit') }}}
				</a>
			</td>
		</tr>
		@endforeach
		</tbody>
	</table>
</div>
@stop
