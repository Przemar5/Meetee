<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Traits\Timestamps;
use Meetee\App\Entities\Traits\SoftDelete;

class Tag extends Entity
{
	use Timestamps;
	use SoftDelete;

	private string $table;
	private ?int $id;
	private string $name;

	public function setId(int $id): void;

	public function setName(string $name): void;

	public function getId(): ?int;

	public function getName(): string;
}