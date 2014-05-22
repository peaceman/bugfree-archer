<table class="table table-condensed table-hover">
	<thead>
	<tr>
		<th>{{{ trans('common.table.headers.id') }}}</th>
		<th>{{{ trans('common.username') }}}</th>
		<th>{{{ trans('common.email') }}}</th>
		<th>{{{ trans('common.table.headers.state') }}}</th>
		<th>{{{ trans('common.table.headers.qualifies_as_vendor') }}}</th>
		<th>{{{ trans('common.table.headers.products') }}}</th>
		<th>{{{ trans('common.table.headers.sales') }}}</th>
		<th style="text-align: right;">{{{ trans('common.table.headers.revenue') }}}</th>
		<th>{{{ trans('common.table.headers.created_at') }}}</th>
		<th>{{{ trans('common.table.headers.actions') }}}</th>
	</tr>
	</thead>
	<tbody>
	@if(!$users->count())
	<tr>
		<td colspan="10" style="text-align: center">{{{ trans('common.table.no_entries') }}}</td>
	</tr>
	@endif
	@foreach($users as $user)
	<tr>
		<td>{{{ $user->id }}}</td>
		<td>{{{ $user->username }}}</td>
		<td>{{{ $user->email }}}</td>
		<td>{{{ trans('admin.user.states.' . $user->state) }}}</td>
		<td><span class="glyphicon glyphicon-{{{ $user->getQualifiesAsVendor() ? 'ok' : 'remove' }}}"></span></td>
		<td>{{{ $user->shopItems()->count() }}}</td>
		<td>{{{ $user->getAmountOfSales() }}}</td>
		<td style="text-align: right;">{{{ $user->getRevenue() }}} EUR</td>
		<td>{{{ $user->created_at }}}</td>
		<td>
			<a class="btn btn-default btn-xs" href="{{{ route('user.public-profile', [$user->username]) }}}">
				<span class="glyphicon glyphicon-globe" title="{{{ trans('admin.user.public_profile') }}}"></span>
			</a>
			<a class="btn btn-default btn-xs" href="{{{ route('admin.users.show', [$user->id]) }}}">
				<span class="glyphicon glyphicon-info-sign" title="{{{ trans('common.table.actions.details') }}}"></span>
			</a>
		</td>
	</tr>
	@endforeach
	</tbody>
</table>
