<fieldset>
	<legend>{{{ trans('common.table.headers.submitter') }}}</legend>
	<dl class="dl-horizontal">
		<dt>{{{ trans('common.username') }}}</dt>
		<dd>{{{ $reviewee->getSubmitter()->username }}}</dd>

		<dt>{{{ trans('common.email') }}}</dt>
		<dd>{{{ $reviewee->getSubmitter()->email }}}</dd>

		<dt>{{{ trans('common.table.headers.created_at') }}}</dt>
		<dd>{{{ $reviewee->created_at }}}</dd>
	</dl>
</fieldset>
