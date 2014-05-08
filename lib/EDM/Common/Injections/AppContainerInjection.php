<?php
namespace EDM\Common\Injections;

trait AppContainerInjection
{
	/**
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app;

	public function injectAppContainer(\Illuminate\Foundation\Application $appContainer)
	{
		$this->app = $appContainer;
	}
}
