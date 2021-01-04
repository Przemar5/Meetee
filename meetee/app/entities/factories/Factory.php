<?php

namespace Meetee\App\Factories;

use Meetee\App\Entities\Entity;

interface Factory
{
	public static function create(): Entity;
}