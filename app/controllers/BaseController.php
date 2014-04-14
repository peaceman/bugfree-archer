<?php
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\View\Environment as ViewFactory;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;

class BaseController extends Controller
{
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

	/**
	 * @var \Illuminate\Routing\Redirector
	 */
	protected $redirector;

	/**
	 * @param Response $response
	 * @param Illuminate\Routing\Redirector $redirector
	 * @param Request $request
	 * @param ViewFactory $view
	 */
	public function __construct(Response $response, Redirector $redirector, Request $request, ViewFactory $view)
	{
		$this->response = $response;
		$this->redirector = $redirector;
		$this->request = $request;
		$this->views = $view;
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if (!is_null($this->layout)) {
			$this->layout = View::make($this->layout);
		}
	}

}
