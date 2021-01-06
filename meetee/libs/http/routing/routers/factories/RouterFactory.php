<?php

namespace Meetee\Libs\Http\Routing\Routers\Factories;

use Meetee\Libs\Http\Routing\Routers\RouterTemplate;
use Meetee\Libs\Http\Routing\Routers\RouterGuardProxy;
use Meetee\Libs\Http\Routing\Routers\RouterLoggerProxy;
use Meetee\Libs\Files\Factories\LoggerFactory;

class RouterFactory
{
	public static function createComplete(): RouterTemplate
	{
		$router = new RouterTemplate();
		$guard = new RouterGuardProxy($router);
		$loggerRouter = new RouterLoggerProxy($guard);
		$logger = LoggerFactory::createRequestLogger();
		$loggerRouter->setLogger($logger);

		return $loggerRouter;
	}
}