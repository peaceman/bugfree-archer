<?php
namespace EDM\User\Processors;

use EDM\Common\Injections\AppContainerInjection;
use EDM\ProcessorInterface;
use EDM\Resource\Storage\StorageDirector;
use EDM\User\UserInjection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use User;


class CreateAvatar implements ProcessorInterface
{
	use UserInjection;
	use AppContainerInjection;

	/**
	 * @var \EDM\Resource\Storage\StorageDirector
	 */
	protected $storageDirector;

	public function __construct(StorageDirector $storageDirector)
	{
		$this->storageDirector = $storageDirector;
	}

	public function process(array $data = null)
	{
		/** @var UploadedFile $avatarFile */
		$avatarFile = $data['avatar_file'];
		$userProfile = $this->user->getProfile();

		$this->ensureFileValidity($avatarFile);

		/** @var \ResourceFile $resourceFile */
		$resourceFile = new \ResourceFile([
			'protected' => false,
			'original_name' => $avatarFile->getClientOriginalName(),
			'mime_type' => $avatarFile->getMimeType(),
			'size' => $avatarFile->getSize(),
		]);

		$resourceFile->userTrackingSession()->associate($this->user->fetchLastTrackingSession());
		$resourceFile->save();

		$this->storageDirector->initialStorageTransport($resourceFile, $avatarFile->getRealPath());

		$resourceImage = new \ResourceImage();
		$resourceImage->originResourceFile()->associate($resourceFile);
		$resourceImage->save();

		if ($userProfile->hasAvatar()) {
			$this->deleteOldAvatar();
		}

		$userProfile->avatar()->associate($resourceImage);
		$userProfile->save();
	}

	protected function deleteOldAvatar()
	{
		$deleteProcess = $this->app->make(DeleteAvatar::class);
		$deleteProcess->process();
	}

	protected function ensureFileValidity(UploadedFile $file)
	{
		if (!$file->isValid()) {
			throw new Exception\InvalidAvatarFile($file);
		}
	}
}
