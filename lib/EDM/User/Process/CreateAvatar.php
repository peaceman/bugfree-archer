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

		$resourceFile = \ResourceFile::create([
			'protected' => false,
			'original_name' => $avatarFile->getClientOriginalName(),
			'mime_type' => $avatarFile->getMimeType(),
			'size' => $avatarFile->getSize(),
		]);

		$this->storageDirector->initialStorageTransport($resourceFile, $avatarFile->getRealPath());

		$userProfile->picture_file_id = $resourceFile->id;
		$userProfile->save();
	}
}
