<?php

namespace Meetee\App\Entities\Traits;

trait SoftDelete
{
	public ?bool $deleted = null;

	public function setDeleted(?int $deleted): void
	{
		if ($deleted === 0 || $deleted === 1 || is_null($deleted))
			$this->deleted = $deleted;
	}

	public function isDeleted(): ?bool
	{
		return $this->deleted;
	}

	public function softDelete(): void
	{
		$this->deleted = 1;
		$this->save($this);
	}
}