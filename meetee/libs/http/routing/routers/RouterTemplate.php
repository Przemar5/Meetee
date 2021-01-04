<?php

namespace Meetee\Libs\Http\Routing\Routers;

use Meetee\Libs\Http\Routing\Data\Structures\RouteListFactory;
use Meetee\Libs\Http\Routing\Data\Structures\RouteList;
use Meetee\Libs\Http\Routing\Data\Route;
use Meetee\App\Controllers\ControllerFactory;

class RouterTemplate
{
	public function route(string $name): void
	{
		$route = $this->getRouteByName($name);

		if (is_null($route))
			throw new \Exception(sprintf("Route '%s' is missing.", $name));

		$this->callControllerMethod($route->getClassName());
	}

	protected function getRouteByName(string $name): Route
	{
		$routes = $this->getRoutes();
		$iterator = $routes->getIterator();
		
		return $iterator->getRouteByName($name);
	}

	protected function getRoutes(): RouteList
	{
		return RouteListFactory::createFromJsonConfig('./config/routes.json');
	}

	protected function callControllerMethod(string $classAndMethod): void
	{
		[$class, $method] = explode('.', $classAndMethod);
		$controller = ControllerFactory::createFromClassName($class);

		$controller->{$method}();
	}

	public function redirect(string $route, ?array $headers = []): void
	{
		if (!headers_sent()) {
			$headers['Location'] = $route;
			$this->sendHeaders($headers);
		}
		else {
			$this->renderRedirectionTags($route);
		}
	}

	protected function sendHeaders(array $headers): void
	{
		foreach ($headers as $header => $value)
			header(sprintf("%s: %s", $header, $value));
	}

	protected function renderRedirectionTags(string $route): void
	{
		echo sprintf('<script>window.location.href = %s</script>', $route);
		echo sprintf('<meta http-equiv="refresh" content="0; URL=\'%s\'"/>', 
				$route);
	}

	protected function renderNoAccessErrorPage(): void
	{
		//
	}

	protected function renderNotFoundErrorPage(): void
	{
		//
	}
}