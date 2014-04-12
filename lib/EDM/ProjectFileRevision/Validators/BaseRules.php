<?php
namespace EDM\ProjectFileRevision\Validators;

use EDM\Common;
use EDM\ProjectFileRevision\ValidatorInterface;
use Illuminate\Validation\Factory;
use ProjectFileRevision;

class BaseRules implements ValidatorInterface
{
	/**
	 * @var \Illuminate\Validation\Factory
	 */
	protected $validatorFactory;

	public function __construct(Factory $validatorFactory)
	{
		$this->validatorFactory = $validatorFactory;
	}

	public function validate(ProjectFileRevision $projectFileRevision)
	{
		$validationRules = ProjectFileRevision::$validationRules;
		$validationData = array_only(
			$projectFileRevision->getAttributes(),
			array_keys($validationRules)
		);

		$validator = $this->validatorFactory->make($validationData, $validationRules);

		if ($validator->fails()) {
			throw new Common\Exception\Validation($this->validatorFactory);
		}
	}

}
