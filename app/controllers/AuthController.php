<?php
class AuthController extends BaseController
{
	public function showLogInForm()
	{
		return View::make('common.login-signup-form');
	}
}
