<?php
namespace EDM\PayPal;

use Illuminate\Support\ServiceProvider;
use PayPal\Service\AdaptivePaymentsService;

class AdaptivePaymentsServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->bind(
			AdaptivePaymentsService::class,
			function ($app) {
				$config = $app->config->get('paypal.service');
				return new AdaptivePaymentsService($config);
			}
		);
	}
}
