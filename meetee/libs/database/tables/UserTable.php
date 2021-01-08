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
		
		if (is_null($data))
			return null;

		$user = new User();
		$user->setId($data['id']);
		$user->setUsername($data['username']);
		$user->setPassword($data['password']);
		$user->setCreatedAt($data['created_at']);
		$user->setUpdatedAt($data['updated_at']);
		$user->setDeleted($data['deleted']);

		$userRole = new UserRoleTable();
		$user->setRoles($userRole->getRolesByUserId($id));

		return $user;
	}

	private function getRawDataForUserId(int $id)
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select(['*']);
		$this->queryBuilder->where(['id' => $id]);

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