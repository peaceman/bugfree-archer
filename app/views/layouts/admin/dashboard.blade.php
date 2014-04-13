@extends('layouts.master')
@section('title')
EDM Admin
@stop
@section('dashboard.header')
<h1>{{ trans('admin.dashboard.page_header.big') }}
	<small>{{ trans('admin.dashboard.page_header.small') }}</small>
</h1>
@stop
@section('wrapped')
@include('common.admin.navbar')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="page-header">
				@yield('dashboard.header')
			</div>
		</div>
	</div>
	<div class="row">
		@yield('content')
	</div>
</div>
@stop
