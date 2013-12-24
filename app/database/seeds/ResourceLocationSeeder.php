<?php
class ResourceLocationSeeder extends Seeder
{
	public function run()
	{
		$data = [
			[
				'type' => 'filesystem',
				'state' => 'active',
				'is_backup' => true,
				'upload_order' => Config::get('storage.instant_transport_upload_order'),
				'download_order' => 10,
			],
			[
				'type' => 'filesystem',
				'state' => 'inactive',
				'is_backup' => false,
				'upload_order' => Config::get('storage.instant_transport_upload_order'),
				'download_order' => 10,
			],
			[
				'type' => 'filesystem',
				'state' => 'only_upload',
				'is_backup' => false,
				'upload_order' => 5,
				'download_order' => 5,
				'settings' => json_encode(
					[
						'storage_path' => public_path(),
						'url_prefix' => '/',
					]
				)
			],
			[
				'type' => 'aws',
				'state' => 'active',
				'upload_order' => 1,
				'download_order' => 1,
				'settings' => json_encode(
					[
						'bucket' => 'peacedev-edm',
					]
				)
			]
		];

		foreach ($data as $dataRow) {
			ResourceLocation::firstOrCreate($dataRow);
		}
	}
}
