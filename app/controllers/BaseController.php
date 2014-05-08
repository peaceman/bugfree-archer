<?php
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Response;
use Illuminate\View\Environment as ViewFactory;

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

		$this->beforeFilter(function () {
			Event::fire('clockwork.controller.start');
		});

		$this->afterFilter(function () {
			Event::fire('clockwork.controller.end');
		});
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
