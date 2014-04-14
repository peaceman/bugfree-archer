<?php
namespace EDM\Controllers;

use BaseController;
use EDM\ControllerTraits;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Response;
use Illuminate\View\Environment as ViewFactory;

class AuthenticatedBaseController extends \BaseController
{
	use ControllerTraits\RequireAuthenticatedUser;

	public function __construct(Response $response, Redirector $redirector, Request $request, ViewFactory $view)
	{
		parent::__construct($response, $redirector, $request, $view);
		$this->beforeFilter('@fetchAuthenticatedUser');
	}
}
