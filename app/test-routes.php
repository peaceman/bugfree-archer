<?php
Route::get(
	'test',
	function () {
		/** @var \EDM\Resource\Storage\StorageDirector $storageDirector */
		$storageDirector = App::make('storage-director');

		$filePath = app_path('database/production.sqlite');
		$file = new \Symfony\Component\HttpFoundation\File\File($filePath);
		$resourceFile = new ResourceFile();
		$resourceFile->original_name = $file->getBasename();
		$resourceFile->mime_type = $file->getMimeType();
		$resourceFile->size = $file->getSize();
		$resourceFile->protected = true;
		$resourceFile->save();

		$storageDirector->initialStorageTransport($resourceFile, $filePath);

		echo '<pre>';
		var_dump($resourceFile->getUrl());
		dd($resourceFile->resourceFileLocations()->get()->toArray());
	}
);
