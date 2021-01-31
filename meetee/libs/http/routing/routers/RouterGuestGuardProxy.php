<?php

namespace Meetee\Libs\Http\Routing\Routers;

use Meetee\Libs\Http\Routing\Routers\RouterTemplate;
use Meetee\Libs\Http\Routing\Data\RouteFactory;
use Meetee\Libs\Security\AuthFacade;
use Meetee\Libs\View\Utils\Notification;

class RouterGuestGuardProxy extends RouterTemplate
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
		elseif ($user->hasRole('GUEST') && $route->accepts('VERIFIED') && 
			!$user->hasAccess($route)) {
			
			Notification::addSuccess(
				'You must be logged in to access this page!');
			$this->redirectTo('login_page');
		}
		else {
			$this->router->route();
		}
	}
}