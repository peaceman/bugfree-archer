{{ Form::model($userProfile, ['route' => ['user.profile.perform.basic', $user->username], 'class' => 'form-horizontal', 'files' => true]) }}
<fieldset>
	<legend>{{{ trans('user.profile.basic_information') }}}</legend>
	<div class="form-group {{ $errors->has('website') ? 'has-error' : '' }}">
		{{ Form::label('website', trans('common.website'), ['class' => 'control-label col-md-2']) }}
		<div class="col-md-6">
			{{ Form::text('website', null, ['class' => 'form-control']) }}
			@if ($errors->has('website'))
			{{ $errors->first('website') }}
			@endif
		</div>
	</div>

	<div class="form-group {{ $errors->has('about') ? 'has-error' : '' }}">
		{{ Form::label('about', trans('common.about'), ['class' => 'control-label col-md-2']) }}
		<div class="col-md-6">
			{{ Form::textarea('about', null, ['class' => 'form-control']) }}
			@if ($errors->has('about'))
			{{ $errors->first('about') }}
			@else
			<div class="help-block">{{{ trans('user.profile.about_help_text') }}}</div>
			@endif
		</div>
	</div>
</fieldset>
<fieldset>
	<legend>{{{ trans('user.profile.pictures') }}}</legend>
	<div class="form-group {{ $errors->has('avatar') ? 'has-error' : '' }}">
		{{ Form::label('avatar', trans('common.avatar'), ['class' => 'control-label col-md-2']) }}
		<div class="col-md-4">
			{{ Form::file('avatar') }}
			@if ($errors->has('avatar'))
			{{ $errors->first('avatar') }}
			@endif
		</div>
		<div class="col-md-4">
			@if ($userAvatar)
			<img class="img-rounded" src="{{{ $imageUrls->getUrlForFormat($userAvatar, 'profile-avatar-preview') }}}" style="height: 75px"/>
			<div class="checkbox">
				<label>
					{{ Form::checkbox('avatar-delete', true) }}
					{{{ trans('common.delete') }}}
				</label>
			</div>
			@endif
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-4 col-md-offset-2">
			<button class="btn btn-default">
				{{{ trans('common.submit') }}}
			</button>
		</div>
	</div>
</fieldset>
{{ Form::close() }}
