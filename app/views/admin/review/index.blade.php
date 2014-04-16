@extends('layouts.admin.dashboard')
@section('dashboard.header')
<h1>
	{{{ trans('admin.review.page_header.reviews') }}}
	<small>
		{{{ trans('admin.review.page_header.amount_of_waiting_reviews') }}} {{{ $amountOfWaitingReviews }}}
	</small>
</h1>
@stop
@section('content')
<div class="col-sm-12">
	<div class="row">
		<div class="col-sm-12">
			@include('admin.review.panel-partial', ['reviews' => $reviewsInWaitingState, 'stateOfReviews' => Review::STATE_WAITING])
		</div>
	</div>
	<div class="row">
		<div class="col-sm-2">
			<ul class="nav nav-pills nav-stacked">
				<li class="active">
					<a href="#in-progress" data-toggle="tab">
						<span class="badge pull-right">{{{ $amountOfInProgressReviews }}}</span>
						{{{ trans('admin.review.states.' . Review::STATE_IN_PROGRESS) }}}
					</a>
				</li>
				<li>
					<a href="#finished" data-toggle="tab">
						<span class="badge pull-right">{{{ $amountOfFinishedReviews }}}</span>
						{{{ trans('admin.review.states.' . Review::STATE_FINISHED) }}}
					</a>
				</li>
			</ul>
		</div>
		<div class="col-sm-10">
			<div class="tab-content">
				<div class="tab-pane active" id="in-progress">
					@include('admin.review.panel-partial', ['reviews' => $reviewsInInProgressState, 'stateOfReviews' => Review::STATE_IN_PROGRESS])
				</div>
				<div class="tab-pane" id="finished">
					@include('admin.review.panel-partial', ['reviews' => $reviewsInFinishedState, 'stateOfReviews' => Review::STATE_FINISHED])
				</div>
			</div>
		</div>
	</div>
</div>
@stop
