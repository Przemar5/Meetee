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
	protected string $entityClass;
	protected bool $softDelete;
	protected DatabaseTemplate $database;

	public function __construct(
		string $name, 
		string $entityClass,
		?bool $softDelete = false
	)
	{
		$this->name = $name;
		$this->entityClass = $entityClass;
		$this->database = DatabaseAbstractFactory::createDatabase();
		$this->queryBuilder = DatabaseAbstractFactory::createQueryBuilder();
		$this->softDelete = $softDelete;
	}

	public function find(int $id): ?Entity
	{
		return $this->findOneBy(['id' => $id]);
	}

	public function findOneBy(array $conditions): ?Entity
	{
		$data = $this->getDataForOneBy($conditions);
		
		if (!$data)
			return null;

		$entity = $this->fetchEntity($data);

		return $entity;
	}

	public function findManyBy(array $conditions)
	{
		$data = $this->getDataForManyBy($conditions);
		
		if (!$data)
			return null;

		$entities = $this->fetchEntities($data);

		return $entities;
	}

	protected function getDataForOneBy(array $conditions)
	{
		$this->prepareSelectBy($conditions);
		$this->queryBuilder->limit(1);

		return $this->database->findOne(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	protected function getDataForManyBy(array $conditions)
	{
		$this->prepareSelectBy($conditions);

		return $this->database->findMany(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	protected function prepareSelectBy(array $conditions): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->select(['*']);
		$this->queryBuilder->where($conditions);
		
		if ($this->softDelete)
			$this->queryBuilder->whereStrings(['deleted IS NULL OR deleted = 0']);
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

	protected function throwExceptionIfInvalidClass(Entity $entity): void
	{
		if (get_class($entity) !== $this->entityClass)
			throw new \Exception(sprintf(
				"Entity must be of class '%s', '%s' given.",
				$this->entityClass, get_class($entity)));
	}

	public function insert(Entity $entity): void
	{
		$this->throwExceptionIfInvalidClass($entity);

		$data = $this->getEntityData($entity);

		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->insert($data);

		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
		$entity->setId($this->database->lastInsertId());

		$this->updatePivots($entity);
	}

	abstract protected function getEntityData(Entity $entity): array;

	protected function updatePivots(Entity $entity): void
	{
		//
	}

	public function update(Entity $entity): void
	{
		$this->throwExceptionIfInvalidClass($entity);

		$values = $this->getEntityData($entity);

		// if (method_exists(get_class($entity), 'isDeleted') 
		// 	&& !is_null($entity->isDeleted()))
		// 	$values['deleted'] = $entity->isDeleted();

		if ($this->softDelete)
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

	public function lastInsertId(): int
	{
		return $this->database->lastInsertId();
	}

	public function delete(int $id): void
	{
		$this->deleteBy(['id' => $id]);
	}

	public function deleteBy(array $conditions): void
	{
		if ($this->softDelete)
			$this->softDeleteBy($conditions);
		else
			$this->hardDeleteBy($conditions);
	}

	public function hardDeleteBy(array $conditions): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->delete();
		$this->queryBuilder->where($conditions);
		
		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	public function softDeleteBy(array $conditions): void
	{
		$this->queryBuilder->reset();
		$this->queryBuilder->in($this->name);
		$this->queryBuilder->update(['deleted' => true]);
		$this->queryBuilder->where($conditions);
		
		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	public function pop(int $id): ?Entity
	{
		$entity = $this->find($id);
		
		if (!$entity)
			return null;

		$this->delete($id);

		return $entity;
	}

	public function popBy(array $conditions): ?Entity
	{
		$entity = $this->findOneBy($conditions);

		if (!$entity)
			return null;

		$this->delete($entity->getId());

		return $entity;
	}

	public function complete(
		Entity &$entity, 
		?array $attrs = ['id']
	): void
	{
		$this->throwExceptionIfInvalidClass($entity);

		$taked = $this->find($entity->getId());

		if (!$taken)
			return;

		foreach ($attrs as $attr) {
			if (method_exists(get_class($entity), 'set'.$attr))
				$entity->{'set'.$attr}($attr);
			
			$entity->{$attr} = $taken->{$attr};
		}
	}

	public function saveComplete(
		Entity $entity
	): ?Entity
	{
		$this->throwExceptionIfInvalidClass($entity);
		$this->save($entity);

		$taken = $this->find($entity->getId());

		if (!$taken)
			return null;

		return $taken;
	}

	public function popComplete(
		Entity &$entity, 
		?array $attrs = ['id']
	): void
	{
		$this->complete($entity, $attrs);

		$id = (method_exists($entity, 'getId')) ? $entity->getId() : $entity->id;
		
		$this->delete($id);
	}
}