<?php

namespace Meetee\Libs\Http\Routing\Data\Structures;

use Meetee\Libs\Data_structures\MappedCollection;
use Meetee\Libs\Http\Routing\Data\Structures\Iterators\RouteListIterator;

class RouteList implements MappedCollection
{
	private array $items;

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
}