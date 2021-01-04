<?php

namespace Meetee\Libs\Data_structures;

interface Collection
{
	public function getItems(): array;

	public function count(): int;

	public function empty(): bool;
}