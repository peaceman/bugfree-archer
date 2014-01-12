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
		'missing_address_for_selling' => 'You need to enter a valid address to start selling',
		'leave_password_empty' => "leave empty if you don't want to change your password",
		'change_password' => 'Change password',
		'password_change_successful' => 'We updated your password',
		'confirm_new_email' => 'You have to confirm the entered email, before the change will be effective',
		'last_email_confirmations' => 'Last email confirmations',
		'email_confirmation_created' => 'Created at: :date',
		'email_confirmation_updated' => 'Updated at: :date',
		'basic_information' => 'Basic information',
		'pictures' => 'Pictures',
		'updated_basic_profile' => 'Updated basic profile information',
		'address' => 'Address information',
		'updated_address_information' => 'Updated address information',
		'avatar_upload_failed' => 'Avatar upload failed',
	],
	'items' => [
		'create' => [
			'dashboard_header' => 'Create a new project file',
			'form' => [
				'panel_title' => 'Details of your project file',
				'metadata' => 'Metadata',
				'compatibility' => 'Compatibility',
			],
			'info' => [
				'panel_title' => 'Pay attention'
			],
		],
	],
];
