<?php
namespace EDM\Common\Exception;

class MissingParameter extends \InvalidArgumentException
{
	public $parameterSource;
	public $parameterKey;

	public function __construct($source, $key)
	{
		$this->parameterSource = $source;
		$this->parameterKey = $key;
	}
}
