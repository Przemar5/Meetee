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
		parent::__construct('users', true);
	}

	protected function fetchEntity($data): User
	{
		$user = new User();
		$user->setId($data['id']);
		$user->setLogin($data['login']);
		$user->setEmail($data['email']);
		$user->setName($data['name']);
		$user->setSurname($data['surname']);
		$user->setBirth(new \DateTime($data['birth']));
		$user->setPassword($data['password']);
		$user->setVerified($data['verified']);
		$user->setCreatedAt($data['created_at']);
		$user->setUpdatedAt($data['updated_at']);
		$user->setDeleted($data['deleted']);

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
		$this->entityIsOfClassOrThrowException($user, User::class);

		$data = [];
		$data['login'] = $user->getLogin();
		$data['name'] = $user->getName();
		$data['surname'] = $user->getSurname();
		$data['email'] = $user->getEmail();
		$data['birth'] = $user->getBirth()->format('Y-m-d H:i:s');
		$data['password'] = $user->getPassword();
		$data['verified'] = $user->isVerified();

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