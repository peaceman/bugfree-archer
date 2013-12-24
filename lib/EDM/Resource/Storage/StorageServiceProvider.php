<?php
namespace EDM\Resource\Storage;

use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->registerFilesystemStorage();
	}

	public function registerFilesystemStorage()
	{
		$this->app->bind(
			'storage.' . FilesystemStorage::TYPE,
			function ($app) {
				return new FilesystemStorage(
					$app->config->get('storage.' . FilesystemStorage::TYPE)
				);
			}
		);
	}
}
