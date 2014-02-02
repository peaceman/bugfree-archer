<?php
namespace EDM\Resource;

use Illuminate\Support\ServiceProvider;
use Config;
use Flow; 

class UploadServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->bind('Flow\ConfigInterface', function () {
			return new Flow\Config(Config::get('uploads.flow_config', []));
		});

		$this->app->bind('Flow\RequestInterface', function () {
			return new Flow\Request();
		});

		$this->app->bind('Flow\File', function () {
			return new Flow\File($this->app['Flow\ConfigInterface'], $this->app['Flow\RequestInterface']);
		});
	}
}