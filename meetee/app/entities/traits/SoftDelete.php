<?php

namespace Meetee\App\Entities\Traits;

trait SoftDelete
{
	protected \DateTime $deletedAt;

	public function setDeletedAt(): void;

	public function getDeletedAt(): \DateTime;
}