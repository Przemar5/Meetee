<?php

namespace Meetee\App\Controllers;

use Meetee\App\Controllers\Controller;
use Meetee\Libs\View\BrowserView;

class ControllerFactory
{
	public static function createFromClassName(string $class): Controller
	{
		$class = sprintf("\\Meetee\\App\\Controllers\\%s", $class);

		if (!class_exists($class))
			throw new \Exception(sprintf("Class '%s' not found.", $class));

		return new $class(new BrowserView());
	}
}