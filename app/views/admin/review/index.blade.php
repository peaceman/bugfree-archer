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
		<div class="col-sm-6">
			@include('admin.review.panel-partial', ['reviews' => $reviewsInInProgressState, 'stateOfReviews' => Review::STATE_IN_PROGRESS])
		</div>
		<div class="col-sm-6">
			@include('admin.review.panel-partial', ['reviews' => $reviewsInFinishedState, 'stateOfReviews' => Review::STATE_FINISHED])
		</div>
	</div>
</div>
@stop
