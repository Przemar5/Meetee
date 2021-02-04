<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\Entity;
use Meetee\Libs\Database\Tables\CountryTable;

class Country extends Entity
{
	public string $name;

	public function __toString()
	{
		return $this->name;
	}
}