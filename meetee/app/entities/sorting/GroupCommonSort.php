<?php

namespace Meetee\App\Entities\Sorting;

use Meetee\App\Entities\Sorting\SortStrategy;

class GroupCommonSort implements SortStrategy
{
	public function sort(array $items): array
	{
		return $items;
	}
}