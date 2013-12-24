<?php
class UserController extends BaseController
{
	public function showSignUpForm()
	{
		return View::make('common.login-signup-form');
	}

	public function performSignUp()
	{
		/** @var \Illuminate\Validation\Validator $validator */
		$validator = Validator::make(Input::get('signup'), User::$validationRules);

		if ($validator->fails()) {
			$errors = [];
			foreach ($validator->errors()->toArray() as $key => $value) {
				$errors['signup[' . $key . ']'] = $value;
			};

			return Redirect::route('user.sign-up')->withInput()->withErrors($errors);
		}

		$user = new User(Input::get('signup'));
		$user->state = User::STATE_UNCONFIRMED_EMAIL;
		$user->password = Hash::make(Input::get('signup.password'));

		if ($user->save()) {
			Event::fire(User::EVENT_SIGNUP, $user);
			Notification::info(trans('flash.user.signup_complete'));
			return Redirect::route('frontpage');
		} else {
			return Redirect::route('user.sign-up')
				->withErrors(['general' => 'flash.user.creation_failed']);
		}
	}

	/**
	 * @param string $confirmationHash
	 */
	public function performEmailConfirmation($confirmationHash)
	{
		/** @var UserEmailConfirmation $emailConfirmation */
		$emailConfirmation = UserEmailConfirmation::where('hash', $confirmationHash)->orderBy(
			'created_at',
			'desc'
		)->first();

		if ($emailConfirmation === null) {
			Log::info(
				'tried to confirm an email with a non existing confirmation hash',
				['hash' => $confirmationHash, 'ip' => Request::server('REMOTE_ADDR')]
			);
			App::abort(404);
		}

		if ($emailConfirmation->state !== UserEmailConfirmation::STATE_UNUSED) {
			$data = [
				'header' => trans('common.email_confirmation'),
				'header_small' => trans(
					'user.email_confirmation.error.small_header',
					['email' => $emailConfirmation->email]
				),
				'text' => trans('user.email_confirmation.error.' . $emailConfirmation->state),
			];
			return Redirect::route('full-page-error')
				->with(['full-page-error' => $data]);
		}

		if ($emailConfirmation->isExpired()) {
			$emailConfirmation->state = UserEmailConfirmation::STATE_EXPIRED;
			$emailConfirmation->save();

			$data = [
				'header' => trans('common.email_confirmation'),
				'header_small' => trans(
					'user.email_confirmation.error.small_header',
					['email' => $emailConfirmation->email]
				),
				'text' => trans('user.email_confirmation.error.expired'),
			];
			return Redirect::route('full-page-error')
				->with(['full-page-error' => $data]);
		}

		$user = $emailConfirmation->user;
		$user->email = $emailConfirmation->email;

		// only change user state to active, if it was in the unconfirmed state
		// to prevent unintentional user state changes
		if ($user->state === User::STATE_UNCONFIRMED_EMAIL) {
			$user->state = User::STATE_ACTIVE;
		}

		$user->save();

		// deactivate unused email confirmations for the same address
		UserEmailConfirmation::where('state', '=', UserEmailConfirmation::STATE_UNUSED)
			->where('email', '=', $emailConfirmation->email)
			->update(['state' => UserEmailConfirmation::STATE_DEACTIVATED]);

		$emailConfirmation->state = UserEmailConfirmation::STATE_USED;
		$emailConfirmation->save();

		Event::fire(User::EVENT_EMAIL_CONFIRMATION, $user);
		Auth::login($user);

		Notification::success(trans('flash.user.email_confirmation_successful'));
		return Redirect::route('frontpage');
	}

	public function showResendConfirmationEmail()
	{
		return View::make('user.resend-confirmation-email');
	}

	public function performResendConfirmationEmail()
	{
		$validationRules = [
			'username' => ['required', 'max:255', 'alpha_dash'],
			'email' => ['required', 'max:255', 'email'],
		];

		$data = Input::only(array_keys($validationRules));
		$validator = Validator::make($data, $validationRules);

		if ($validator->fails()) {
			return Redirect::route('user.resend-confirmation-email')
				->withInput()->withErrors($validator);
		}

		$user = User::where('username', '=', $data['username'])
			->where('email', '=', $data['email'])->first();

		if (!$user) {
			Notification::error(trans('flash.user.not_found'));
			return Redirect::route('user.resend-confirmation-email');
		}

		$userEventHandler = App::make('EDM\User\UserEventHandler');
		$userEventHandler->onUserSignUp($user);

		Notification::info(trans('flash.user.confirmation_mail_resent'));
		return Redirect::route('frontpage');
	}
}
