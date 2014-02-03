<?php
namespace EDM\Controllers\Api;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\View\Environment as ViewFactory;
use Flow;
use EDM\Resource\Storage\StorageDirector;
use Config;
use ResourceFile;

class UploadController extends BaseController
{
	protected $flowFile;
	protected $flowRequest;
	protected $storageDirector;

	public function __construct(Response $response, Request $request, ViewFactory $view, 
		Flow\File $flowFile, Flow\RequestInterface $flowRequest, StorageDirector $storageDirector)
	{
		parent::__construct($response, $request, $view);
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

			if ($resourceFile->save()) {
				$this->storageDirector->initialStorageTransport($resourceFile, $infoFile->getRealPath());
				unlink($infoFile->getRealPath());
				return $this->response->json($resourceFile->toArray());
			} else {
				return $this->response->json(['errors' => $resourceFile->errors()->toArray()], 500);
			}
		}
	}
}