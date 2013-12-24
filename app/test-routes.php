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
		$resourceFile->save();

		$storageDirector->initialStorageTransport($resourceFile, $filePath);

		echo '<pre>';
		dd($resourceFile->resourceFileLocations()->get()->toArray());
	}
);
