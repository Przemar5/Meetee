<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\Libs\Database\Tables\TableTemplate;
use Meetee\Libs\Database\Tables\TagTable;
use Meetee\App\Entities\Entity;
use Meetee\App\Entities\Tag;
use Meetee\Libs\Database\Tables\Pivots\CommentTagTable;

class TagTable extends TableTemplate
{
	public function __construct()
	{
		parent::__construct('tags', Tag::class, false);
	}

	protected function fetchEntity($data): Tag
	{
		$role = new Tag();
		$role->setId($data['id']);
		$role->name = $data['name'];

		return $role;
	}

	protected function getEntityData(Entity $role): array
	{
		$this->entityIsOfClassOrThrowException($role, Tag::class);

		$data = [];
		$data['id'] = $role->getId();
		$data['name'] = $role->name;

		return $data;
	}

	public function completeMultiple(array &$tags): void
	{
		$this->insertMultipleNames($tags);

		$tags = $this->selectByNames($tags);
	}

	private function insertMultipleNames(array $tags): void
	{
		$tags = $this->prepareTagsToInsert($tags);

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->insertMultiple($tags);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	private function prepareTagsToInsert(array $tags): array
	{
		$result = [];

		foreach ($tags as $tagName) {
			$result[] = ['name' => $tagName];
		}

		return $result;
	}

	private function selectByNames(array $tags): array
	{
		$conditions = $this->prepareWhereInNamesCondition($tags);
		$table = new TagTable();

		return $table->findManyBy($conditions);
	}

	private function prepareWhereInNamesCondition(array $tags): array
	{
		$result = $this->prepareTagsToInsert($tags);
		array_unshift($result, 'OR');

		return $result;
	}
}