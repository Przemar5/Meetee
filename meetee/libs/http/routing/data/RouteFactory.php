<?php

namespace Meetee\Libs\Http\Routing\Data;

use Meetee\Libs\Http\Routing\Data\Route;
use Meetee\Libs\Http\Routing\Data\Structures\RouteListFactory;
use Meetee\Libs\Http\CurrentRequestFacade;

class RouteFactory
{
	public static function getCurrentRouteOrThrowException(): ?Route
	{
		$uri = CurrentRequestFacade::getSanitizedUriIfValidOrThrowException();
		CurrentRequestFacade::throwExceptionIfRequestMethodInvalid();

		$routes = RouteListFactory::createFromJsonConfig('./config/routes.json');
		$iterator = $routes->getIterator();

		return $iterator->getRouteByUriAndMethod(
			$uri, $_SERVER['REQUEST_METHOD']);
	}
}