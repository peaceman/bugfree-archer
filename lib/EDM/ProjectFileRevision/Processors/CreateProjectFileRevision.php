<?php
namespace EDM\ProjectFileRevision\Processors;

use App;
use ProjectFileRevision;

class CreateProjectFileRevision extends AbstractBaseProcessor
{
	public function process(array $data = null)
	{
		$inputData = array_get($data, 'input_data', []);

		$projectFileRevision = new ProjectFileRevision([
			'bpm' => array_get($inputData, 'project-file.bpm'),
			'description' => array_get($inputData, 'project-file.description')
		]);

		$this->setMusicGenreOnProjectFileRevision($inputData, $projectFileRevision);
		$this->executeValidatorsOnProjectFileRevision($this->validatorBag->preSave, $projectFileRevision);

		$projectFileRevision->save();

		$this->setCompatibleSoftwareOnProjectFileRevision($inputData, $projectFileRevision);
		$this->executeValidatorsOnProjectFileRevision($this->validatorBag->postSave, $projectFileRevision);

		return $projectFileRevision;
	}

}
