<?php

namespace Meetee\Libs\Http\Routing\Routers;

use Meetee\Libs\Http\Routing\Routers\RouterTemplate;
use Meetee\Libs\Http\Routing\Data\RouteFactory;
use Meetee\Libs\Security\AuthFacade;

class RouterGuardProxy extends RouterTemplate
{
	private RouterTemplate $router;

	public function __construct(RouterTemplate $router)
	{
		$this->router = $router;
	}

	public function route(): void
	{
		$user = AuthFacade::getUser();
		$route = RouteFactory::getCurrentRouteOrThrowException();

		if (!is_null($route) && !is_null($user) && $user->hasAccess($route)) {
			$this->router->route();
		}
		else {
			$this->renderNoAccessErrorPage();
		}
	}

	public function setLogger(Logger $logger): void
	{
		$this->logger = $logger;
	}
}