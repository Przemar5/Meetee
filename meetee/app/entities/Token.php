<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\TokenTable;
use Meetee\Libs\Utils\RandomStringGenerator;

class Token extends Entity
{
	protected ?int $id = null;
	protected string $name;
	protected string $value;
	protected int $userId;
	protected \DateTime $expires;

	public function __construct()
	{
		parent::__construct(new TokenTable());
	}

	public function pop(): ?Token
	{
		return $this->table->popValidByToken($this);
	}

	public function delete(): void
	{
		$this->table->delete($this);
	}

	public function setId(int $id): void
	{
		if (!isset($this->id))
			$this->id = $id;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function setValue(string $value): void
	{
		$this->value = $value;
	}

	public function setUserId(int $id): void
	{
		$this->userId = $id;
	}

	public function setExpiry($expires): void
	{
		if (is_string($expires)) {
			$this->expires = new \DateTime($expires);
		}
		elseif ($expires instanceof \DateTime) {
			$this->expires = $expires;
		}
		else {
			throw new \Exception(sprintf(
				"Expiry date must be a string or a DateTime object, '%s' given.", 
				gettype($expires)));
		}
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getValue(): string
	{
		return $this->value;
	}

	public function getUserId(): int
	{
		return $this->userId;
	}

	public function getExpiry(): \DateTime
	{
		return $this->expires;
	}

	public function getExpiryString(): string
	{
		return $this->expires->format('Y-m-d H:i:s');
	}
}