<?php

namespace Meetee\Libs\Data_structures;

use Meetee\Libs\Data_structures\Collection;
use Meetee\Libs\Data_structures\Iterators\ArrayCollectionIterator;

class ArrayCollection extends Collection
{
	public function __construct(array $items)
	{
		$this->items = $items;
	}

	public function getItems(): array
	{
		return $this->items;
	}

	public function getIterator(): ArrayCollectionIterator
	{
		return new ArrayCollectionIterator($this);
	} 

	public function count(): int
	{
		return count($this->items);
	}

	public function empty(): bool
	{
		return empty($this->items);
	}

	public function add($element): void
	{
		$this->items[] = $element;
	}
}