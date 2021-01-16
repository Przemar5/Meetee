<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\TokenTable;
use Meetee\Libs\Utils\RandomStringGenerator;

class Token extends Entity
{
	protected ?int $id = null;
	public string $name;
	public string $value;
	public int $userId;
	protected \DateTime $expiry;

	public function __construct()
	{
		parent::__construct(new TokenTable());
	}

	public function setId(int $id): void
	{
		if (is_null($this->id))
			$this->id = $id;
	}

	public function pop(): ?Token
	{
		return $this->table->popValidByToken($this);
	}

	public function delete(): void
	{
		$this->table->delete($this);
	}

	public function setExpiry($expiry): void
	{
		if (is_string($expiry)) {
			$this->expiry = new \DateTime($expiry);
		}
		elseif ($expires instanceof \DateTime) {
			$this->expiry = $expiry;
		}
		else {
			throw new \Exception(sprintf(
				"Expiry date must be a string or a DateTime object, '%s' given.", 
				gettype($expiry)));
		}
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getExpiry(): \DateTime
	{
		return $this->expiry;
	}

	public function getExpiryString(): string
	{
		return $this->expiry->format('Y-m-d H:i:s');
	}

	// public function isValid(): bool
	// {
		
	// }
}