@extends('layouts.admin.dashboard')
@section('dashboard.header')
<h1>
	{{{ trans('admin.resource_location.page_header.details') }}}
	<small>
		{{{ trans('admin.resource_location.page_header.of_type') }}}
		<em>{{{ trans('admin.resource_location.types.' . $rL->type) }}}</em>
	</small>
</h1>
@stop
@section('content')
<script type="text/javascript">
	$(function () {
		var form = $('form#state-selection');
		$('a', form).on('click', function (e) {
			e.preventDefault();
			var stateInput = $('<input>', {
				type: 'hidden',
				name: 'state',
				value: $(this).attr('data-state')
			});
			form.append(stateInput);
			form.submit();
		});
	});
</script>
<div class="col-sm-12">
	<div class="row">
		<div class="col-sm-6">
			<fieldset>
				<legend>{{{ trans('common.overview') }}}</legend>
				<dl class="dl-horizontal">
					<dt>{{{ trans('common.table.headers.id') }}}</dt>
					<dd>{{{ $rL->id }}}</dd>

					<dt>{{{ trans('common.table.headers.type') }}}</dt>
					<dd>{{{ trans('admin.resource_location.types.' . $rL->type) }}}</dd>

					<dt>{{{ trans('common.table.headers.state') }}}</dt>
					<dd>
						{{{ trans('admin.resource_location.states.' . $rL->state) }}}
						{{ Form::open(['id' => 'state-selection', 'method' => 'put', 'route' => ['admin.resource-locations.update', $rL->id]]) }}
						<div class="btn-group btn-group-xs">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								{{{ trans('common.change_state') }}} <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								@foreach($rL->getPossibleStates() as $state)
								<li>
									<a href="" data-state="{{{ $state }}}" >{{{ $state }}}</a>
								</li>
								@endforeach
							</ul>
						</div>
						{{ Form::close() }}
					</dd>

					<dt>{{{ trans('common.table.headers.is_backup') }}}</dt>
					<dd><span class="glyphicon glyphicon-{{{ $rL->is_backup ? 'ok' : 'remove' }}}"></span></dd>

					<dt>{{{ trans('common.files') }}}</dt>
					<dd>{{{ $rL->getAmountOfFiles() }}}</dd>

					<dt>{{{ trans('common.used_space') }}}</dt>
					<dd>{{{ nice_bytesize($rL->getSpaceUsage()) }}}</dd>

					<dt>{{{ trans('common.table.headers.upload_order') }}}</dt>
					<dd>{{{ $rL->upload_order }}}</dd>

					<dt>{{{ trans('common.table.headers.download_order') }}}</dt>
					<dd>{{{ $rL->download_order }}}</dd>

					<dt>{{{ trans('common.table.headers.created_at') }}}</dt>
					<dd>{{{ $rL->created_at }}}</dd>

					<dt>{{{ trans('common.table.headers.updated_at') }}}</dt>
					<dd>{{{ $rL->updated_at }}}</dd>
				</dl>
			</fieldset>
		</div>
		<div class="col-sm-6">
			<fieldset>
				<legend>{{{ trans('common.settings') }}}</legend>
			</fieldset>
			@if($rL->settings)
			<pre>{{{ json_encode(json_decode($rL->settings), JSON_PRETTY_PRINT) }}}</pre>
			@else
			N/A
			@endif
		</div>
	</div>
	<fieldset>
		<legend>{{{ trans('common.files') }}}</legend>
		<table class="table table-condensed table-hover">
			<thead>
			<tr>
				<th>{{{ trans('common.table.headers.id') }}}</th>
				<th>{{{ trans('common.table.headers.identifier') }}}</th>
				<th>{{{ trans('common.table.headers.state') }}}</th>
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
			@foreach($resourceFileLocations as $rFL)
			<tr>
				<td>{{{ $rFL->resourceFile->id }}}</td>
				<td>{{{ $rFL->identifier }}}</td>
				<td>{{{ $rFL->state }}}</td>
				<td><span class="glyphicon glyphicon-{{{ $rFL->protected ? 'ok' : 'remove' }}}"></span></td>
				<td>
					<a href="{{{ $rFL->getUrl() }}}"><span class="glyphicon glyphicon-download-alt"></span></a>
				</td>
				<td>{{{ $rFL->resourceFile->original_name }}}</td>
				<td>{{{ $rFL->resourceFile->mime_type }}}</td>
				<td>{{{ nice_bytesize($rFL->resourceFile->size) }}}</td>
				<td>{{{ $rFL->created_at }}}</td>
				<td>{{{ $rFL->updated_at }}}</td>
			</tr>
			@endforeach
			</tbody>
		</table>
		{{ $resourceFileLocations->links() }}
	</fieldset>
</div>
@stop
