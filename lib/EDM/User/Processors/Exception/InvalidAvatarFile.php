<?php
namespace EDM\User\Processors\Exception;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class InvalidAvatarFile extends \RuntimeException
{
	/**
	 * @var \Symfony\Component\HttpFoundation\File\UploadedFile
	 */
	public $avatarFile;

	public function __construct(UploadedFile $file)
	{
		$this->avatarFile = $file;
	}
}
