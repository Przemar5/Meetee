<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;

class User extends Entity
{
	private string $table = 'users';
	private int $id;
	private string $username;
	private string $password;

	public static function current(): ?User;

	public function login(): void;

	public function logout(): void;

	public function softDelete(): void;

	public function delete(): void;

	public function setId(int $id): void;

	public function setUsername(string $username): void;

	public function setPassword(string $password): void;

	public function getId(): ?int;

	public function getUsername(): string;

	public function getPassword(): string;
}