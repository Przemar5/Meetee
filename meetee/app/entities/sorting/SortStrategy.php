<?php

namespace Meetee\App\Entities\Sorting;

interface SortStrategy
{
	public function sort(array $items): array;
}