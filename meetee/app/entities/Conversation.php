<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;

class Conversation extends Entity
{
	use Timestamps;
	use SoftDelete;

	private ?int $id = null;
	

	public function setId(int $id): void
	{
		if (isset($this->id) || is_null($this->id))
			$this->id = $id;
	}

	public function getId(): ?int
	{
		return $this->id;
	}
}