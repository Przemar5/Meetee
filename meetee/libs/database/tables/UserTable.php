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
		$data = $this->getRawDataForUserId($id);
		
		if (!$data)
			return null;

		$user = $this->insertDataIntoUser($data);

		return $user;
	}

	private function getRawDataForUserId(int $id)
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select(['*']);
		$this->queryBuilder->where(['id' => $id]);
		$this->queryBuilder->whereNull(['deleted']);

		return $this->database->findOne(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	private function insertDataIntoUser($data): User
	{
		$user = new User();
		$user->setId($data['id']);
		$user->setLogin($data['login']);
		$user->setEmail($data['email']);
		$user->setName($data['name']);
		$user->setSurname($data['surname']);
		$user->setPassword($data['password']);
		$user->setCreatedAt($data['created_at']);
		$user->setUpdatedAt($data['updated_at']);
		$user->setDeleted($data['deleted']);

		$userRole = new UserRoleTable();
		$user->setRoles($userRole->getRolesByUserId($data['id']));

		return $user;
	}

	public function findByLogin(string $login): ?User
	{
		$data = $this->getDataByLogin($login);

		if (!$data)
			return null;

		$user = $this->insertDataIntoUser($data);

		return $user;
	}

	private function getDataByLogin(string $login)
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select(['*']);
		$this->queryBuilder->where(['login' => $login]);
		$this->queryBuilder->limit(1);

		return $this->database->findOne(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
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
		$data = [];
		$data['username'] = $user->getUsername();
		$data['password'] = $user->getPassword();

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

	public function update(User $user): void
	{
		$values = [];
		$values['username'] = $user->getUsername();
		$values['password'] = $user->getPassword();

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