<?php

namespace Meetee\Libs\Http\Routing\Data\Structures\Iterators;

use Meetee\Libs\Data_structures\Iterators\Iterator;
use Meetee\Libs\Http\Routing\Data\Structures\RouteList;
use Meetee\Libs\Http\Routing\Data\Route;

class RouteListIterator extends Iterator
{
	public function __construct(RouteList $routes)
	{
		$this->collection = $routes;
	}

	public function getRouteByName(string $name): ?Route
	{
		$this->rewind();

		while ($this->valid()) {
			$route = $this->current();

			if ($route->getName() === $name)
				return $route;
		}
	}

	public function getRouteByUriAndMethod(string $uri, string $method): ?Route
	{
		$this->rewind();

		while ($this->valid()) {
			$route = $this->current();

			if ($route->matchByUriAndMethod($uri, $method))
				return $route;
		}
	}

	public function rewind(): void
	{
		$this->position = 0;
	}

	public function current(): mixed
	{
		return $this->collection->getItems()[$this->position];
	}

	public function next(): void
	{
		$this->position++;
	}

	public function hasNext(): bool
	{
		return isset($this->collection->getItems()[$this->position + 1]);
	}

	public function valid(): bool
	{
		return isset($this->collection->getItems()[$this->position]);
	}
}