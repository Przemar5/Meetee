<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\Table;
use Meetee\App\Entities\Role;
use Meetee\Libs\Database\Tables\Pivots\UserRoleTable;

class RoleTable extends Table
{
	public function __construct()
	{
		parent::__construct('users');
	}

	public function find(int $id): ?User
	{
		$data = $this->getRawDataForRoleId($id);
		
		if (is_null($data))
			return null;

		$role = new Role();
		$role->setId($data['id']);
		$role->setName($data['name']);

		return $role;
	}

	private function getRawDataForRoleId(int $id)
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

	public function save(Role $role): void
	{
		if (is_null($user->getId()))
			$this->insert($user);
		else
			$this->update($user);
	}

	public function insert(Role $role): void
	{
		$name = $role->getName();

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->insert(['name' => $name]);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	public function update(Role $role): void
	{
		$name = $role->getName();

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->update(['name' => $name]);
		$this->queryBuilder->where(['id' => $user->getId()]);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}
}