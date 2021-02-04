<?php

namespace Meetee\App\Entities;

use Meetee\Libs\Database\Tables\TableTemplate;

abstract class Entity
{
	protected ?int $id;

	public function setId(int $id): void
	{
		if (empty($this->id))
			$this->id = $id;
	}

	public function getId(): ?int
	{
		return $this->id ?? null;
	}
}