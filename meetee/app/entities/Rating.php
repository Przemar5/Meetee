<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;

class Rating extends Entity
{
	private string $table;
	private ?int $id;
	private string $iconPath;

	public function getTable(): string;

	public function setId(int $id): void;

	public function setIconPath(string $path): void;

	public function getId(): ?int;

	public function getIconPath(): string;
}