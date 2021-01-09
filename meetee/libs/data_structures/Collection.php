<?php

namespace Meetee\Libs\Data_structures;

use Meetee\Libs\Data_structures\Iterators\Iterator;

abstract class Collection
{
	protected array $items;

	public function __construct(array $items)
	{
		$this->items = $items;
	}

	abstract public function getIterator(): Iterator;

	abstract public function getItems(): array;

	abstract public function count(): int;

	abstract public function empty(): bool;

	abstract public function push($element): void;

	abstract public function pop();
}