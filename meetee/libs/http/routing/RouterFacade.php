<?php

namespace Meetee\Libs\Http\Routing;

use Meetee\Libs\Http\Routing\Routers\Factories\RouterFactory;
use Meetee\Libs\Http\Routing\Data\Structures\RouteListFactory;
use Meetee\Libs\Security\Validators\RequestMethodValidator;
use Meetee\Libs\Security\Sanitizers\UriSanitizer;

class RouterFacade
{
	public static function route(): void
	{
		$routes = RouteListFactory::createFromJsonConfig('./config/routes.json');
		$iterator = $routes->getIterator();
		$route = static::getSanitizedUri();

		while ($iterator->valid()) {
			$iterator->current()->matchByUriAndMethod(
				$route, $_SERVER['REQUEST_METHOD']
			);
			$iterator->next();
		}
	}

	public static function routeToCurrentUri(): void
	{
		$router = RouterFactory::createComplete();
		$route = static::getRouteName();
		$router->route($route);
	}

	private static function getRouteName(): ?string
	{
		$routes = RouteListFactory::createFromJsonConfig('./config/routes.json');
		static::throwExceptionIfRequestMethodNotValid();
		$uri = static::getSanitizedUri();
		$iterator = $routes->getIterator();
		$routeName = $iterator->getRouteNameByUriAndMethod(
			$uri, $_SERVER['REQUEST_METHOD']);

		return $routeName;
	}

	private static function throwExceptionIfRequestMethodNotValid(): void
	{
		$validator = new RequestMethodValidator();

		if (!$validator->run($_SERVER['REQUEST_METHOD']))
			throw new \Exception(
				sprintf("Invalid request method '%s'.", $_SERVER['REQUEST_METHOD']));
	}

	private static function getSanitizedUri(): string
	{
		return UriSanitizer::run($_SERVER['PATH_INFO']);
	}

	private static function throwErrorUfRouteIsNull($route): void
	{
		if (is_null($route))
			throw new \Exception(
				sprintf("Cannot find the route for '%s' uri and method '%s'.", 
					$uri, $_SERVER['REQUEST_METHOD']));
	}
}