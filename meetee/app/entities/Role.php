<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\Libs\Database\Tables\RoleTable;

class Role extends Entity
{
	public string $name;

	public function __toString()
	{
		return $this->name;
	}
}