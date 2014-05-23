<?php
namespace EDM\Common;

use Illuminate\Support\ServiceProvider;

class ViewHelperServiceProvider extends ServiceProvider
{
	public function register()
	{
		/** @var \Illuminate\View\Environment $viewEnvironment */
		$viewEnvironment = $this->app['view'];
		$viewEnvironment->creator('*', \EDM\ResourceImage\UrlHelper::class);
	}
}