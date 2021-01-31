<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\Pivots\UserRoleTable;
use Meetee\Libs\Database\Tables\CountryTable;
use Meetee\Libs\Database\Tables\PostTable;

class UserTable extends TableTemplate
{
	public function __construct()
	{
		parent::__construct('users', User::class, true);
	}

	protected function fetchEntity($data): User
	{
		$user = new User();
		$user->setId($data['id']);
		$user->login = $data['login'];
		$user->email = $data['email'];
		$user->name = $data['name'];
		$user->secondName = $data['second_name'];
		$user->surname = $data['surname'];
		$user->setBirth(new \DateTime($data['birth']));
		$user->city = $data['city'];
		$user->zipCode = $data['zip'];
		$user->password = $data['password'];
		$user->verified = $data['verified'];
		$user->setCreatedAt($data['created_at']);
		$user->setUpdatedAt($data['updated_at']);
		$user->deleted = $data['deleted'];

		$table = new CountryTable();
		$user->country = $country = $table->find($data['country']);

		$table = new UserRoleTable();
		$user->setRoles($table->findRolesForUserId($data['id']));

		return $user;
	}

	public function findByLogin(string $login): ?User
	{
		return $this->findOneWhere(['login' => $login]);
	}

	protected function getEntityData(Entity $user): array
	{
		$this->throwExceptionIfInvalidClass($user, User::class);

		$data = [];
		$data['login'] = $user->login;
		$data['name'] = $user->name;
		$data['second_name'] = $user->secondName;
		$data['surname'] = $user->surname;
		$data['country'] = $user->country->getId();
		$data['city'] = $user->city;
		$data['zip'] = $user->zipCode;
		$data['email'] = $user->email;
		$data['birth'] = $user->getBirth()->format('Y-m-d H:i:s');
		$data['password'] = $user->password;
		$data['verified'] = $user->verified;

		return $data;
	}

	protected function updatePivots(Entity $user): void
	{
		$userRole = new UserRoleTable();
		$userRole->setRolesForUser($user);
	}

	public function findOneVerifiedWhere(array $conditions): array
	{
		$conditions['verified'] = 'TRUE';

		return $this->findOneWhere($conditions);
	}
}