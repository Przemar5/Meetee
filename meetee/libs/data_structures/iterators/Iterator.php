<?php

namespace Meetee\Libs\Data_structures\Iterators;

use Meetee\Libs\Data_structures\Collection;

abstract class Iterator
{
	protected Collection $collection;
	protected int $position;

	public function __construct(Collection $collection)
	{
		$this->collection = $collection;
	}

	abstract public function rewind(): void;

	abstract public function current(): mixed;

	abstract public function next(): void;

	abstract public function hasNext(): bool;

	abstract public function valid(): bool;
}