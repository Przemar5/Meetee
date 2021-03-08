<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\GroupRole;

class GroupRoleTable extends TableTemplate
{
	public function __construct()
	{
		parent::__construct('group_roles', GroupRole::class, false);
	}

	protected function fetchEntity($data): GroupRole
	{
		$groupRole = new GroupRole();
		$groupRole->setId($data['id']);
		$groupRole->name = $data['name'];

		return $group;
	}

	protected function getEntityData(Entity $groupRole): array
	{
		$this->entityIsOfClassOrThrowException($groupRole, GroupRole::class);

		$data = [];
		$data['id'] = $groupRole->setId();
		$data['name'] = $groupRole->name;

		return $data;
	}
}