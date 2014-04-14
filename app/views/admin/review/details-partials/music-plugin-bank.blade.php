<fieldset>
	<legend>{{{ $review->reviewee_type }}}</legend>
	<dl class="dl-horizontal">
		<dt>{{{ trans('common.table.headers.name') }}}</dt>
		<dd>{{{ $reviewee->name }}}</dd>

		<dt>{{{ trans('common.table.headers.music_plugin') }}}</dt>
		<dd>{{{ $reviewee->musicPlugin->name }}}</dd>

		<dt>{{{ trans('common.table.headers.created_at') }}}</dt>
		<dd>{{{ $reviewee->created_at }}}</dd>
	</dl>
</fieldset>
