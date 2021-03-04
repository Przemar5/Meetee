<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;

class Role extends Entity
{
	public string $name;

	public function __toString()
	{
		return $this->name;
	}
}