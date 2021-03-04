<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Relation;

class RelationTable extends TableTemplate
{
	public function __construct()
	{
		parent::__construct('relations', Relation::class, false);
	}

	protected function fetchEntity($data): Relation
	{
		$relation = new Relation();
		$relation->setId($data['id']);
		$relation->name = $data['name'];

		return $relation;
	}

	protected function getEntityData(Entity $role): array
	{
		$this->entityIsOfClassOrThrowException($role, Relation::class);

		$data = [];
		$data['id'] = $relation->getId();
		$data['name'] = $relation->name;

		return $data;
	}
}