<?php

Route::get(
	'test-hier',
	function () {
		return ShopCategory::roots()->get()->map(function ($rootNode) {
			return $rootNode->getDescendantsAndSelf()->toHierarchy();
		});
	}
);

Route::get(
	'test',
	function () {
		/** @var \EDM\Resource\Storage\StorageDirector $storageDirector */
//		$storageDirector = App::make('storage-director');
//
//		$filePath = app_path('database/production.sqlite');
//		$file = new \Symfony\Component\HttpFoundation\File\File($filePath);
//		$resourceFile = new ResourceFile();
//		$resourceFile->original_name = $file->getBasename();
//		$resourceFile->mime_type = $file->getMimeType();
//		$resourceFile->size = $file->getSize();
//		$resourceFile->protected = true;
//		$resourceFile->save();
//
//		$storageDirector->initialStorageTransport($resourceFile, $filePath);

		/** @var ResourceLocation $resourceLocation */
		$resourceLocation = ResourceLocation::where('type', '=', 'aws')->firstOrFail();
		$resourceFileLocations = $resourceLocation->resourceFileLocations()->where('state', '=', ResourceFileLocation::STATE_UPLOADED)->get();

		echo '<pre>';
		foreach ($resourceFileLocations as $resourceFileLocation) {
			/** @var ResourceFileLocation $resourceFileLocation */
			var_dump($resourceFileLocation->getUrl());
		}
	}
);
