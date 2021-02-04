<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;

class Rate extends Entity
{
	private string $name;

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getName(): string
	{
		return $this->name;
	}
}