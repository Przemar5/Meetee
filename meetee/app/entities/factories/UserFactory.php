<?php

namespace Meetee\App\Entities\Factories;

use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\App\Entities\Factories\RoleFactory;
use Meetee\Libs\Security\Hash;

abstract class UserFactory
{
	public static function createAndSaveUserFromPostRequest(): User
	{
		$table = new UserTable();
		$user = new User();
		$user->login = trim($_POST['login']);
		$user->email = trim($_POST['email']);
		$user->name = trim($_POST['name']);
		$user->surname = trim($_POST['surname']);
		$user->setBirth(trim($_POST['birth']));
		$user->password = Hash::create(trim($_POST['password']));
		$user->addRole(RoleFactory::createUserRole());
		$table->save($user);

		return $user;
	}

	public static function getByEmail(string $email): ?User
	{
		return User::findOneWhere(['email' => $email]);
	}
} 