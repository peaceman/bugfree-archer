<?php
use EDM\User\Processors as UserProcess;

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

	public function performEmailConfirmation($confirmationHash)
	{
		$process = new UserProcess\FinishEmailConfirmation();

		try {
			$process->process(['confirmation_hash' => $confirmationHash]);
			Notification::success(trans('flash.user.email_confirmation_successful'));

			return Redirect::route('auth.log-in');
		} catch (UserProcess\Exception\EmailConfirmation\NonExistingConfirmationHash $e) {
			App::abort(404);
		} catch (UserProcess\Exception\EmailConfirmation\AbstractException $e) {
			$viewData = [
				'header' => trans('common.email_confirmation'),
				'header_small' => trans(
					'user.email_confirmation.error.small_header',
					['email' => $e->getConfirmation()->email]
				),
				'text' => trans('user.email_confirmation.error.' . $e->getConfirmation()->state),
			];

			return Redirect::route('full-page-error')
				->with(['full-page-error' => $viewData]);
		} catch (Exception $e) {
			Log::error(
				'uncaught exception in the finish email confirmation process',
				['exception' => $e]
			);

			throw $e;
		}
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
