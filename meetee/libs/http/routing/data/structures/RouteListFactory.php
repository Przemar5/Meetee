<?php

namespace Meetee\Libs\Http\Routing\Data\Structures;

use Meetee\Libs\Http\Routing\Data\Structures\RouteList;
use Meetee\Libs\Http\Routing\Data\Route;
use Meetee\Libs\Converters\Converter;

class RouteListFactory
{
	public static function createFromJsonConfig(string $path): RouteList
	{
		$content = file_get_contents($path);

		try {
			$routes = Converter::jsonToArray($content);
			$routes = array_map(fn($single) => self::createRoute($single), $routes);
		}
		catch (\Exception $e) {
			die($e->getMessage());
		}

		return new RouteList($routes);
	}

	private static function createRoute(array $data): ?Route
	{
		if (!isset($data['pattern']))
			throw new \Exception('Pattern for the route is missing.');

		elseif (!isset($data['method']))
			throw new \Exception('HTTP method for the route is missing.');

		elseif (!isset($data['class']))
			throw new \Exception('Controller class for the route is missing.');

		return new Route(
			$data['pattern'], 
			$data['method'], 
			$data['class'], 
			$data['name'] ?? null
		);
	}
}