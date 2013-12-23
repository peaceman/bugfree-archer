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
		$validationRules = [
			'email' => $this->user->getEmailValidationRule(),
			'real_name' => User::$validationRules['real_name'],
		];
		$inputData = Input::only(array_keys($validationRules));
		$profileRedirect = Redirect::route('user.profile', ['username' => $this->user->username]);

		$validator = Validator::make($inputData, $validationRules);
		if ($validator->fails()) {
			return $profileRedirect->withErrors($validator);
		}

		$this->user->fill($inputData);
		if (!$this->user->save()) {
			Notification::error(trans('common.save_failed'));
			return $profileRedirect;
		}

		Notification::success(trans('common.data_update_successful'));
		return $profileRedirect;
	}
}
