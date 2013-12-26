@extends('layouts.dashboard')
@section('dashboard.header')
<h1>Profile
	<small>of {{ $user->username }}</small>
</h1>
@overwrite
@section('content.dashboard')
<ul class="nav nav-tabs" style="margin-bottom: 15px;">
	<li class="active">
		<a href="#account" data-toggle="tab">{{ trans('user.profile.nav.account') }}</a>
	</li>
	<li>
		<a href="#basic" data-toggle="tab">{{ trans('user.profile.nav.basic') }}</a>
	</li>
	<li>
		<a href="#address" data-toggle="tab">{{ trans('user.profile.nav.address') }}</a>
	</li>
	<li>
		<a href="#payment" data-toggle="tab">{{ trans('user.profile.nav.payment') }}</a>
	</li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="account">
		@include('user.profile.tab.account')
	</div>
	<div class="tab-pane" id="basic">basic</div>
	<div class="tab-pane" id="address">
		address
	</div>
</div>
@stop
