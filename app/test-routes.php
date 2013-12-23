<?php
Route::get(
	'test',
	function () {
		$user = User::findOrFail(10);
		$handler = new EDM\User\UserEventHandler;
		$handler->onUserSignUp($user);
	}
);
