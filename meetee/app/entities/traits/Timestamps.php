<?php

namespace Meetee\App\Entities\Traits;

trait Timestamps
{
	protected \DateTime $createdAt;
	protected \DateTime $updatedAt;

	public function setCreatedAt(): void;

	public function setUpdatedAt(): void;

	public function getCreatedAt(): \DateTime;

	public function getUpdatedAt(): \DateTime;
}