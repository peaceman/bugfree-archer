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
		{{ Form::model($user, ['route' => ['user.profile.perform.account', $user->username], 'class' =>
		'form-horizontal']) }}
		<fieldset>
			<legend>{{ trans('common.general') }}</legend>
			<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
				{{ Form::label('username', trans('common.username'), ['class' => 'control-label col-md-2']) }}
				<div class="col-md-5">
					<p class="form-control-static">{{ $user->username }}</p>
				</div>
			</div>

			<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
				{{ Form::label('email', trans('common.email'), ['class' => 'control-label col-md-2']) }}
				<div class="col-md-5">
					{{ Form::email('email', null, ['class' => 'form-control']) }}
					@if ($errors->has('email'))
					{{ $errors->first('email') }}
					@endif
				</div>
			</div>

			<div class="form-group {{ $errors->has('real_name') ? 'has-error' : '' }}">
				{{ Form::label('email', trans('common.real_name'), ['class' => 'control-label col-md-2']) }}
				<div class="col-md-5">
					{{ Form::text('real_name', null, ['class' => 'form-control']) }}
					@if ($errors->has('real_name'))
					{{ $errors->first('real_name') }}
					@endif
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>{{ trans('common.password') }}</legend>
			<div class="form-group">
				{{ Form::label('current_password', trans('common.current_password'), ['class' => 'control-label
				col-md-2']) }}
				<div class="col-md-5">
					{{ Form::password('current_password', ['class' => 'form-control', 'autocomplete' => 'off']) }}
						<span class="help-block">
							leave empty if you don't want to change your password
						</span>
				</div>
			</div>

			<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
				{{ Form::label('password', trans('common.password'), ['class' => 'control-label col-md-2']) }}
				<div class="col-md-5">
					{{ Form::password('password', ['class' => 'form-control', 'autocomplete' => 'off']) }}
					@if ($errors->has('password'))
					{{ $errors->first('password') }}
					@endif
				</div>
			</div>

			<div class="form-group">
				{{ Form::label('password_confirmation', trans('common.password_again'), ['class' => 'control-label
				col-md-2']) }}
				<div class="col-md-5">
					{{ Form::password('password_confirmation', ['class' => 'form-control']) }}
				</div>
			</div>
		</fieldset>


		<div class="form-group">
			<div class="col-md-offset-2 col-md-2">
				<button class="btn btn-default ">{{ trans('common.submit') }}</button>
			</div>
		</div>
		{{ Form::close() }}
	</div>
	<div class="tab-pane" id="address">
		address
	</div>
</div>
@stop
