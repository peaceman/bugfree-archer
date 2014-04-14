@extends('layouts.admin.dashboard')
@section('dashboard.header')
<h1>
	{{{ trans('admin.user.page_header.users') }}}
	<small>
		{{{ trans('admin.user.page_header.amount_of_active_users') }}} {{{ $amountOfActiveUsers }}}
	</small>
</h1>
@stop
@section('content')
<div class="col-sm-12">
	<div class="row">
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{{{ trans('admin.user.page_header.latest_active') }}}</h3>
				</div>
				@include('admin.user.table-partial-small', ['users' => $latestActiveUsers])
			</div>
		</div>
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{{{ trans('admin.user.page_header.latest_unconfirmed') }}}</h3>
				</div>
				@include('admin.user.table-partial-small', ['users' => $latestUnconfirmedUsers])
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{{{ trans('admin.user.page_header.users') }}}</h3>
				</div>
				@include('admin.user.table-partial-big', ['users' => $users])
				<div class="panel-footer">
					<div class="row">
						<div class="col-sm-6 col-sm-offset-6">
							{{ $users->links() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
