<?php

namespace Meetee\Libs\Database\Tables\Pivots;

use Meetee\Libs\Database\Tables\Table;

abstract class Pivot extends Table
{
	public function __construct(string $name)
	{
		parent::__construct($name);
	}
}