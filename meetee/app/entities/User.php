<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\Libs\Database\DatabaseMediator;

class User extends Entity
{
	private string $table = 'users';
	private ?int $id = null;
	private string $username;
	private string $password;

	public static function current(): ?static
	{
		return null;
		// DatabaseMediator::find();
	}

	public function hasAccess(): bool
	{
		return true;
	}

	public function login(): void
	{
		//
	}

	public function logout(): void
	{
		//
	}

	public function softDelete(): void
	{
		//
	}

	public function delete(): void
	{
		//
	}

	public function setId(int $id): void
	{
		$this->id = $id;
	}

	public function setUsername(string $username): void
	{
		$this->username = $username;
	}

	public function setPassword(string $password): void
	{
		$this->password = $password;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function getPassword(): string
	{
		return $this->password;
	}
}