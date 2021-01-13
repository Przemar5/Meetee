<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\Pivots\UserRoleTable;

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
		$user->surname = $data['surname'];
		$user->setBirth(new \DateTime($data['birth']));
		$user->password = $data['password'];
		$user->verified = $data['verified'];
		$user->setCreatedAt($data['created_at']);
		$user->setUpdatedAt($data['updated_at']);
		$user->deleted = $data['deleted'];

		$userRole = new UserRoleTable();
		$user->setRoles($userRole->findRolesForUserId($data['id']));

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
		$data['surname'] = $user->surname;
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