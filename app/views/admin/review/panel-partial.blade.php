<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{{{ trans('admin.review.panel_header.reviews_in_state') }}} <em><strong>{{{ trans('admin.review.states.' . $stateOfReviews) }}}</strong></em></h3>
	</div>
	<table class="table table-striped">
		<thead>
		<tr>
			<th>{{{ trans('common.table.headers.id') }}}</th>
			<th>{{{ trans('common.table.headers.type') }}}</th>
			<th>{{{ trans('common.table.headers.name') }}}</th>
			<th>{{{ trans('common.table.headers.submitter') }}}</th>
			<th>{{{ trans('common.table.headers.created_at') }}}</th>
			<th>{{{ trans('common.table.headers.reviewer') }}}</th>
			<th>{{{ trans('common.table.headers.accepted') }}}</th>
			<th>{{{ trans('common.table.headers.reviewed_at') }}}</th>
			<th>{{{ trans('common.table.headers.actions') }}}</th>
		</tr>
		</thead>
		<tbody>
		@foreach($reviews as $review)
		<tr>
			<td>{{{ $review->id }}}</td>
			<td>{{{ $review->reviewee_type }}}</td>
			<td>{{{ $review->reviewee->getNameForReview() }}}
			<td>{{{ $review->reviewee->getSubmitter()->email }}}</td>
			<td>{{{ $review->created_at }}}</td>
			<td>{{{ $review->reviewer->username }}}</td>
			<td>
				@if(!isset($review->result))
				-
				@elseif($review->result)
				<span class="glyphicon glyphicon-ok"></span>
				@else
				<span class="glyphicon glyphicon-remove"></span>
				@endif
			</td>
			<td>{{{ $review->reviewed_at or '-' }}}</td>
			<td>
				<a class="btn btn-primary btn-xs" href="{{ route('admin.reviews.show', [$review->id]) }}">
					{{{ trans('common.table.actions.details') }}}
				</a>
			</td>
		</tr>
		@endforeach
		</tbody>
	</table>
	<div class="panel-footer">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-6">
				{{ $reviews->links() }}
			</div>
		</div>
	</div>
</div>
