<?php

namespace Meetee\Libs\Database\Tables\Pivots;

use Meetee\Libs\Database\Tables\Pivots\Pivot;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Role;

class UserRoleTable extends Pivot
{
	public function __construct()
	{
		parent::__construct('user_role');
	}

	public function getRolesByUserId(int $id): ?array
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->select(['roles.*']);
		$this->queryBuilder->in('roles');
		$this->queryBuilder->innerJoin('user_role');
		$this->queryBuilder->on(['user_role.role_id = roles.id']);
		$this->queryBuilder->where(['user_role.user_id = :user_id']);
		$this->queryBuilder->setAdditionalBindings([':user_id' => $id]);

		$data = $this->database->findMany(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);

		return $this->createManyRolesFromRawData($data);
	}

	private function createManyRolesFromRawData(array $data): array
	{
		$roles = [];

		foreach ($data as $roleData)
			$roles[] = $this->createRoleFromRawData($roleData);

		return $roles;
	}

	private function createRoleFromRawData(array $data): Role
	{
		$role = new Role();
		$role->setId($data['id']);
		$role->setName($data['name']);

		return $role;
	}

	public function setRolesForUser(User $user): void
	{
		$roles = $user->getRoles();
		$roleIds = array_map(fn($r) => $r->getId(), $roles);
		$roleIds = array_filter(fn($id) => !is_null($id), $roleIds);
		$userId = $$user->getId();
		
		if (empty($roleIds) || is_null($userId))
			return;


	}

	private function addRolesForUser(User $user): void
	{
		$this->queryBuilder->in($this->table);
		$this->queryBuilder->insert();
		$this->queryBuilder->in($this->table);
		$this->queryBuilder->in($this->table);

	}

	private function removePreviousRolesForUserId(int $id): void
	{
		$this->queryBuilder->in($this->table);
		$this->queryBuilder->delete();
		$this->queryBuilder->where(['user_id' => $id]);
		
		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}
}