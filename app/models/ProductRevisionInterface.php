<?php

/**
 * Interface ProductRevisionInterface
 *
 * @property \ResourceFile $archiveFile
 */
interface ProductRevisionInterface
{
	public function getResourceFileTypes();
	public function getFiles();
	public function getMetaData();

	public function getArchiveFileAttribute();
	public function getListingPictureFileAttribute();
}
