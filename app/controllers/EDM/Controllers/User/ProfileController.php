<?php
namespace EDM\Controllers\User;

use App;
use EDM\User\Processors;
use EDM\User\ValidationRules;
use Exception;
use Hash;
use Input;
use Log;
use Notification;
use Redirect;
use Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use URL;
use Validator;
use View;

class ProfileController extends UserBaseController
{
	public function getIndex()
	{
		$userProfile = $this->user->getProfile();
		$userAvatar = $userProfile ? $userProfile->avatar : null;
		$userAddress = $this->user->getAddress();
		$userPayoutDetail = $this->user->getPayoutDetail();
		$countries = array_map(function ($country) {
			return $country['name'];
		}, \Countries::getList());

		return View::make('user.profile.index', compact('userProfile', 'userAvatar', 'userAddress', 'userPayoutDetail', 'countries'));
	}

	protected function getRedirectForTab($tabName)
	{
		return Redirect::to(
			URL::route('user.profile', ['username' => $this->user->username]) . '#!' . $tabName
		);
	}

	public function postAddress()
	{
		$profileRedirect = $this->getRedirectForTab('address');
		$validator = Validator::make(Input::all(), (new ValidationRules\AddressInformation())->getValidationRules());

		if ($validator->fails()) {
			return $profileRedirect
				->withInput()
				->withErrors($validator);
		}

		$userAddress = $this->user->getAddress();
		$userAddress->fill(Input::all());

		$userAddress->save();
		Notification::success(trans('user.profile.updated_address_information'));

		return Redirect::intended($profileRedirect->getTargetUrl());
	}

	public function postBasic()
	{
		$profileRedirect = $this->getRedirectForTab('basic');
		$validator = Validator::make(
			Input::all(),
			(new ValidationRules\BasicInformation())->getValidationRules()
		);

		if (Input::has('avatar-delete')) {
			$this->handleAvatarDeletion();
		}

		$validator->passes();
		if (!$validator->messages()->has('avatar') && Input::hasFile('avatar')) {
			$this->handleAvatarCreation();
		}

		if ($validator->fails()) {
			return $profileRedirect->withInput()
				->withErrors($validator);
		}

		$userProfile = $this->user->getProfile();
		$userProfile->fill(Input::only(['website', 'about']));

		$userProfile->save();
		Notification::success(trans('user.profile.updated_basic_profile'));

		return $profileRedirect;
	}

	public function postPayoutDetail()
	{
		$profileRedirect = $this->getRedirectForTab('payout-detail');
		$validator = Validator::make(
			Input::all(),
			\UserPayoutDetail::$validationRules
		);

		if ($validator->fails()) {
			return $profileRedirect
				->withInput()
				->withErrors($validator);
		}

		$payoutDetail = $this->user->getPayoutDetail();
		$payoutDetail->paypal_email = Input::get('paypal_email');
		$payoutDetail->userTrackingSession()->associate($this->user->fetchLastTrackingSession());

		if (!$payoutDetail->save()) {
			Notification::error(trans('common.save_failed'));

			return $profileRedirect;
		}

		Notification::success(trans('common.data_update_successful'));

		return Redirect::intended($profileRedirect->getTargetUrl());
	}

	public function postChangePassword()
	{
		$validationRules = [
			'current_password' => ['required'],
			'password' => ['required', 'confirmed'],
		];
		$profileRedirect = $this->getRedirectForTab('account');

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
		$validator = Validator::make(
			Request::all(),
			(new ValidationRules\AccountInformation($this->user))->getValidationRules()
		);

		if ($validator->fails()) {
			return $this->getRedirectForTab('account')->withInput()
				->withErrors($validator);
		}

		$this->user->real_name = Request::get('real_name');
		if (!$this->user->save()) {
			Notification::error(trans('common.save_failed'));

			return $this->getRedirectForTab('account');
		}

		Notification::success(trans('common.data_update_successful'));

		if ($this->user->email !== ($email = Request::get('email'))) {
			App::make(Processors\StartEmailConfirmation::class)
				->process(['new_email' => Request::get('email')]);

			Notification::info(trans('user.profile.confirm_new_email'));
		}

		return $this->getRedirectForTab('account');
	}

	protected function handleAvatarDeletion()
	{
		try {
			$process = App::make(Processors\DeleteAvatar::class);
			$process->process();
		} catch (Exception $e) {
			Log::error(
				'uncaught exception in the delete avatar process',
				['exception' => $e]
			);

			throw $e;
		}
	}

	protected function handleAvatarCreation()
	{
		/** @var UploadedFile $avatar */
		$avatar = Input::file('avatar');

		try {
			$process = App::make(Processors\CreateAvatar::class);
			$process->process(['avatar_file' => $avatar]);
		} catch (Processors\Exception\InvalidAvatarFile $e) {
			Log::notice(
				'invalid avatar file in the create avatar process',
				['error' => $e->avatarFile->getError(), 'error_message' => $e->avatarFile->getErrorMessage()]
			);

			Notification::warning(trans('user.profile.avatar_upload_failed'));
		} catch (Exception $e) {
			Log::error(
				'uncaught exception in the create avatar process',
				['exception' => $e]
			);

			throw $e;
		}
	}
}
