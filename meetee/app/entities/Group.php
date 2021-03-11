<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\Libs\Database\Tables\GroupTable;
use Meetee\App\Entities\Traits\Timestamps;
use Meetee\App\Entities\Traits\SoftDelete;

class Group extends Entity
{
	use Timestamps;
	use SoftDelete;

	public string $name;
	public string $description;
	public bool $active = true;
	public string $background = 'groups/nophoto.jpg';
}