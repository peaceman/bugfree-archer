<?php
namespace EDM\Controllers\Api;

use EDM\Controllers\AuthenticatedBaseController;
use ResourceFile;

class ResourceFileController extends AuthenticatedBaseController
{
	public function index()
	{
		$query = ResourceFile::onlyFromUser($this->user);
		$resourceFiles = $query->get();

		$data = $resourceFiles->map(function ($resourceFile) {
			return array_merge($resourceFile->toArray(), [
				'in_use_by_shop_items' => $resourceFile->inUseByShopItems(),
			]);
		});

		return $this->response->json($data);
	}
}
