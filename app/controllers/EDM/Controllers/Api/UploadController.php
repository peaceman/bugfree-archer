<?php
namespace EDM\Controllers\Api;

use Config;
use EDM\Controllers\AuthenticatedBaseController;
use EDM\Resource\Storage\StorageDirector;
use Flow;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Response;
use Illuminate\View\Environment as ViewFactory;
use ResourceFile;

class UploadController extends AuthenticatedBaseController
{
	protected $flowFile;
	protected $flowRequest;
	protected $storageDirector;

	public function __construct(Response $response, Redirector $redirector, Request $request, ViewFactory $view,
		Flow\File $flowFile, Flow\RequestInterface $flowRequest, StorageDirector $storageDirector)
	{
		parent::__construct($response, $redirector, $request, $view);
		$this->flowFile = $flowFile;
		$this->flowRequest = $flowRequest;
		$this->storageDirector = $storageDirector;
	}

	public function checkChunk()
	{
		if ($this->flowFile->checkChunk()) {
			return $this->response->make('', 200);
		} else {
			return $this->response->make('', 404);
		}
	}

	public function saveChunk()
	{
		if ($this->flowFile->validateChunk()) {
			$this->flowFile->saveChunk();
		} else {
			return $this->response->make('', 400);
		}

		$storageFilename = Config::get('uploads.flow_config.tempDir')
			. DIRECTORY_SEPARATOR
			. uniqid();

		if ($this->flowFile->validateFile() && $this->flowFile->save($storageFilename)) {
			$infoFile = new \Symfony\Component\HttpFoundation\File\File($storageFilename);

			$resourceFile = new ResourceFile();
			$resourceFile->original_name = $this->flowRequest->getFileName();
			$resourceFile->size = $this->flowRequest->getTotalSize();
			$resourceFile->mime_type = $infoFile->getMimeType();
			$resourceFile->protected = (bool)$this->request->get('protect_file', false);
			$resourceFile->userTrackingSession()->associate($this->user->fetchLastTrackingSession());

			if ($resourceFile->save()) {
				$this->storageDirector->initialStorageTransport($resourceFile, $infoFile->getRealPath(), true);

				if (str_contains($resourceFile->mime_type, 'image')) {
					$resourceImage = new \ResourceImage();
					$resourceImage->originResourceFile()->associate($resourceFile);
					$resourceImage->save();
				}

				unlink($infoFile->getRealPath());
				return $this->response->json($resourceFile->toArray());
			} else {
				return $this->response->json(['errors' => $resourceFile->errors()->toArray()], 500);
			}
		}
	}
}
