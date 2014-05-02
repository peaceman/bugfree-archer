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
			$validatorBag = new \EDM\ValidatorBag();

			$validatorBag->preSave[] = $app->make(\EDM\Common\Validators\BaseRules::class);

			$processor = $app->make(CreateProjectFileRevision::class);
			$processor->injectValidatorBag($validatorBag);

			return $processor;
		});

		$this->app->bind(UpdateProjectFileRevision::class, function ($app) {
			/** @var \Illuminate\Foundation\Application $app */
			$validatorBag = new \EDM\ValidatorBag();

			$validatorBag->preSave[] = $app->make(\EDM\Common\Validators\BaseRules::class);

			$processor = $app->make(UpdateProjectFileRevision::class);
			$processor->injectValidatorBag($validatorBag);

			return $processor;
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

	protected function registerResourceLocationProcessors()
	{
		$this->app->bind(\EDM\ResourceLocation\Processors\UpdateResourceLocation::class, function ($app) {
			/** @var \Illuminate\Foundation\Application $app */
			$validatorBag = new \EDM\ValidatorBag();

			$validatorBag->preSave[] = $app->make(\EDM\Common\Validators\BaseRules::class);

			$processor = $app->make(\EDM\ResourceLocation\Processors\UpdateResourceLocation::class);
			$processor->injectValidatorBag($validatorBag);

			return $processor;
		});
	}

}
