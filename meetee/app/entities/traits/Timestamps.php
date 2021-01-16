<?php

namespace Meetee\App\Entities\Traits;

trait Timestamps
{
	protected ?\DateTime $createdAt;
	protected ?\DateTime $updatedAt;

	public function setCreatedAt($date): void
	{
		if (is_null($date))
			$this->createdAt = null;
		elseif (is_string($date))
			$this->createdAt = new \DateTime($date);
		elseif ($date instanceof \DateTime)
			$this->createdAt = $date;
		else
			throw new \Exception(
				"This method accepts only string or '\DateTime' object");
	}

	public function setUpdatedAt($date): void
	{
		if (is_null($date))
			$this->createdAt = null;
		elseif (is_string($date))
			$this->updatedAt = new \DateTime($date);
		elseif ($date instanceof \DateTime)
			$this->updatedAt = $date;
		else
			throw new \Exception(
				"This method accepts only string or '\DateTime' object");
	}

	public function getCreatedAt(): ?\DateTime
	{
		return $this->createdAt ?? null;
	}

	public function getUpdatedAt(): ?\DateTime
	{
		return $this->updatedAt ?? null;
	}
}