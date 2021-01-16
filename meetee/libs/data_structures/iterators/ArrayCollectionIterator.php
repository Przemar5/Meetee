<?php

namespace Meetee\Libs\Data_structures\Iterators;

use Meetee\Libs\Data_structures\Iterators\Iterator;
use Meetee\Libs\Data_structures\ArrayCollection;

class ArrayCollectionIterator implements Iterator
{
	public function __construct(ArrayCollection $collection)
	{
		$this->collection = $collection;
		$this->position = 0;
	}

	public function find($value)
	{
		$this->rewind();

		while ($this->valid()) {
			if ($this->current() === $value)
				return $this->current();
		}
		
		return null;
	}

	public function rewind(): void
	{
		$this->position = 0;
	}

	public function current()
	{
		return $this->collection->getItems()[$this->position];
	}

	public function next(): void
	{
		$this->position++;
	}

	public function hasNext(): bool
	{
		return isset($this->collection->getItems()[$this->position+1]);
	}

	public function valid(): bool
	{
		return isset($this->collection->getItems()[$this->position]);
	}
}