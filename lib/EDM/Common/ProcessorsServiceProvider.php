<?php
namespace EDM\Common;

use EDM\ProductRevision;
use EDM\ProjectFileRevision;
use EDM\ProjectFileRevision\Processors\CreateProjectFileRevision;
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
		$this->registerProductRevisionCreateProcessorManager();
	}

	protected function registerProjectFileRevisionProcessors()
	{
		$this->app->bind(CreateProjectFileRevision::class, function ($app) {
			/** @var \Illuminate\Foundation\Application $app */
			$validatorBag = new ProjectFileRevision\ValidatorBag();

			$validatorBag->preSave[] = $app->make(ProjectFileRevision\Validators\BaseRules::class);

			return new CreateProjectFileRevision($validatorBag);
		});
	}

	protected function registerProductRevisionCreateProcessorManager()
	{
		$this->app->bind(ProductRevision\CreateProcessorManager::class, function ($app) {
			return new ProductRevision\CreateProcessorManager($app);
		});
	}

}
