<?php
namespace EDM\Resource\Storage;

interface StorageInterface
{
	public function store(\ResourceFileLocation $resourceFileLocation, $filePath);
	public function delete(\ResourceFileLocation $resourceFileLocation);
	public function getUrl(\ResourceFileLocation $resourceFileLocation);
	public function getNewFileIdentifier();
	public function getType();
}
