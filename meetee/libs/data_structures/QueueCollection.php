<?php

namespace Meetee\Libs\Data_structures\Collection;

class QueueCollection implements Collection
{
	public function push(mixed $element): void;

	public function pop(mixed $element): mixed;
}