<div class="row">
	<div class="col-md-8">
		{{ Form::model($user, ['route' => ['user.profile.perform.account', $user->username], 'class' =>
		'form-horizontal']) }}
		<fieldset>
			<legend>{{{ trans('common.general') }}}</legend>
			<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
				{{ Form::label('username', trans('common.username'), ['class' => 'control-label col-md-3']) }}
				<div class="col-md-9">
					<p class="form-control-static">{{{ $user->username }}}</p>
				</div>
			</div>

			<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
				{{ Form::label('email', trans('common.email'), ['class' => 'control-label col-md-3']) }}
				<div class="col-md-9">
					{{ Form::email('email', null, ['class' => 'form-control']) }}
					@if ($errors->has('email'))
					{{ $errors->first('email') }}
					@endif
				</div>
			</div>

			<div class="form-group {{ $errors->has('real_name') ? 'has-error' : '' }}">
				{{ Form::label('email', trans('common.real_name'), ['class' => 'control-label col-md-3']) }}
				<div class="col-md-9">
					{{ Form::text('real_name', null, ['class' => 'form-control']) }}
					@if ($errors->has('real_name'))
					{{ $errors->first('real_name') }}
					@endif
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-3 col-md-3">
					<button class="btn btn-default ">{{{ trans('common.submit') }}}</button>
				</div>
			</div>
		</fieldset>
		{{ Form::close() }}
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h6 class="panel-title">{{{ trans('user.profile.last_email_confirmations') }}}</h6>
			</div>
			<ul class="list-group">
				@foreach ($user->emailConfirmations()->orderBy('created_at', 'desc')->get()->take(5) as $eC)
				<li class="list-group-item">
					<span data-toggle="tooltip" title="{{{ trans('user.profile.email_confirmation_created', ['date' => $eC->created_at]) }}}">{{{ $eC->email }}}</span>
					<span data-toggle="tooltip" title="{{{ trans('user.profile.email_confirmation_updated', ['date' => $eC->updated_at]) }}}" class="label label-{{{ $eC->stateContext() }}}">{{{ $eC->state }}}</span>
				</li>
				@endforeach
			</ul>
		</div>
	</div>
</div>

{{ Form::open(['route' => ['user.profile.perform.password', $user->username], 'class' => 'form-horizontal']) }}
<fieldset>
	<legend>{{{ trans('common.password') }}}</legend>
	<div class="form-group {{ $errors->has('current_password') ? 'has-error' : '' }}">
		{{ Form::label('current_password', trans('common.current_password'), ['class' => 'control-label
		col-md-2']) }}
		<div class="col-md-6">
			{{ Form::password('current_password', ['class' => 'form-control', 'autocomplete' => 'off']) }}
			@if ($errors->has('current_password'))
			{{ $errors->first('current_password') }}
			@else
			<span class="help-block">{{{ trans('user.profile.leave_password_empty') }}}</span>
			@endif
		</div>
	</div>

	<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
		{{ Form::label('password', trans('common.password'), ['class' => 'control-label col-md-2']) }}
		<div class="col-md-6">
			{{ Form::password('password', ['class' => 'form-control', 'autocomplete' => 'off']) }}
			@if ($errors->has('password'))
			{{ $errors->first('password') }}
			@endif
		</div>
	</div>

	<div class="form-group">
		{{ Form::label('password_confirmation', trans('common.password_again'), ['class' => 'control-label
		col-md-2']) }}
		<div class="col-md-6">
			{{ Form::password('password_confirmation', ['class' => 'form-control']) }}
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-offset-2 col-md-2">
			<button class="btn btn-default ">{{{ trans('user.profile.change_password') }}}</button>
		</div>
	</div>
</fieldset>
{{ Form::close() }}
