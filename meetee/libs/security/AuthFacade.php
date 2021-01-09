<?php

namespace Meetee\Libs\Security;

use Meetee\App\Entities\User;
use Meetee\App\Entities\NullUser;
use Meetee\Libs\Storage\Session;

class AuthFacade
{
	public static function getUser(): ?User
	{
		$id = Session::get('Bs7Kf05jenMIft42Aj8');

		if (!preg_match('/^[1-9][0-9]*$/', $id))
			return new NullUser();

		return User::find($id) ?? new NullUser();
	}
}