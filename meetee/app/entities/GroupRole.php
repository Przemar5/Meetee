<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;

class GroupRole extends Entity
{
	public string $name;

	public function __toString()
	{
		return $this->name;
	}
}