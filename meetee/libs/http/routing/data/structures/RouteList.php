<?php

namespace Meetee\Libs\Http\Routing\Data\Structures;

use Meetee\Libs\Data_structures\MappedCollection;
use Meetee\Libs\Http\Routing\Data\Structures\Iterators\RouteListIterator;
use Meetee\Libs\Http\Data\Route;

class RouteList extends MappedCollection
{
	protected array $items;

	public function __construct(array $items)
	{
		$this->items = $items;
	}

	public function getIterator(): RouteListIterator
	{
		return new RouteListIterator($this);
	}

	public function getItems(): array
	{
		return $this->items;
	}

	public function count(): int
	{
		return count($this->items);
	}

	public function empty(): bool
	{
		return empty($this->items);
	}

	public function set($key, $value): void
	{
		$this->items[$key] = $value;
	}

	public function get($key)
	{
		return $this->items[$key];
	}

	public function contains($key): bool
	{
		return isset($this->items[$key]);
	}

	public function push($route): void
	{
		$this->items[] = $route;
	}

	public function pop()
	{
		return array_pop($this->items);
	}
}