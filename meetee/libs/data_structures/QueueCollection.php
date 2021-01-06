<?php

namespace Meetee\Libs\Data_structures\Collection;

class QueueCollection implements Collection
{
	public function push($element): void;

	public function pop($element);
}