<?php
namespace EDM\ProductRevision;

use EDM\ProjectFileRevision\Processors\CreateProjectFileRevision;
use EDM\SampleRevision\Processors\CreateSampleRevision;
use Illuminate\Support\Manager;

class CreateProcessorManager extends Manager
{
	protected function createProjectFilesTemplatesDriver()
	{
		return $this->app->make(CreateProjectFileRevision::class);
	}

	protected function createProjectFilesPresetsDriver()
	{
		return $this->app->make(CreateProjectFileRevision::class);
	}

	protected function createSamplesDriver()
	{
		return $this->app->make(CreateSampleRevision::class);
	}
}
