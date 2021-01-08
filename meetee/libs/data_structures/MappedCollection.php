<?php

namespace Meetee\Libs\Data_structures;

use Meetee\Libs\Data_structures\Collection;

abstract class MappedCollection extends Collection
{
	abstract public function set($key, $value): void;

	abstract public function get($key);

	abstract public function contains($key): bool;
}