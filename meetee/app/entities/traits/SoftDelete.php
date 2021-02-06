<?php

namespace Meetee\App\Entities\Traits;

trait SoftDelete
{
	public bool $deleted = false;

	public function setDeleted($deleted): void
	{
		$this->deleted = (bool) $deleted;
	}

	public function isDeleted(): ?bool
	{
		return $this->deleted;
	}

	public function softDelete(): void
	{
		$this->deleted = true;
		$this->save($this);
	}
}