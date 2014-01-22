@extends('layouts.dashboard')
@section('dashboard.header')
<h1>{{ trans('user.items.create.dashboard_header') }}</h1>
@overwrite

@section('scripts.footer')
<script src="{{ asset('/assets/angular.min.js') }}"></script>
<script src="{{ asset('/assets/angular-ui-router.min.js') }}"></script>
<script src="{{ asset('/assets/shop-items.js') }}"></script>
@stop

@section('content.dashboard')
<div class="row" ng-app="edmShopItems">
	<div ui-view></div>
</div>
@stop
