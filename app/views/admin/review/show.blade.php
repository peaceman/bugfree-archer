@extends('layouts.admin.dashboard')
@section('dashboard.header')
<h1>
	{{{ trans('admin.review.page_header.details') }}}
	<small>
		{{{ trans('admin.review.page_header.about') }}}
		{{{ $review->reviewee_type }}}
		<em>{{{ $review->reviewee->getNameForReview() }}}</em>
		{{{ trans('admin.review.page_header.from') }}}
		<em>{{{ $review->reviewee->getSubmitter()->email }}}</em>
	</small>
</h1>
@stop
@section('content')
<div class="col-sm-12">
	@if($review->state === \Review::STATE_FINISHED)
		<div class="panel panel-{{{ $review->result ? 'success' : 'danger' }}}">
			<div class="panel-heading">
				<h3 class="panel-title">
					{{{ trans('admin.review.panel_header.result') }}}
					<em>{{{ trans('admin.review.' . ($review->result ? 'accepted' : 'rejected')) }}}</em>
				</h3>
			</div>
			<div class="panel-body">
				<h4>{{{ trans('admin.review.result_reasoning') }}}</h4>
				<blockquote>
					<p>{{{ $review->result_reasoning ?: 'N/A' }}}</p>
					<footer>{{{ $review->reviewer->username }}} / {{{ $review->reviewed_at }}}</footer>
				</blockquote>
			</div>
		</div>
	@endif
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">
				{{{ trans('admin.review.panel_header.overview') }}}
				@if($review->state === \Review::STATE_WAITING)
					{{ Form::open(['route' => ['admin.reviews.start', $review->id], 'class' => 'pull-right']) }}
					<button class="btn btn-primary btn-xs" type="submit">{{{ trans('admin.review.start') }}}</button>
					{{ Form::close() }}
				@else
					<span class="pull-right">
						{{{ trans('admin.review.panel_header.current_state') }}} <em>{{{ trans('admin.review.states.' . $review->state) }}}</em>
					</span>
				@endif
				<div class="clearfix"></div>
			</h3>
		</div>
		{{ Form::open(['route' => ['admin.reviews.update', $review->id], 'method' => 'put']) }}
			<div class="panel-body">
				@include('admin.review.details-partials.submitter', ['reviewee' => $review->reviewee])
				@include('admin.review.details-partials.' . snake_case($review->reviewee_type, '-'), ['reviewee' => $review->reviewee])
				@if($review->state === \Review::STATE_IN_PROGRESS)
					<fieldset>
						<legend>{{{ trans('common.feedback') }}}</legend>
						{{ Form::textareaField('result_reasoning', trans('admin.review.result_reasoning'), null, ['rows' => 5]) }}
					</fieldset>
				@endif
			</div>
			@if($review->state === \Review::STATE_IN_PROGRESS)
				<div class="panel-footer">
					<div class="pull-right">
						<input class="btn btn-danger" type="submit" name="reject"
							   value="{{{ trans('common.button.reject') }}}"/>
						<input class="btn btn-success" type="submit" name="accept"
							   value="{{{ trans('common.button.accept') }}}"/>
					</div>
					<div class="clearfix"></div>
				</div>
			@endif
		{{ Form::close() }}
	</div>
</div>
@stop
