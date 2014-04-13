<?php
namespace EDM\Controllers;

use BaseController;
use EDM\ControllerTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\Environment as ViewFactory;

class AuthenticatedBaseController extends \BaseController
{
	use ControllerTraits\RequireAuthenticatedUser;

	public function __construct(Response $response, Request $request, ViewFactory $view)
	{
		parent::__construct($response, $request, $view);
		$this->beforeFilter('@fetchAuthenticatedUser');
	}
}
