<?php
namespace EDM\Common;

use EDM\ProductRevision;
use EDM\ProjectFileRevision\Processors\CreateProjectFileRevision;
use EDM\ProjectFileRevision;
use EDM\ProjectFileRevision\Processors\UpdateProjectFileRevision;
use EDM\ShopItemRevision;
use EDM\ShopItemRevision\Processors\UpdateShopItemRevision;
use Illuminate\Support\ServiceProvider;

class ProcessorsServiceProvider extends ServiceProvider
{
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerProjectFileRevisionProcessors();
		$this->registerProductRevisionProcessorManagers();
		$this->registerShopItemRevisionProcessors();
	}

	protected function registerProjectFileRevisionProcessors()
	{
		$this->app->bind(CreateProjectFileRevision::class, function ($app) {
			/** @var \Illuminate\Foundation\Application $app */
			$validatorBag = new ProjectFileRevision\ValidatorBag();

			$validatorBag->preSave[] = $app->make(ProjectFileRevision\Validators\BaseRules::class);

			return new CreateProjectFileRevision($validatorBag);
		});

		$this->app->bind(UpdateProjectFileRevision::class, function ($app) {
			/** @var \Illuminate\Foundation\Application $app */
			$validatorBag = new ProjectFileRevision\ValidatorBag();

			$validatorBag->preSave[] = $app->make(ProjectFileRevision\Validators\BaseRules::class);

			return new UpdateProjectFileRevision($validatorBag);
		});
	}

	protected function registerProductRevisionProcessorManagers()
	{
		$this->app->bind(ProductRevision\CreateProcessorManager::class, function ($app) {
			return new ProductRevision\CreateProcessorManager($app);
		});

		$this->app->bind(ProductRevision\UpdateProcessorManager::class, function ($app) {
			return new ProductRevision\UpdateProcessorManager($app);
		});
	}

	protected function registerShopItemRevisionProcessors()
	{
		$this->app->bind(UpdateShopItemRevision::class, function ($app) {
			/** @var \Illuminate\Foundation\Application $app */
			$validatorBag = new ShopItemRevision\ValidatorBag();

			$validatorBag->preSave[] = $app->make(ShopItemRevision\Validators\BaseRules::class);

			return new UpdateShopItemRevision($validatorBag);
		});
	}

}
