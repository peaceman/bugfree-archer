<?php
namespace EDM\Common\Injections;

trait LogWriterInjection
{
	/**
	 * @var \Illuminate\Log\Writer
	 */
	protected $log;

	/**
	 * @param \Illuminate\Log\Writer $log
	 */
	public function injectLogWriter(\Illuminate\Log\Writer $log)
	{
		$this->log = $log;
	}
}
