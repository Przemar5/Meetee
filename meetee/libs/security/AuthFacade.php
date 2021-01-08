<?php

namespace Meetee\Libs\Security;

use Meetee\App\Entities\User;
use Meetee\Libs\Storage\Session;

class AuthFacade
{
	public static function getUser(): ?User
	{
		$id = Session::get('user_id');
		$user = new User();

		return $user;
	}
}