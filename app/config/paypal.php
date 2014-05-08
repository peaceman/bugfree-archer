<?php
return [
	'service' => [
		'mode' => 'sandbox', // live
		'acct1.UserName' => $_ENV['PAYPAL_USERNAME'],
		'acct1.Password' => $_ENV['PAYPAL_PASSWORD'],
		'acct1.Signature' => $_ENV['PAYPAL_SIGNATURE'],
		'acct1.AppId' => $_ENV['PAYPAL_APP_ID'], // sandbox global test appid
	],
	'app' => [
		'system_paypal_receiver' => 'bvc1@edm.com',
		'commission_percentage' => 0.2,
		'fees_payer' => 'EACHRECEIVER',
		'cancel_url_route' => 'pp-endpoints.cancel',
		'ipn_url_route' => 'pp-endpoints.ipn',
		'return_url_route' => 'pp-endpoints.return',
	],
];
