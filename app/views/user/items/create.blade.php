@extends('layouts.dashboard')
@section('dashboard.header')
<h1>{{ trans('user.items.create.dashboard_header') }}</h1>
@overwrite

@section('scripts.footer')
<script src="{{ asset('/assets/lodash.min.js') }}"></script>
<script src="{{ asset('/assets/angular.min.js') }}"></script>
<script src="//code.angularjs.org/1.2.12/angular-animate.js"></script>
<script src="{{ asset('/assets/angular-ui-router.min.js') }}"></script>
<script src="{{ asset('/assets/restangular.min.js') }}"></script>
<script src="{{ asset('/assets/ui-bootstrap-tpls-0.10.0.min.js') }}"></script>
<script src="{{ asset('/assets/ngStorage.js') }}"></script>
<script src="//rawgithub.com/mgcrea/angular-strap/master/dist/angular-strap.js"></script>
<script src="//rawgithub.com/mgcrea/angular-strap/master/dist/angular-strap.tpl.js"></script>
<script src="{{ asset('/assets/ng-flow.js') }}"></script>
<script>
angular.module('edmShopItems', ['ngAnimate', 'ui.router', 'ui.bootstrap.tpls', 'ui.bootstrap.collapse', 'ui.bootstrap.buttons', 'ui.bootstrap.progressbar', 'mgcrea.ngStrap', 'restangular', 'flow', 'ngStorage']);
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
