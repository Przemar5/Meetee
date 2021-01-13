<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Traits\Timestamps;
use Meetee\App\Entities\Traits\SoftDelete;

class Tag extends Entity
{
	use Timestamps;
	use SoftDelete;

	public string $table;
	public ?int $id;
	public string $name;
}