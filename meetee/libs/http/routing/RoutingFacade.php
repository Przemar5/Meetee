<?php

namespace Meetee\Libs\Http\Routing;

use Meetee\Libs\Http\Routing\Data\RouteFactory;

class RoutingFacade
{
	public static function getRouteUriByName(
		string $name, 
		?array $args = []
	): ?string
	{
		$route = RouteFactory::getRouteByName($name);

		if (is_null($route))
			return null;

		return $route->getPreparedUri($args);
	}

	public static function getRouteMethodByName(string $name): ?string
	{
		$route = RouteFactory::getRouteByName($name);

		if (is_null($route))
			return null;

		return $route->getMethod();
	}

	public static function redirectTo(string $route, ?array $args = []): void
	{
		
	}

	public static function getLinkTo(string $route, ?array $args = []): ?string
	{
		$uri = self::getRouteUriByName($route, $args);

		return BASE_URI . ltrim($uri, '/');
	}
}