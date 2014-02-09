@extends('layouts.dashboard')
@section('dashboard.header')
<h1>{{ trans('user.items.create.dashboard_header') }}</h1>
@overwrite

@section('scripts.footer')
<script src="{{ asset('/assets/lodash.min.js') }}"></script>
<script src="{{ asset('/assets/angular.min.js') }}"></script>
<script src="{{ asset('/assets/angular-ui-router.min.js') }}"></script>
<script src="{{ asset('/assets/restangular.min.js') }}"></script>
<script src="{{ asset('/assets/ui-bootstrap-tpls-0.10.0.min.js') }}"></script>
<script src="//rawgithub.com/mgcrea/angular-strap/master/dist/angular-strap.min.js"></script>
<script src="//rawgithub.com/mgcrea/angular-strap/master/dist/angular-strap.tpl.min.js"></script>
<script src="{{ asset('/assets/ng-flow.js') }}"></script>
<script>
angular.module('edmShopItems', ['ui.router', 'ui.bootstrap',  'restangular', 'flow']);
</script>
<script src="{{ asset('/assets/app/common/utils.js') }}"></script>
<script src="{{ asset('/assets/app/common/base-service.js') }}"></script>
<script src="{{ asset('/assets/app/common/directives/field-group.js') }}"></script>
<script src="{{ asset('/assets/app/common/directives/selectize.js') }}"></script>
<script src="{{ asset('/assets/app/common/directives/server-side-validation.js') }}"></script>
<script src="{{ asset('/assets/app/common/directives/validation-message.js') }}"></script>
<script src="{{ asset('/assets/app/common/directives/files-table.js') }}"></script>
<script src="{{ asset('/assets/app/common/filters/bytes.js') }}"></script>
<script src="{{ asset('/assets/app/item-creation/service.js') }}"></script>
<script src="{{ asset('/assets/app/item-creation/steps/model.js') }}"></script>
<script src="{{ asset('/assets/app/item-creation/steps/controllers.js') }}"></script>
<script src="{{ asset('/assets/app/shop-categories/model.js') }}"></script>
<script src="{{ asset('/assets/app/music-genres/model.js') }}"></script>
<script src="{{ asset('/assets/app/music-plugins/model.js') }}"></script>
<script src="{{ asset('/assets/app/music-programs/model.js') }}"></script>
<script src="{{ asset('/assets/app/resource-files/model.js') }}"></script>
<script src="{{ asset('/assets/app/config.js') }}"></script>
@stop

@section('content.dashboard')
<div class="row" ng-app="edmShopItems">
	<div class="col-sm-8" ui-view></div>
	<div class="col-sm-4" ui-view="progress-sidebar"></div>
</div>
@stop
