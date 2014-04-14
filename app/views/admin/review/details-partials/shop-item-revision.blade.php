<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			{{{ $review->reviewee_type }}}
		</h3>
	</div>
	<div class="panel-body">
		<dl class="dl-horizontal">
			<dt>{{{ trans('common.table.headers.category') }}}</dt>
			<dd>{{{ $reviewee->shopCategory->name }}}</dd>

			<dt>{{{ trans('common.table.headers.title') }}}</dt>
			<dd>{{{ $reviewee->title }}}</dd>

			<dt>{{{ trans('common.table.headers.price') }}}</dt>
			<dd>{{{ $reviewee->price }}} EUR</dd>
		</dl>
	</div>
</div>
@include('admin.review.details-partials.' . snake_case($reviewee->product_revision_type, '-'), ['productRevision' => $reviewee->product_revision])
