{{ Form::model($userPayoutDetail, ['route' => ['user.profile.perform.payout-detail', $user->username], 'class' => 'form-horizontal']) }}
<fieldset>
	<legend>{{ trans('user.profile.payout_detail') }}</legend>
	<div class="col-md-8">
		{{ Form::textField('paypal_email', trans('common.paypal_email')) }}

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
