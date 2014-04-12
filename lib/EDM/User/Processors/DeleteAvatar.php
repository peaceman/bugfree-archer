<?php
namespace EDM\User\Processors;

use EDM\ProcessorInterface;
use EDM\Resource\Storage\StorageDirector;
use EDM\User\UserInjection;
use User;

class DeleteAvatar implements ProcessorInterface
{
	use UserInjection;

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
		$userProfile = $this->user->profile;
		if (!$userProfile->hasAvatar()) {
			return;
		}

		$this->storageDirector->queueWipingOfResourceFile($userProfile->avatar);
		$userProfile->picture_file_id = null;
		$userProfile->save();
	}
}
