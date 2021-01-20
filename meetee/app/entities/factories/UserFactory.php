<?php

namespace Meetee\App\Entities\Factories;

use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\UserTable;
use Meetee\Libs\Database\Tables\CountryTable;
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
		$user->secondName = trim($_POST['second_name']);
		$user->surname = trim($_POST['surname']);
		$user->setBirth(trim($_POST['birth']));
		
		$countryTable = new CountryTable();
		$user->country = $countryTable->find((int) $_POST['country']);
		$user->city = trim($_POST['city']);
		$user->zipCode = trim($_POST['zip']);
		$user->password = Hash::make(trim($_POST['password']));
		$user->addRole(RoleFactory::createUserRole());
		$table->save($user);
		$user->setId($table->lastInsertId());

		return $user;
	}

	public static function getByEmail(string $email): ?User
	{
		$table = new UserTable();

		return $table->findOneBy(['email' => $email]);
	}
} 