<?php
namespace EDM\Controllers\User;
use View;

class CustomerQuestionController extends UserBaseController
{
	public function getIndex()
	{
		return View::make('user.customer-questions.index');
	}
}
