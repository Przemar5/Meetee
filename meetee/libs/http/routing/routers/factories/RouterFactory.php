<?php

namespace Meetee\Libs\Http\Routing\Routers\Factories;

use Meetee\Libs\Http\Routing\Routers\RouterTemplate;
use Meetee\Libs\Http\Routing\Routers\RouterGuardProxy;
use Meetee\Libs\Http\Routing\Routers\RouterLoggedUserGuardProxy;
use Meetee\Libs\Http\Routing\Routers\RouterGuestGuardProxy;
use Meetee\Libs\Http\Routing\Routers\RouterLoggerProxy;
use Meetee\Libs\Files\Factories\LoggerFactory;

class RouterFactory
{
	public static function createComplete(): RouterTemplate
	{
		$router = new RouterTemplate();
		$guard = new RouterGuardProxy($router);
		$guestGuard = new RouterGuestGuardProxy($guard);
		$loggedGuard = new RouterLoggedUserGuardProxy($guestGuard);
		$loggerRouter = new RouterLoggerProxy($loggedGuard);
		$logger = LoggerFactory::createRequestLogger();
		$loggerRouter->setLogger($logger);

		return $loggerRouter;
	}
}