<?php

namespace Meetee\Libs\Database\Factories;

use Meetee\Libs\Database\Database;

interface Factory
{
	public static function create(): Database;
}