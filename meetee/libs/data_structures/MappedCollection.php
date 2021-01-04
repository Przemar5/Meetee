<?php

namespace Meetee\Libs\Data_structures;

use Meetee\Libs\Data_structures\Collection;

interface MappedCollection extends Collection
{
	public function set(mixed $key, mixed $value): void;

	public function get(mixed $key): mixed;

	public function contains(mixed $key): bool;
}