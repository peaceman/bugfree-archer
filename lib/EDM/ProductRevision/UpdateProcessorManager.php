<?php
namespace EDM\ProductRevision;

use EDM\ProjectFileRevision\Processors\UpdateProjectFileRevision;
use Illuminate\Support\Manager;

class UpdateProcessorManager extends Manager
{
	protected function createProjectFilesTemplatesDriver()
	{
		return $this->app->make(UpdateProjectFileRevision::class);
	}

	protected function createProjectFilesPresetsDriver()
	{
		return $this->app->make(UpdateProjectFileRevision::class);
	}

//	protected function createSamplesDriver()
//	{
//		return $this->app->make(UpdateSampleRevision::class);
//	}
}
