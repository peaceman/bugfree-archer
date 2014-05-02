<?php
namespace EDM\ProjectFileRevision\Processors;

use EDM\ValidatorBagInjection;

class UpdateProjectFileRevision extends AbstractBaseProcessor
{
	use ValidatorBagInjection;

	public function process(array $data = null)
	{
		/** @var \ProjectFileRevision $projectFileRevision */
		$projectFileRevision = array_get($data, 'product_revision');
		$inputData = array_get($data, 'input_data', []);

		$projectFileRevision->fill([
			'bpm' => array_get($inputData, 'project-file.bpm'),
			'description' => array_get($inputData, 'project-file.description'),
		]);

		$this->setMusicGenreOnProjectFileRevision($inputData, $projectFileRevision);
		$this->executeValidatorsOnModel($this->validatorBag->preSave, $projectFileRevision);

		$projectFileRevision->save();

		$this->setCompatibleSoftwareOnProjectFileRevision($inputData, $projectFileRevision);
		$this->executeValidatorsOnModel($this->validatorBag->postSave, $projectFileRevision);

		return $projectFileRevision;
	}
}
