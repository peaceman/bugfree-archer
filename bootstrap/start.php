<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application;

/*
|--------------------------------------------------------------------------
| Detect The Application Environment
|--------------------------------------------------------------------------
|
| Laravel takes a dead simple approach to your application environments
| so you can just specify a machine name for the host that matches a
| given environment, then we will automatically detect it for you.
|
*/

$env = $app->detectEnvironment(function () {
	$env = getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'local';
	return $env;
});

/*
|--------------------------------------------------------------------------
| Bind Paths
|--------------------------------------------------------------------------
|
| Here we are binding the paths configured in paths.php to the app. You
| should not be changing these here. If you need to change these you
| may do so within the paths.php file and they will be bound here.
|
*/

$app->bindInstallPaths(require __DIR__ . '/paths.php');

/*
|--------------------------------------------------------------------------
| Load The Application
|--------------------------------------------------------------------------
|
| Here we will load this Illuminate application. We will keep this in a
| separate location so we can isolate the creation of an application
| from the actual running of the application with a given request.
|
*/

$framework = $app['path.base'] . '/vendor/laravel/framework/src';

require $framework . '/Illuminate/Foundation/start.php';

App::bind(User::class, function ($app) {
	return $app['auth']->user();
});

App::resolvingAny(function ($object) {
	if (!is_object($object)) {
		return;
	}

	$reflection = new ReflectionClass($object);
	$publicMethods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

	foreach ($publicMethods as $method) {
		if (!starts_with($method->name, 'inject')) {
			continue;
		}

		if ($method->getNumberOfRequiredParameters() !== 1) {
			continue;
		}

		list($parameter) = $method->getParameters();
		$parameterClass = $parameter->getClass();

		if (!$parameterClass) {
			continue;
		}

		$dependency = App::make($parameterClass->name);
		$method->invoke($object, $dependency);
	}
});

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
