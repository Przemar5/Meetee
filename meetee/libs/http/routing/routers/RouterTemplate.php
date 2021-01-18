<?php

namespace Meetee\Libs\Http\Routing\Routers;

use Meetee\Libs\Http\Routing\Data\Structures\RouteList;
use Meetee\Libs\Http\Routing\Data\Structures\RouteListFactory;
use Meetee\Libs\Http\Routing\Data\Route;
use Meetee\Libs\Http\Routing\Data\RouteFactory;
use Meetee\Libs\Http\Routing\RoutingFacade;
use Meetee\App\Controllers\ControllerFactory;

class RouterTemplate
{
	public function route(): void
	{
		$route = RouteFactory::getCurrentRouteOrThrowException();

		if (is_null($route))
			$this->renderNotFoundErrorPage();
		else
			$this->callControllerMethod(
				$route->getClassName(), 
				$route->getArgsForUri($_SERVER['PATH_INFO'] ?? '')
			);
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

	protected function callControllerMethod(
		string $classAndMethod, 
		?array $args = []
	): void
	{
		[$class, $method] = explode('.', $classAndMethod);
		$controller = ControllerFactory::createFromClassNameForBrowser($class);

		call_user_func_array([$controller, $method], $args);
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
		die;
	}

	public function redirectTo(
		string $routeName, 
		?array $args = [], 
		?array $headers = []
	): void
	{
		$uri = RoutingFacade::getRouteUriByName($routeName, $args);
		$method = RoutingFacade::getRouteMethodByName($routeName);

		if (strcasecmp($method, 'POST') === 0 || 
			!strcasecmp($method, $_SERVER['REQUEST_METHOD']) === 0)
			throw new \Exception(sprintf(
				"Route has different request method than '%s'.", 
				$_SERVER['REQUEST_METHOD']));

		$uri = BASE_URI . ltrim($uri, '/');

		$this->redirect($uri, $headers);
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
		$this->callControllerMethod('ErrorController.accessRestricted');
	}

	protected function renderNotFoundErrorPage(): void
	{
		$this->callControllerMethod('ErrorController.notFound');
	}
}