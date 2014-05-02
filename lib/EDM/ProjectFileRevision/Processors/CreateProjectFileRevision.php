<?php
namespace EDM\ProjectFileRevision\Processors;

use App;
use EDM\ValidatorBagInjection;
use ProjectFileRevision;

class CreateProjectFileRevision extends AbstractBaseProcessor
{
	use ValidatorBagInjection;

	public function process(array $data = null)
	{
		$inputData = array_get($data, 'input_data', []);

		$projectFileRevision = new ProjectFileRevision([
			'bpm' => array_get($inputData, 'project-file.bpm'),
			'description' => array_get($inputData, 'project-file.description')
		]);

		$this->setMusicGenreOnProjectFileRevision($inputData, $projectFileRevision);
		$this->executeValidatorsOnModel($this->validatorBag->preSave, $projectFileRevision);

		$projectFileRevision->save();

		$this->setCompatibleSoftwareOnProjectFileRevision($inputData, $projectFileRevision);
		$this->executeValidatorsOnModel($this->validatorBag->postSave, $projectFileRevision);

		return $projectFileRevision;
	}

}
