<?php
namespace EDM\PayPal\Processors;

use EDM\AbstractBaseProcessor;
use PayPal\Service\AdaptivePaymentsService;
use PayPal\Types\AP\PayRequest;
use PayPal\Types\AP\Receiver;
use PayPal\Types\AP\ReceiverList;

class StartPayment extends AbstractBaseProcessor
{
	/**
	 * @var AdaptivePaymentsService
	 */
	protected $paymentsService;

	/**
	 * @var array
	 */
	protected $config;

	public function __construct(AdaptivePaymentsService $paymentsService, array $config)
	{
		$this->paymentsService = $paymentsService;
		$this->config = $config;
	}

	public function process(array $data = null)
	{
		/** @var $shopOrder \ShopOrder */
		$shopOrder = $this->requireData($data, 'shop_order');

		$receiverList = $this->buildReceiverList($shopOrder);
		$payRequest = $this->buildPayRequest($receiverList);

		$payResponse = $this->paymentsService->Pay($payRequest);
		dd($payResponse);
	}

	protected function buildPayRequest(ReceiverList $receiverList)
	{
		$requestEnvelope = new \PayPal\Types\Common\RequestEnvelope('en_US');

		$payRequest = new PayRequest($requestEnvelope);
		$payRequest->actionType = 'PAY';
		$payRequest->currencyCode = 'EUR';
		$payRequest->memo = 'blubber aka techno viking is in tha house!';

		$payRequest->cancelUrl = route($this->requireData($this->config, 'cancel_url_route'));
		$payRequest->ipnNotificationUrl = route($this->requireData($this->config, 'ipn_url_route'));
		$payRequest->returnUrl = route($this->requireData($this->config, 'return_url_route'));

		$payRequest->feesPayer = $this->requireData($this->config, 'fees_payer');

		$payRequest->receiverList = $receiverList;
		return $payRequest;
	}

	protected function buildReceiverList(\ShopOrder $shopOrder)
	{
		$shopItemPrice = $shopOrder->shopItemRevision->price;
		$sellerPayment = $this->calculateSellerPayment($shopItemPrice);

		$receivers = [
			$this->buildSystemPaymentReceiver($shopItemPrice),
			$this->buildSellerPaymentReceiver($shopOrder->seller, $sellerPayment),
		];

		return new ReceiverList($receivers);
	}

	protected function buildSystemPaymentReceiver($shopItemPrice)
	{
		$receiver = $this->buildReceiver($shopItemPrice);
		$receiver->email = $this->requireData($this->config, 'system_paypal_receiver');
		$receiver->primary = true;

		return $receiver;
	}

	protected function buildReceiver($amount)
	{
		$receiver = new Receiver($amount);
		$receiver->paymentType = 'DIGITALGOODS';

		return $receiver;
	}

	protected function buildSellerPaymentReceiver(\User $seller, $sellerPayment)
	{
		$payoutDetail = $seller->payoutDetail;

		$receiver = $this->buildReceiver($sellerPayment);
		$receiver->email = $payoutDetail->paypal_email;

		return $receiver;
	}

	/**
	 * @param $shopItemPrice
	 * @return string
	 */
	protected function calculateSellerPayment($shopItemPrice)
	{
		$commissionPercentage = $this->requireData($this->config, 'commission_percentage');
		$sellerPayment = bcmul(bcsub(1, $commissionPercentage, 5), $shopItemPrice, 2);
		return $sellerPayment;
	}
} 
