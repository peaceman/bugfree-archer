<?php
namespace EDM\Controllers\User;
use View;

class CustomerQuestionController extends \BaseController
{
	public function getIndex()
	{
		return View::make('user.customer-questions.index');
	}
}
