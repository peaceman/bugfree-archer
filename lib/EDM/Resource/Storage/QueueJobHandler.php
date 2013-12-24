<?php
namespace EDM\Resource\Storage;

class QueueJobHandler
{
	public function deleteResourceFileLocation($job, $data)
	{
		var_dump(func_get_args());
	}

	public function fillResourceLocation($job, $data)
	{
		var_dump(func_get_args());
	}

	public function transportToStorage($job, $data)
	{
		var_dump(func_get_args());
	}

	public function wipeResourceLocation($job, $data)
	{
		var_dump(func_get_args());
	}
}
