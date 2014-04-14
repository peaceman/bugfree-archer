<?php
namespace EDM\Common\Injections;

trait StorageDirectorInjection 
{
	/**
	 * @var \EDM\Resource\Storage\StorageDirector
	 */
	protected $storageDirector;

	public function injectStorageDirector(\EDM\Resource\Storage\StorageDirector $storageDirector)
	{
		$this->storageDirector = $storageDirector;
	}
} 
