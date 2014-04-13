<?php
return [
	'navbar' => [
		'brand' => 'EDM Admin',
		'frontend' => 'Frontend',
		'users' => 'Users',
		'shop_items' => 'Shop items',
		'shop_categories' => 'Shop categories',
		'reviews' => 'Reviews',
		'music_genres' => 'Music genres',
		'music_software' => 'Music software',
		'music_programs' => 'Music programs',
		'music_plugins' => 'Music plugins',
		'music_plugin_banks' => 'Music plugin banks',
		'resources' => 'Resources',
		'resource_files' => 'Resource files',
		'resource_locations' => 'Resource locations',
	],
	'dashboard' => [
		'page_header' => [
			'big' => 'Admin dashboard',
			'small' => 'the force will be with you',
		]
	],
	'review' => [
		'page_header' => [
			'reviews' => 'Reviews',
			'amount_of_waiting_reviews' => 'Amount of waiting reviews',
		],
		'panel_header' => [
			'reviews_in_state' => 'Reviews in state',
		],
		'states' => [
			Review::STATE_WAITING => 'waiting',
			Review::STATE_IN_PROGRESS => 'in progress',
			Review::STATE_FINISHED => 'finished',
		],
	],
];
