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

	public function find(int $id): ?Role
	{
		return $this->findOneWhere(['id' => $id]);
	}

	public function findOneWhere(array $conditions): ?Role
	{
		$data = $this->getRawDataForRoleWhere($conditions);
		
		if (!$data)
			return null;

		$role = $this->fetchRole($data);

		return $role;
	}

	public function findManyWhere(array $conditions)
	{
		$data = $this->getRawDataForManyRolesWhere($conditions);
		
		if (!$data)
			return null;

		$roles = $this->fetchManyRoles($data);

		return $roles;
	}

	private function getRawDataForRoleWhere(array $conditions)
	{
		$this->prepareSelectWhere($conditions);
		$this->queryBuilder->limit(1);

		return $this->database->findOne(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	private function getRawDataForManyRolesWhere(array $conditions)
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
	}

	private function fetchRole($data): Role
	{
		$role = new Role();
		$role->setId($data['id']);
		$role->setName($data['name']);

		return $role;
	}

	private function fetchManyRoles($data)
	{
		return array_map(fn($d) => $this->fetchRole($d), $data);
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