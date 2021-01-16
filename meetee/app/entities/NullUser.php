<?php

namespace Meetee\App\Entities;

use Meetee\App\Entities\User;
use Meetee\App\Entities\Factories\RoleFactory;

class NullUser extends User
{
	public function __construct()
	{
		parent::__construct();
		$this->roles = [RoleFactory::createGuestRole()];
		$this->id = 0;
	}

	public function save(): void
	{
		return;
	}
}