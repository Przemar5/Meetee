<?php

namespace Meetee\Libs\Data_structures;

use Meetee\Libs\Data_structures\Collection;

interface MappedCollection extends Collection
{
	public function set($key, $value): void;

	public function get($key);

	public function contains($key): bool;
}