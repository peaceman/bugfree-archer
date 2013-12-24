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
				'priority' => Config::get('storage.instant_store_priority'),
			],
			[
				'type' => 'filesystem',
				'state' => 'inactive',
				'is_backup' => false,
				'priority' => Config::get('storage.instant_store_priority'),
			],
			[
				'type' => 'filesystem',
				'state' => 'only_upload',
				'is_backup' => false,
				'priority' => 5,
				'settings' => json_encode([
					'storage_path' => public_path(),
					'url_prefix' => '/',
				])
			],
		];

		foreach ($data as $dataRow) {
			ResourceLocation::firstOrCreate($dataRow);
		}
	}
}
