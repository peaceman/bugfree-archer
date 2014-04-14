<fieldset>
	<legend>{{{ $review->reviewee_type }}}</legend>
	<dl class="dl-horizontal">
		<dt>{{{ trans('common.table.headers.category') }}}</dt>
		<dd>{{{ $reviewee->shopCategory->name }}}</dd>

		<dt>{{{ trans('common.table.headers.title') }}}</dt>
		<dd>{{{ $reviewee->title }}}</dd>

		<dt>{{{ trans('common.table.headers.price') }}}</dt>
		<dd>{{{ $reviewee->price }}} EUR</dd>
	</dl>
</fieldset>
@include('admin.review.details-partials.' . snake_case($reviewee->product_revision_type, '-'), ['productRevision' => $reviewee->product_revision])
