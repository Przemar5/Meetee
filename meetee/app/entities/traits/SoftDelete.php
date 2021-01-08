<?php

namespace Meetee\App\Entities\Traits;

trait SoftDelete
{
	protected ?bool $deleted;

	public function setDeleted(?bool $deleted): void
	{
		$this->deleted = $deleted;
	}

	public function isDeleted(): ?bool
	{
		return $this->deleted ?? false;
	}

	public function softDelete(): void
	{
		$this->deleted = true;
	}
}