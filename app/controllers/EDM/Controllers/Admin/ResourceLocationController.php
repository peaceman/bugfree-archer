<?php
namespace EDM\Controllers\Admin;

use EDM\Controllers\AuthenticatedBaseController;

class ResourceLocationController extends AuthenticatedBaseController
{
	public function index()
	{
		$resourceLocations = \ResourceLocation::all();
		return $this->response->view('admin.resource-location.index', [
			'resourceLocations' => $resourceLocations,
		]);
	}

	public function show($resourceLocationId)
	{
		$resourceLocation = \ResourceLocation::findOrFail($resourceLocationId);
		$resourceFileLocations = $resourceLocation->resourceFileLocations()->paginate();

		return $this->response->view('admin.resource-location.show', [
			'rL' => $resourceLocation,
			'resourceFileLocations' => $resourceFileLocations,
		]);
	}

	public function update($resourceLocationId)
	{
		$resourceLocation = \ResourceLocation::findOrFail($resourceLocationId);

		$data = [
			'resource_location' => $resourceLocation,
			'input_data' => array_filter($this->request->only([
				'state',
				'is_backup',
				'upload_order',
				'download_order',
				'settings',
			])),
		];

		try {
			$updateProcessor = \App::make(\EDM\ResourceLocation\Processors\UpdateResourceLocation::class);
			$updateProcessor->process($data);

			\Notification::success(trans('common.notifications.update.succeeded'));
			return $this->redirector->route('admin.resource-locations.show', [$resourceLocation->id]);
		} catch (\EDM\Common\Exception\Validation $e) {
			\Notification::error(trans('common.notifications.update.failed'));
			return $this->redirector->back();
		}
	}
}
