<?php

namespace Meetee\App\Entities;

use Meetee\App\Entitites\Entity;

class Role extends Entity
{
	private ?int $id;
	private string $name;

	public function __toString()
	{
		return $this->name;
	}

	public function setId(int $id): void
	{
		$this->id = $id;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}
}