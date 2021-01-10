<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Role;
use Meetee\Libs\Database\Tables\Pivots\UserRoleTable;

class RoleTable extends TableTemplate
{
	public function __construct()
	{
		parent::__construct('users', false);
	}

	protected function fetchEntity($data): Role
	{
		$role = new Role();
		$role->setId($data['id']);
		$role->setName($data['name']);

		return $role;
	}

	protected function getEntityData(Entity $role): array
	{
		$this->entityIsOfClassOrThrowException($role, Role::class);

		$data = [];
		$data['id'] = $role->getId();
		$data['name'] = $role->getName();

		return $data;
	}
}