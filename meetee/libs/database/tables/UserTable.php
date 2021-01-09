<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\Table;
use Meetee\App\Entities\User;
use Meetee\Libs\Database\Tables\Pivots\UserRoleTable;

class UserTable extends Table
{
	public function __construct()
	{
		parent::__construct('users');
	}

	public function find(int $id): ?User
	{
		return $this->findOneWhere(['id' => $id]);
	}

	public function findOneWhere(array $conditions): ?User
	{
		$data = $this->getRawDataForUserWhere($conditions);
		
		if (!$data)
			return null;

		$user = $this->fetchUser($data);

		return $user;
	}

	public function findManyWhere(array $conditions)
	{
		$data = $this->getRawDataForManyUsersWhere($conditions);
		
		if (!$data)
			return null;

		$users = $this->fetchManyUsers($data);

		return $users;
	}

	private function getRawDataForUserWhere(array $conditions)
	{
		$this->prepareSelectWhere($conditions);
		$this->queryBuilder->limit(1);

		return $this->database->findOne(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	private function getRawDataForManyUsersWhere(array $conditions)
	{
		$this->prepareSelectWhere($conditions);

		return $this->database->findMany(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	private function prepareSelectWhere(array $conditions): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select(['*']);
		$this->queryBuilder->where($conditions);
		$this->queryBuilder->whereNull(['deleted']);
	}

	private function fetchUser($data): User
	{
		$user = new User();
		$user->setId($data['id']);
		$user->setLogin($data['login']);
		$user->setEmail($data['email']);
		$user->setName($data['name']);
		$user->setSurname($data['surname']);
		$user->setBirth(new \DateTime($data['birth']));
		$user->setPassword($data['password']);
		$user->setCreatedAt($data['created_at']);
		$user->setUpdatedAt($data['updated_at']);
		$user->setDeleted($data['deleted']);

		$userRole = new UserRoleTable();
		$user->setRoles($userRole->findRolesForUserId($data['id']));

		return $user;
	}

	private function fetchManyUsers($data)
	{
		return array_map(fn($d) => $this->fetchUser($d), $data);
	}

	public function findByLogin(string $login): ?User
	{
		return $this->findOneWhere(['login' => $login]);
	}

	public function save(User $user): void
	{
		if (is_null($user->getId()))
			$this->insert($user);
		else
			$this->update($user);
	}

	public function insert(User $user): void
	{
		$data = $this->userToRawData($user);

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->insert($data);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);

		$user->setId($this->database->lastInsertId());

		$userRole = new UserRoleTable();
		$userRole->setRolesForUser($user);
	}

	private function userToRawData(User $user): array
	{
		$data = [];
		$data['login'] = $user->getLogin();
		$data['name'] = $user->getName();
		$data['surname'] = $user->getSurname();
		$data['email'] = $user->getEmail();
		$data['birth'] = $user->getBirth()->format('Y-m-d H:i:s');
		$data['password'] = $user->getPassword();

		return $data;
	}

	public function update(User $user): void
	{
		$values = $this->userToRawData($user);

		if (!is_null($user->isDeleted()))
			$values['deleted'] = $user->isDeleted();

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->update($values);
		$this->queryBuilder->where(['id' => $user->getId()]);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);

		$userRole = new UserRoleTable();
		$userRole->setRolesForUser($user);
	}
}