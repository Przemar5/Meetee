<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Group;

class GroupTable extends TableTemplate
{
	public function __construct()
	{
		parent::__construct('groups', Group::class, false);
	}

	protected function fetchEntity($data): Group
	{
		$group = new Group();
		$group->setId($data['id']);
		$group->name = $data['name'];
		$group->description = $data['description'];
		$group->active = $data['active'];
		$group->background = $data['background'];
		$group->setCreatedAt($data['created_at']);
		$group->setUpdatedAt($data['updated_at']);
		$group->deleted = $data['deleted'];

		return $group;
	}

	protected function getEntityData(Entity $group): array
	{
		$this->throwExceptionIfInvalidClass($group, Group::class);

		$data = [];
		$data['id'] = $group->getId();
		$data['name'] = $group->name;
		$data['description'] = $group->description;
		$data['active'] = $group->active;
		$data['background'] = $group->background;
		$data['deleted'] = $group->deleted;

		return $data;
	}
}