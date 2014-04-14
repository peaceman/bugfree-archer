{{ Form::model($userAddress, ['route' => ['user.profile.perform.address', $user->username], 'class' => 'form-horizontal']) }}
<fieldset>
	<legend>{{ trans('user.profile.address') }}</legend>
	<div class="col-md-8">
		{{ Form::textareaField('address_lines', trans('common.address'), null, ['rows' => 3]) }}
		{{ Form::textField('postcode', trans('common.postcode')) }}
		{{ Form::textField('locality', trans('common.locality')) }}
		{{ Form::selectField('country_id', trans('common.country'), $countries, 276) }}

		<div class="form-group">
			<div class="col-md-9 col-md-offset-3">
				<button class="btn btn-default">
					{{{ trans('common.submit') }}}
				</button>
			</div>
		</div>
	</div>

</fieldset>
{{ Form::close() }}
