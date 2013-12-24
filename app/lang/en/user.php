<?php
return [
	'resend_confirmation_email' => [
		'page_header' => 'Resend confirmation email',
		'page_header_small' => 'got no mail? try to resend',
		'submit_button' => 'Resend',
	],
	'email_confirmation' => [
		'error' => [
			'small_header' => 'for email :email',
			'used' => 'The provided confirmation hash was already used',
			'expired' => 'The provided confirmation hash is expired',
			'deactivated' => 'The provided confirmation hash was deactivated, as you have used a newer confirmation hash for the same email address',
		],
	],
	'profile' => [
		'nav' => [
			'basic' => 'Basic',
			'payment' => 'Payment',
			'account' => 'Account',
			'address' => 'Address'
		],
		'leave_password_empty' => "leave empty if you don't want to change your password",
		'change_password' => 'Change password',
		'password_change_successful' => 'We updated your password',
		'confirm_new_email' => 'You have to confirm the entered email, before the change will be effective',
	],
];
