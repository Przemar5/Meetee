<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\App\Entities\User;

class Token extends Entity
{
	private string $table;
	private ?int $id;
	private string $name;
	private string $value;
	private User $user;

	public function setId(int $id): void;

	public function setName(string $name): void;

	public function setValue(string $value): void;

	public function setUser(User $user): void;

	public function getId(): ?int;

	public function getName(): string;

	public function getValue(): string;

	public function getUser(): User;
}