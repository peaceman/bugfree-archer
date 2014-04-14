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
		$this->registerReviewProcessors();
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

	protected function registerReviewProcessors()
	{
		$this->app->bind(\EDM\Review\Processors\StartReview::class, function ($app) {
			/** @var \Illuminate\Foundation\Application $app */
			$validatorBag = new \EDM\Review\ValidatorBag();

			$validatorBag->preSave[] = $app->make(\EDM\Review\Validators\BaseRules::class);

			return new \EDM\Review\Processors\StartReview($validatorBag);
		});

		$this->app->bind(\EDM\Review\Processors\FinishReview::class, function ($app) {
			/** @var \Illuminate\Foundation\Application $app */
			$validatorBag = new \EDM\Review\ValidatorBag();

			$validatorBag->preSave[] = $app->make(\EDM\Review\Validators\BaseRules::class);

			return new \EDM\Review\Processors\FinishReview($validatorBag);
		});
	}

}
