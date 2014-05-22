@extends('layouts.admin.dashboard')
@section('dashboard.header')
<h1>
	{{{ trans('admin.music_plugin.page_header.music_plugins') }}}
	<small>
		{{{ trans('common.total_amount') }}} {{{ $amountOfMusicPlugins }}}
	</small>
</h1>
@stop
@section('content')
<div class="col-sm-12">
	<table class="table table-condensed table-hover">
		<thead>
		<tr>
			<th>{{{ trans('common.table.headers.id') }}}</th>
			<th>{{{ trans('common.table.headers.name') }}}</th>
			<th>{{{ trans('common.table.headers.submitter') }}}</th>
			<th>{{{ trans('common.table.headers.created_at') }}}</th>
			<th>{{{ trans('common.table.headers.updated_at') }}}</th>
		</tr>
		</thead>
		<tbody>
		@if(!$musicPlugins->count())
		<tr>
			<td colspan="5" style="text-align: center">{{{ trans('common.table.no_entries') }}}</td>
		</tr>
		@endif
		@foreach($musicPlugins as $mG)
		<tr>
			<td>{{{ $mG->id }}}</td>
			<td>{{{ $mG->name }}}</td>
			<td>{{{ $mG->getSubmitter() ? $mG->getSubmitter()->email : '-' }}}</td>
			<td>{{{ $mG->created_at }}}</td>
			<td>{{{ $mG->updated_at }}}</td>
		</tr>
		@endforeach
		</tbody>
	</table>
	{{ $musicPlugins->links() }}
</div>
@stop
