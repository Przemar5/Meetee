<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\App\Entities\User;
use Meetee\Libs\Utils\RandomStringGenerator;

class Token extends Entity
{
	protected string $table;
	protected ?int $id;
	protected string $name;
	protected string $value;
	protected int $userId;

	public function __construct()
	{
		parent::__construct('tokens');
	}

	public static function generate(string $name): self
	{
		$user = AuthFacade::getUser();
		$token = new static();
		$token->setName($name);
		$token->setValue(RandomStringGenerator::generateHex(64));
		$token->setUserId();

		return $token;
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
}