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
@include('admin.review.details-partials.' . snake_case($review->reviewee_type, '-'), ['reviewee' => $review->reviewee])
</div>
@stop
