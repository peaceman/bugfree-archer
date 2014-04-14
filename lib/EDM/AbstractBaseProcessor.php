<?php
namespace EDM;

abstract class AbstractBaseProcessor implements ProcessorInterface
{
	protected function requireData(array $source, $key, $default = null)
	{
		$result = array_get($source, $key, $default);

		if (is_null($result)) {
			throw new \EDM\Common\Exception\MissingParameter($source, $key);
		}

		return $result;
	}
}
