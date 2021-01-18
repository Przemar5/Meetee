<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\Libs\Database\Tables\RoleTable;

class Role extends Entity
{
	protected ?int $id = null;
	public string $name;

	public function __toString()
	{
		return $this->name;
	}

	public function setId(int $id): void
	{
		$this->id = $id;
	}

	public function getId(): ?int
	{
		return $this->id;
	}
}