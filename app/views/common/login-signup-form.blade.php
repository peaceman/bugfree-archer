@extends('layouts.master')
@section('content')
<div class="page-header">
	<h1>Log in & Sign up
		<small>log into or create an account</small>
	</h1>
</div>
<div class="row">
	<!-- log in -->
	<div class="col-md-6">
		<form class="form-horizontal" action="{{{ route('auth.perform.log-in') }}}" method="POST">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Already a member? Log in:</h2>
				</div>
				<div class="panel-body">
					@if ($errors->has('login'))
					<div class="alert alert-warning">
						{{ $errors->first('login') }}
					</div>
					@endif
					<div class="form-group {{ $errors->has('login.username') ? 'has-error' : '' }}">
						{{ Form::label('login[username]', 'Username', ['class' => 'col-md-2 control-label']) }}

						<div class="col-md-10">
							{{ Form::text('login[username]', null, ['class' => 'form-control']) }}
							@if ($errors->has('login[username]'))
							<span class="help-block">
								{{ $errors->first('login[username]', '<span class="help-block">:message</span>') }}
							</span>
							@endif
						</div>
					</div>
					<div class="form-group {{ $errors->has('login[password]') ? 'has-error' : '' }}">
						{{ Form::label('login[password]', 'Password', ['class' => 'col-md-2 control-label']) }}

						<div class="col-md-10">
							{{ Form::password('login[password]', ['class' => 'form-control']) }}
							@if ($errors->has('login[password]'))
							<div class="help-block">
								{{ $errors->first('login[password]', '<span class="help-block">:message</span>') }}
							</div>
							@endif
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<div class="checkbox">
								<label>
									{{ Form::checkbox('login[remember]', '1') }}
									Remember Me
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<button class="btn btn-default btn-block btn-primary">Log in</button>
				</div>
			</div>
		</form>
	</div>
	<!-- sign up -->
	<div class="col-md-6">
		<form action="{{ route('user.perform.sign-up') }}" method="POST">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Create an account. It's free!</h2>
				</div>
				<div class="panel-body">
					<div class="form-group {{ $errors->has('signup[real_name]') ? 'has-error' : '' }}">
						{{ Form::label('signup[real_name]', 'Real name', ['class' => 'control-label']) }}
						{{ Form::text('signup[real_name]', null, ['class' => 'form-control']) }}
						@if ($errors->has('signup[real_name]'))
						{{ $errors->first('signup[real_name]', '<span class="help-block">:message</span>') }}
						@endif
					</div>

					<div class="form-group {{ $errors->has('signup[username]') ? 'has-error' : '' }}">
						{{ Form::label('signup[username]', 'Username', ['class' => 'control-label']) }}
						{{ Form::text('signup[username]', null, ['class' => 'form-control']) }}
						@if ($errors->has('signup[username]'))
						{{ $errors->first('signup[username]', '<span class="help-block">:message</span>') }}
						@endif
					</div>

					<div class="form-group {{ $errors->has('signup[email]') ? 'has-error' : '' }}">
						{{ Form::label('signup[email]', 'Email', ['class' => 'control-label']) }}
						{{ Form::text('signup[email]', null, ['class' => 'form-control']) }}
						@if ($errors->has('signup[email]'))
						{{ $errors->first('signup[email]', '<span class="help-block">:message</span>') }}
						@endif
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group {{ $errors->has('signup[password]') ? 'has-error' : '' }}">
								{{ Form::label('signup[password]', 'Password', ['class' => 'control-label']) }}
								{{ Form::password('signup[password]', ['class' => 'form-control']) }}
								@if ($errors->has('signup[password]'))
								{{ $errors->first('signup[password]', '<span class="help-block">:message</span>') }}
								@endif
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group {{ $errors->has('signup[password_confirmation]') ? 'has-error' : '' }}">
								{{ Form::label('signup[password_confirmation]', 'Password (again)', ['class' =>
								'control-label']) }}
								{{ Form::password('signup[password_confirmation]', ['class' => 'form-control']) }}
								@if ($errors->has('signup[password_confirmation]'))
								{{ $errors->first('signup[password_confirmation]',
								'<span class="help-block">:message</span>') }}
								@endif
							</div>
						</div>
					</div>
				</div>

				<div class="panel-footer">
					<button type="submit"
							class="btn btn-default btn-block btn-success">{{{ trans('common.sign_up') }}}
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
<a href="{{{ route('user.resend-confirmation-email') }}}" class="btn btn-link">{{{
	trans('common.resend_confirmation_email') }}}</a>
@stop
