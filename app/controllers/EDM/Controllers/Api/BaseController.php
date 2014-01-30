<?php
namespace EDM\Controllers\Api;

use EDM\ControllerTraits;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\View\Environment as ViewFactory;

class BaseController extends \BaseController
{
	use ControllerTraits\RequireAuthenticatedUser;

	/**
	 * @var \Illuminate\Support\Facades\Response
	 */
	protected $response;

	/**
	 * @var \Illuminate\Http\Request
	 */
	protected $request;

	/**
	 * @var \Illuminate\View\Environment
	 */
	protected $views;

	public function __construct(Response $response, Request $request, ViewFactory $view)
	{
		$this->response = $response;
		$this->request = $request;
		$this->views = $view;

		$this->beforeFilter('@fetchAuthenticatedUser');
	}
}
