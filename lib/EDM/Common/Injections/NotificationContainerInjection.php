<?php
namespace EDM\Common\Injections;

use Krucas\Notification\Notification;
use Krucas\Notification\NotificationsBag;

trait NotificationContainerInjection
{
	/**
	 * @var NotificationsBag
	 */
	protected $notification;

	public function injectNotificationContainer(Notification $notificationContainer)
	{
		$this->notification = $notificationContainer;
	}
}
