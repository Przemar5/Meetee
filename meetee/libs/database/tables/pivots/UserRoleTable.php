<?php

namespace Meetee\Libs\Database\Tables\Pivots;

use Meetee\Libs\Database\Tables\Pivots\Pivot;
use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\User;
use Meetee\App\Entities\Role;

class UserRoleTable extends Pivot
{
	public function __construct()
	{
		parent::__construct('user_role');
	}

	public function findRolesForUserId(int $id): ?array
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->select(['roles.*']);
		$this->queryBuilder->in('roles');
		$this->queryBuilder->join([
			'user_role' => [
				'type' => 'INNER',
				'on' => 'user_role.role_id = roles.id',
			],
		]);
		$this->queryBuilder->where(['user_role.user_id' => $id]);
		// $this->queryBuilder->setAdditionalBindings([':user_id' => $id]);

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
		$role->name = $data['name'];

		return $role;
	}

	public function setRolesForUser(User $user): void
	{
		$roles = $user->getRoles();
		$roleIds = array_map(fn($r) => $r->getId(), $roles);
		$roleIds = array_filter($roleIds, fn($id) => !is_null($id));

		if (empty($user->getId()))
			return;

		$this->removeRolesForUser($user);
		$this->addRolesForUser($user);
	}

	private function addRolesForUser(User $user): void
	{
		$roles = $user->getRoles();

		if (empty($roles))
			return;

		$data = array_map(fn($r) => [
			'user_id' => $user->getId(), 
			'role_id' => $r->getId(),
		], $roles);

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->insertMultiple($data);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	private function removeRolesForUser(User $user): void
	{
		$id = $user->getId();

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->delete();
		$this->queryBuilder->where(['user_id' => $id]);
		
		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}
}