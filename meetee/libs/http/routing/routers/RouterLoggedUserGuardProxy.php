<?php

namespace Meetee\Libs\Http\Routing\Routers;

use Meetee\Libs\Http\Routing\Routers\RouterTemplate;
use Meetee\Libs\Http\Routing\Data\RouteFactory;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\View\Utils\Notification;

class RouterLoggedUserGuardProxy extends RouterTemplate
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
		if (is_null($route) || is_null($user)) {
			$this->renderNotFoundErrorPage();
		}
		elseif ($user->hasRole('VERIFIED') && $route->accepts('GUEST') && 
			!$user->hasAccess($route)) {

			Notification::addSuccess('You are already logged in!');
			$this->redirectTo('main_page');
		}
		else {
			$this->router->route();
		}
	}
}