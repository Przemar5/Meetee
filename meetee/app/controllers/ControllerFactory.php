<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\ControllerTemplate;
use Meetee\App\Controllers\ErrorController;
use Meetee\Libs\View\Factories\ViewFactory;

class ControllerFactory
{
	public static function createFromClassNameForBrowser(string $class): ControllerTemplate
	{
		$class = sprintf("\Meetee\App\Controllers\%s", $class);

		if (!class_exists($class))
			throw new \Exception(sprintf("Class '%s' not found.", $class));

		$view = ViewFactory::createDefaultBrowserView();

		return new $class($view);
	}

	public static function createErrorControllerForBrowser(): ErrorController
	{
		$view = ViewFactory::createDefaultBrowserView();

		return new ErrorController($view);
	}
}