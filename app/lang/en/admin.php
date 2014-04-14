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
			'details' => 'Details of review',
			'about' => 'about',
			'from' => 'from',
		],
		'panel_header' => [
			'reviews_in_state' => 'Reviews in state',
			'overview' => 'Overview',
			'current_state' => 'Current review state',
			'result' => 'Review result',
		],
		'states' => [
			Review::STATE_WAITING => 'waiting',
			Review::STATE_IN_PROGRESS => 'in progress',
			Review::STATE_FINISHED => 'finished',
		],
		'notifications' => [
			'started' => 'You started the review process',
			'finished' => 'You finished the review process',
		],
		'result_reasoning' => 'Reasoning of the judgement',
		'start' => 'Start review',
		'accepted' => 'Accepted',
		'rejected' => 'Rejected',
	],
	'user' => [
		'page_header' => [
			'users' => 'Users',
			'amount_of_active_users' => 'Amount of active users',
			'latest_active' => 'Latest active users',
			'latest_unconfirmed' => 'Latest unconfirmed users',
		],
		'states' => [
			\User::STATE_ACTIVE => 'active',
			\User::STATE_INACTIVE => 'inactive',
			\User::STATE_PERMA_BAN => 'permanent ban',
			\User::STATE_TMP_BAN => 'temporary ban',
			\User::STATE_UNCONFIRMED_EMAIL => 'unconfirmed email',
		],
		'public_profile' => 'Public profile',
	],
];
