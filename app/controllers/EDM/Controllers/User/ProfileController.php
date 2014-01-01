<?php
namespace EDM\Controllers\User;

use EDM\Resource\Storage\StorageDirector;
use Hash;
use Input;
use Notification;
use Redirect;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use User;
use UserProfile;
use Validator;
use View;
use App;
use ResourceFile;
use URL;

class ProfileController extends UserBaseController
{
	public function getIndex()
	{
		$userProfile = $this->user->profile;
		$userAvatar = $userProfile ? $userProfile->avatar : null;

		return View::make('user.profile.index', compact('userProfile', 'userAvatar'));
	}

	protected function getRedirectForTab($tabName)
	{
		return Redirect::to(
			URL::route('user.profile', ['username' => $this->user->username]) . '#!' . $tabName
		);
	}

	public function postBasic()
	{
		$validationRules = [
			'website' => ['url'],
			'about' => ['max:5000'],
			'avatar' => ['image'],
		];
		$profileRedirect = $this->getRedirectForTab('basic');

		$validator = Validator::make(Input::all(), $validationRules);
		if ($validator->fails()) {
			return $profileRedirect->withErrors($validator);
		}

		$userProfile = $this->user->profile ? : new UserProfile();
		$userProfile->fill(Input::only(['website', 'about']));

		if (Input::has('avatar-delete')) {
			$userProfile->picture_file_id = null;
		} elseif (Input::hasFile('avatar')) {
			/** @var UploadedFile $avatar */
			$avatar = Input::file('avatar');
			/** @var \ResourceFile $avatarResourceFile */
			$avatarResourceFile = ResourceFile::create([
				'protected' => false,
				'original_name' => $avatar->getClientOriginalName(),
				'mime_type' => $avatar->getMimeType(),
				'size' => $avatar->getSize(),
			]);

			/** @var StorageDirector $storageDirector */
			$storageDirector = App::make('storage-director');
			$storageDirector->initialStorageTransport($avatarResourceFile, $avatar->getRealPath());

			$userProfile->picture_file_id = $avatarResourceFile->id;
		}

		$this->user->profile()->save($userProfile);
		Notification::success(trans('user.profile.updated_basic_profile'));

		return $profileRedirect;
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
		$validationRules = [
			'email' => $this->user->getEmailValidationRule(),
			'real_name' => User::$validationRules['real_name'],
		];
		$inputData = Input::only(array_keys($validationRules));
		$profileRedirect = $this->getRedirectForTab('account');

		$validator = Validator::make($inputData, $validationRules);
		if ($validator->fails()) {
			return $profileRedirect->withErrors($validator);
		}

		$this->user->real_name = $inputData['real_name'];
		if (!$this->user->save()) {
			Notification::error(trans('common.save_failed'));

			return $profileRedirect;
		}
		Notification::success(trans('common.data_update_successful'));

		if ($this->user->email !== $inputData['email']) {
			$emailConfirmation = $this->user->createEmailConfirmation($inputData['email']);
			$this->user->sendEmailConfirmation(
				$emailConfirmation,
				'emails.user.email-confirmation',
				'mail.user.email_confirmation.subject'
			);
			Notification::info(trans('user.profile.confirm_new_email'));
		}

		return $profileRedirect;
	}
}
