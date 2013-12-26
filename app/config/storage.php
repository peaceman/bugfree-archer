<?php
return [
	'instant_transport_upload_order' => 0,
	'filesystem' => [
		'storage_path' => public_path('storage'),
		'url_prefix' => 'storage',
	],
	'resource_file' => [
		'chunk_size' => 100,
	],
	'protected_url_lifetime_in_minutes' => 10,
];
