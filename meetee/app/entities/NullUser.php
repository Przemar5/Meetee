<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\User;

class NullUser extends User
{
	public function __construct()
	{
		parent::__construct();
		$this->roles = [Role::getGuestRole()];
		$this->id = 0;
	}
}