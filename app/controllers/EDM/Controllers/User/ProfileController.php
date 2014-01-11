<?php
namespace EDM\Controllers\User;

use App;
use EDM\Resource\Storage\StorageDirector;
use EDM\User\Process;
use EDM\User\ValidationRules;
use Hash;
use Input;
use Notification;
use Redirect;
use ResourceFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use URL;
use UserProfile;
use Validator;
use View;

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
		$validator = Validator::make(
			Request::all(),
			(new ValidationRules\AccountInformation($this->user))->getValidationRules()
		);

		if ($validator->fails()) {
			return $this->getRedirectForTab('account')->withErrors($validator);
		}

		$this->user->real_name = Request::get('real_name');
		if (!$this->user->save()) {
			Notification::error(trans('common.save_failed'));

			return $this->getRedirectForTab('account');
		}

		Notification::success(trans('common.data_update_successful'));

		if ($this->user->email !== ($email = Request::get('email'))) {
			(new Process\StartEmailConfirmation($this->user))
				->process(['new_email' => Request::get('email')]);
			
			Notification::info(trans('user.profile.confirm_new_email'));
		}

		return $this->getRedirectForTab('account');
	}
}
