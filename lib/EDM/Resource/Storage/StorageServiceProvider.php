<?php
namespace EDM\Resource\Storage;

use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->registerFilesystemStorage();
		$this->registerAWSStorage();
		$this->registerStorageDirector();
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

	public function registerAWSStorage()
	{
		$this->app->bind(
			'storage.' . AWSStorage::TYPE,
			function ($app) {
				return new AWSStorage(
					$app->config->get('storage.' . AWSStorage::TYPE, []),
					$app['aws']
				);
			}
		);
	}

	public function registerStorageDirector()
	{
		$this->app->bind(
			StorageDirector::class,
			function ($app) {
				$location = $app['ResourceLocation'];
				$locations = $location->query()
					->where('state', '!=', $location::STATE_INACTIVE)
					->get();

				$storages = array_map(
					function ($location) use ($app) {
						return $app['storage.' . $location['type']];
					},
					$locations->toArray()
				);

				return new StorageDirector($locations, $storages);
			}
		);
	}
}
