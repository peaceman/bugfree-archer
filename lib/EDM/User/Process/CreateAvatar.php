<?php
namespace EDM\User\Process;

use EDM\Resource\Storage\StorageDirector;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use User;


class CreateAvatar extends AbstractUserProcess
{
	/**
	 * @var \EDM\Resource\Storage\StorageDirector
	 */
	protected $storageDirector;

	public function __construct(User $user, StorageDirector $storageDirector)
	{
		parent::__construct($user);

		$this->storageDirector = $storageDirector;
	}

	public function process(array $data = null)
	{
		/** @var UploadedFile $avatarFile */
		$avatarFile = $data['avatar_file'];
		$userProfile = $this->user->profile;

		$this->ensureFileValidity($avatarFile);

		$resourceFile = \ResourceFile::create([
			'protected' => false,
			'original_name' => $avatarFile->getClientOriginalName(),
			'mime_type' => $avatarFile->getMimeType(),
			'size' => $avatarFile->getSize(),
		]);

		$this->storageDirector->initialStorageTransport($resourceFile, $avatarFile->getRealPath());

		if ($userProfile->picture_file_id !== null) {
			$this->deleteOldAvatar();
		}
		$userProfile->picture_file_id = $resourceFile->id;
		$userProfile->save();
	}

	protected function deleteOldAvatar()
	{
		$userProfile = $this->user->profile;
		$this->storageDirector->queueWipingOfResourceFile($userProfile->avatar);
	}

	protected function ensureFileValidity(UploadedFile $file)
	{
		if (!$file->isValid()) {
			throw new Exception\InvalidAvatarFile($file);
		}
	}
}
