<?php

namespace Meetee\Libs\Data_structures\Iterators;

use Meetee\Libs\Data_structures\Collection;

abstract class Iterator
{
	protected Collection $collection;
	protected int $position = 0;

	public function __construct(Collection $collection)
	{
		$this->collection = $collection;
		$this->position = 0;
	}

	// abstract public function find();

	abstract public function rewind(): void;

	abstract public function current();

	abstract public function next(): void;

	abstract public function hasNext(): bool;

	abstract public function valid(): bool;
}