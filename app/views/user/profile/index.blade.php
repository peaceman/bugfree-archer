@extends('layouts.dashboard')
@section('dashboard.header')
<h1>Profile
	<small>of {{{ $user->username }}}</small>
</h1>
@overwrite
@section('content.dashboard')
<ul class="nav nav-tabs" style="margin-bottom: 15px;">
	<li class="active">
		<a href="#account" data-toggle="tab">{{{ trans('user.profile.nav.account') }}}</a>
	</li>
	<li>
		<a href="#basic" data-toggle="tab">{{{ trans('user.profile.nav.basic') }}}</a>
	</li>
	<li>
		<a href="#address" data-toggle="tab">{{{ trans('user.profile.nav.address') }}}</a>
	</li>
	<li>
		<a href="#payout-detail" data-toggle="tab">{{{ trans('user.profile.nav.payout_detail') }}}</a>
	</li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="account">
		@include('user.profile.tab.account')
	</div>
	<div class="tab-pane" id="basic">
		@include('user.profile.tab.basic')
	</div>
	<div class="tab-pane" id="address">
		@include('user.profile.tab.address')
	</div>
	<div class="tab-pane" id="payout-detail">
		@include('user.profile.tab.payout-detail')
	</div>
</div>
@stop
