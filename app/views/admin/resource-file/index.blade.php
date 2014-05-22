@extends('layouts.admin.dashboard')
@section('dashboard.header')
<h1>
	{{{ trans('admin.resource_file.page_header.resource_files') }}}
	<small>
		{{{ trans('admin.resource_file.page_header.total_amount') }}} {{{ $amountOfResourceFiles }}}
	</small>
</h1>
@stop
@section('content')
<div class="col-sm-12">
	<table class="table table-condensed table-hover">
		<thead>
		<tr>
			<th>{{{ trans('common.table.headers.id') }}}</th>
			<th>
				<span class="glyphicon glyphicon-lock" title="{{{ trans('common.table.headers.is_protected') }}}"></span>
			</th>
			<th>
				<span class="glyphicon glyphicon-download-alt" title="{{{ trans('common.table.headers.download') }}}"></span>
			</th>
			<th>{{{ trans('common.table.headers.original_filename') }}}</th>
			<th>{{{ trans('common.table.headers.mime_type') }}}</th>
			<th>{{{ trans('common.table.headers.file_size') }}}</th>
			<th>{{{ trans('common.table.headers.created_at') }}}</th>
			<th>{{{ trans('common.table.headers.updated_at') }}}</th>
		</tr>
		</thead>
		<tbody>
		@if(!$resourceFiles->count())
		<tr>
			<td colspan="8" style="text-align: center">{{{ trans('common.table.no_entries') }}}</td>
		</tr>
		@endif
		@foreach($resourceFiles as $rF)
		<tr>
			<td>{{{ $rF->id }}}</td>
			<td><span class="glyphicon glyphicon-{{{ $rF->protected ? 'ok' : 'remove' }}}"></span></td>
			<td>
				<a href="{{{ $rF->getUrl() }}}"><span class="glyphicon glyphicon-download-alt"></span></a>
			</td>
			<td>{{{ $rF->original_name }}}</td>
			<td>{{{ $rF->mime_type }}}</td>
			<td>{{{ nice_bytesize($rF->size) }}}</td>
			<td>{{{ $rF->created_at }}}</td>
			<td>{{{ $rF->updated_at }}}</td>
		</tr>
		@endforeach
		</tbody>
	</table>
	{{ $resourceFiles->links() }}
</div>
@stop
