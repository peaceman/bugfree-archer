<?php
namespace EDM\ProjectFileRevision;

use ProjectFileRevision;

interface ValidatorInterface
{
	public function validate(ProjectFileRevision $projectFileRevision);
}
