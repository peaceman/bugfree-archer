<?php
namespace EDM\Controllers\User;
use Hash;
use View;
use User;
use Validator;
use Input;
use Redirect;
use Notification;

class ProfileController extends UserBaseController
{
	public function getIndex()
	{
		return View::make('user.profile.index');
	}

	public function postChangePassword()
	{
		$validationRules = [
			'current_password' => ['required'],
			'password' => ['required', 'confirmed'],
		];
		$profileRedirect = Redirect::route('user.profile', ['username' => $this->user->username]);
		if (!$this->user->checkPassword(Input::get('current_password'))) {
			return $profileRedirect->withErrors(['current_password' => 'Invalid password']);
		}

		$validator = Validator::make(Input::all(), $validationRules);
		if ($validator->fails()) {
			return $profileRedirect->withErrors($validator);
		}

		$newHashedPassword = Hash::make(Input::get('password'));
		$this->user->password = $newHashedPassword;
		if (!$this->user->save()) {
			Notification::error(trans('common.save_failed'));
			return $profileRedirect;
		}

		Notification::success(trans('user.profile.password_change_successful'));
		return $profileRedirect;
	}

	public function postAccount()
	{
		$emailValidation = User::$validationRules['email'];
		$uniqueEmailValidatorKey = array_search('unique:users,email', $emailValidation);
		$emailValidation[$uniqueEmailValidatorKey] .= ',' . $this->user->id;

		$validationRules = [
			'email' => $emailValidation,
			'real_name' => User::$validationRules['real_name'],
			'current_password' => ['required_with:password'],
			'password' => ['confirmed'],
		];

		$currentPassword = Input::get('current_password');
		if (!empty($currentPassword)) {
			$passwordCheckResult = Hash::check($currentPassword, $this->user->password);
			if (!$passwordCheckResult) {
				Notification::error(trans('user.profile.incorrect_password'));
				return Redirect::route('user.profile', ['username' => $this->user->username]);
			}
		}

		$validator = Validator::make(Input::all(), $validationRules);
		if ($validator->fails()) {
			return Redirect::route('user.profile', ['username' => $this->user->username])->withErrors($validator);
			echo '<pre>';
			var_dump($validator->messages());
			exit;
		}
	}
}
