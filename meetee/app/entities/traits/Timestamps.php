<?php

namespace Meetee\App\Entities\Traits;

use Meetee\Libs\Utils\StringableDateTime;

trait Timestamps
{
	protected ?StringableDateTime $createdAt;
	protected ?StringableDateTime $updatedAt;

	public function __get(string $name): string
	{
		if ($name === 'createdAt')
			return $this->createdAt->format('Y-m-d H:i:s');
		elseif ($name === 'updatedAt')
			return $this->updatedAt->format('Y-m-d H:i:s');
	}

	public function __set(string $name, $value): void
	{
		if ($name === 'createdAt')
			$this->setCreatedAt($value);
		elseif ($name === 'updatedAt')
			$this->setUpdatedAt($value);
	}

	public function setCreatedAt($date): void
	{
		if (is_null($date))
			$this->createdAt = null;
		elseif (is_string($date))
			$this->createdAt = new StringableDateTime($date);
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
			$this->updatedAt = new StringableDateTime($date);
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

	public function getCreatedAtString(): string
	{
		return $this->createdAt->format('Y-m-d H:i:s');
	}

	public function getUpdatedAtString(): string
	{
		return $this->updatedAt->format('Y-m-d H:i:s');
	}
}