<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;

class Relation extends Entity
{
	public string $name;

	public function __toString()
	{
		return $this->name;
	}
}