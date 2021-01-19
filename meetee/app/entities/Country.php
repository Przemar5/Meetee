<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\Libs\Database\Tables\CountryTable;

class Country extends Entity
{
	private ?int $id;
	public string $name;

	public function __toString()
	{
		return $this->name;
	}

	public function setId(int $id): void
	{
		if (!isset($this->id) || is_null($this->id))
			$this->id = $id;
	}

	public function getId(): ?int
	{
		return $this->id;
	}
}