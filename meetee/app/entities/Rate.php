<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;

class Rate extends Entity
{
	private string $table;
	private ?int $id;
	private string $name;

	public function setId(int $id): void
	{
		if (!isset($this->id))
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