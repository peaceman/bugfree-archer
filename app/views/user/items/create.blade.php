@extends('layouts.dashboard')
@section('dashboard.header')
<h1>{{ trans('user.items.create.dashboard_header') }}</h1>
@overwrite

@section('scripts.footer')
<script src="{{ asset('/assets/lodash.min.js') }}"></script>
<script src="{{ asset('/assets/angular.min.js') }}"></script>
<script src="{{ asset('/assets/angular-ui-router.min.js') }}"></script>
<script src="{{ asset('/assets/restangular.min.js') }}"></script>
<script>
angular.module('edmShopItems', ['ui.router', 'restangular']);
</script>
<script src="{{ asset('/assets/app/common/utils.js') }}"></script>
<script src="{{ asset('/assets/app/common/base-service.js') }}"></script>
<script src="{{ asset('/assets/app/common/directives/field-group.js') }}"></script>
<script src="{{ asset('/assets/app/common/directives/selectize.js') }}"></script>
<script src="{{ asset('/assets/app/common/directives/server-side-validation.js') }}"></script>
<script src="{{ asset('/assets/app/common/directives/validation-message.js') }}"></script>
<script src="{{ asset('/assets/app/item-creation/service.js') }}"></script>
<script src="{{ asset('/assets/app/item-creation/steps/model.js') }}"></script>
<script src="{{ asset('/assets/app/item-creation/steps/controllers.js') }}"></script>
<script src="{{ asset('/assets/app/shop-categories/model.js') }}"></script>
<script src="{{ asset('/assets/app/music-genres/model.js') }}"></script>
<script src="{{ asset('/assets/app/config.js') }}"></script>
@stop

@section('content.dashboard')
<div class="row" ng-app="edmShopItems">
	<div class="col-sm-8" ui-view></div>
	<div class="col-sm-4" ui-view="progress-sidebar"></div>
</div>
@stop
