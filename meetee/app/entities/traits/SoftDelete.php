<?php

namespace Meetee\App\Entities\Traits;

trait SoftDelete
{
	protected bool $deletedAt;

	public function setDeletedAt(): void
	{
		$this->deletedAt = true;
	}

	public function getDeletedAt(): ?bool
	{
		return $this->deletedAt;
	}
}