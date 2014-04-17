@extends('layouts.admin.dashboard')
@section('dashboard.header')
<h1>
	{{{ trans('admin.music_program.page_header.music_programs') }}}
	<small>
		{{{ trans('common.total_amount') }}} {{{ $amountOfMusicPrograms }}}
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
		@foreach($musicPrograms as $mG)
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
	{{ $musicPrograms->links() }}
</div>
@stop
