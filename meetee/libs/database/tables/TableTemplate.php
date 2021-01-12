<?php

namespace Meetee\Libs\Database\Tables;

use Meetee\App\Entities\Entity;
use Meetee\Libs\Database\Query_builders\QueryBuilderTemplate;
use Meetee\Libs\Database\Factories\DatabaseAbstractFactory;
use Meetee\Libs\Database\DatabaseTemplate;

abstract class TableTemplate
{
	public QueryBuilderTemplate $queryBuilder;
	protected string $name;
	protected bool $softDelete;
	protected DatabaseTemplate $database;

	public function __construct(string $name, ?bool $softDelete = false)
	{
		$this->name = $name;
		$this->database = DatabaseAbstractFactory::createDatabase();
		$this->queryBuilder = DatabaseAbstractFactory::createQueryBuilder();
		$this->softDelete = $softDelete;
	}

	public function find(int $id): ?Entity
	{
		return $this->findOneWhere(['id' => $id]);
	}

	public function findOneWhere(array $conditions): ?Entity
	{
		$data = $this->getDataForOneWhere($conditions);
		
		if (!$data)
			return null;

		$entity = $this->fetchEntity($data);

		return $entity;
	}

	public function findManyWhere(array $conditions)
	{
		$data = $this->getDataForManyWhere($conditions);
		
		if (!$data)
			return null;

		$entities = $this->fetchEntities($data);

		return $entities;
	}

	protected function getDataForOneWhere(array $conditions)
	{
		$this->prepareSelectWhere($conditions);
		$this->queryBuilder->limit(1);

		return $this->database->findOne(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	protected function getDataForManyWhere(array $conditions)
	{
		$this->prepareSelectWhere($conditions);

		return $this->database->findMany(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	protected function prepareSelectWhere(array $conditions): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select(['*']);
		$this->queryBuilder->where($conditions);
		
		if ($this->softDelete)
			$this->queryBuilder->whereNull(['deleted']);
	}

	abstract protected function fetchEntity($data): Entity;

	protected function fetchEntities($data)
	{
		return array_map(fn($d) => $this->fetchEntity($d), $data);
	}

	public function save(Entity $entity): void
	{
		if (is_null($entity->getId()))
			$this->insert($entity);
		else
			$this->update($entity);
	}

	public function insert(Entity $entity): void
	{
		$data = $this->getEntityData($entity);

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->insert($data);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);

		$this->updatePivots($entity);
	}

	abstract protected function getEntityData(Entity $entity): array;

	protected function updatePivots(Entity $entity): void
	{
		//
	}

	public function update(Entity $entity): void
	{
		$values = $this->getEntityData($entity);

		if (method_exists(get_class($entity), 'isDeleted') 
			&& !is_null($entity->isDeleted()))
			$values['deleted'] = $entity->isDeleted();

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->update($values);
		$this->queryBuilder->where(['id' => $entity->getId()]);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);

		$this->updatePivots($entity);
	}

	protected function entityIsOfClassOrThrowException(
		Entity $entity, 
		string $class
	): void
	{
		if (!$entity instanceof $class)
			throw new \Exception(sprintf(
				"Entity must be instance of class '%s'", $class));
	}

	public function lastInsertId(): int
	{
		return $this->database->lastInsertId();
	}
}