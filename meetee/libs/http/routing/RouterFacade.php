<?php

namespace Meetee\Libs\Http\Routing;

use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\Http\Routing\Data\Structures\RouteListFactory;

class RouterFacade
{


	public static function redirectToRouteNamed(string $name)
	{
		$router = RouterFactory::createComplete();
		$routes = RouteListFactory::createFromJsonConfig('./config/routes.json');
		$iterator = $routes->getIterator();
		$route = $iterator->getRouteByName('home');
	}
}