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
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">
				{{{ trans('admin.review.panel_header.overview') }}}
			</h3>
		</div>
		{{ Form::open(['route' => ['admin.reviews.update', $review->id]]) }}
			<div class="panel-body">
				@include('admin.review.details-partials.' . snake_case($review->reviewee_type, '-'), ['reviewee' => $review->reviewee])
				<fieldset>
					<legend>{{{ trans('common.feedback') }}}</legend>
					<div class="form-group">
						<label for="result-reasoning-input">{{{ trans('admin.review.result_reasoning') }}}</label>
						<textarea id="result-reasoning-input" name="result_reasoning" class="form-control" cols="30" rows="10"></textarea>
					</div>
				</fieldset>
			</div>
			<div class="panel-footer">
				<div class="pull-right">
					<input class="btn btn-danger" type="submit" name="reject"
						   value="{{{ trans('common.button.reject') }}}"/>
					<input class="btn btn-success" type="submit" name="accept"
						   value="{{{ trans('common.button.accept') }}}"/>
				</div>
				<div class="clearfix"></div>
			</div>
		{{ Form::close() }}
	</div>
</div>
@stop
