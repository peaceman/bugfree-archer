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

		$this->app->bind(
			\EDM\PayPal\Processors\StartPayment::class,
			function ($app) {
				$paymentsService = $app->make(AdaptivePaymentsService::class);
				$config = $app->config->get('paypal.app');

				return new \EDM\PayPal\Processors\StartPayment($paymentsService, $config);
			}
		);
	}
}
