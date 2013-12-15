<?php
class UserController extends BaseController
{
	public function showSignUpForm()
	{
		return View::make('common.login-signup-form');
	}

	public function performSignUp()
	{
		$validationRules = [
			'real_name' => ['required', 'max:255'],
			'username' => ['required', 'alpha_dash', 'unique:users'],
			'email' => ['required', 'email', 'unique:users'],
			'password' => ['required', 'confirmed'],
		];

		/** @var \Illuminate\Validation\Validator $validator */
		$validator = Validator::make(Input::get('signup'), $validationRules);

		if ($validator->fails()) {
			$errors = [];
			foreach ($validator->errors()->toArray() as $key => $value) {
				$errors['signup[' . $key . ']'] = $value;
			};

			return Redirect::route('user.sign-up')->withInput()->withErrors($errors);
		}

		$user = new User(Input::get('signup'));
		$user->state = User::STATE_UNCONFIRMED_EMAIL;
		$user->password = Hash::make(Input::get('signup[password]'));

		if ($user->save()) {
			Event::fire(User::EVENT_SIGNUP, $user);
			return Redirect::route('frontpage');
		} else {
			return Redirect::route('user.sign-up')->withErrors(['general' => 'User creation failed. Try again later']);
		}
	}

	/**
	 * @param string $confirmationHash
	 *
	 * @todo calls with non existing hashes, should be logged for security measures
	 */
	public function performEmailConfirmation($confirmationHash)
	{
		/** @var UserEmailConfirmation $emailConfirmation */
		$emailConfirmation = UserEmailConfirmation::where('hash', $confirmationHash)->orderBy(
			'created_at',
			'desc'
		)->first();

		if ($emailConfirmation === null) {
			App::abort(404);
		}

		if ($emailConfirmation->used) {
			return View::make('user.used-confirmation-hash');
		}

		$user = $emailConfirmation->user;
		$user->state = User::STATE_ACTIVE;
		$user->save();

		$emailConfirmation->used = true;
		$emailConfirmation->save();

		Event::fire(User::EVENT_EMAIL_CONFIRMATION, $user);
		Auth::login($user);
		// todo flash message
		return Redirect::route('frontpage');
	}
}
