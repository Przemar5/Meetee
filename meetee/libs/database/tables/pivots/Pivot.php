<?php

namespace Meetee\Libs\Database\Tables\Pivots;

use Meetee\App\Entities\Entity;
use Meetee\Libs\Database\Query_builders\QueryBuilderTemplate;
use Meetee\Libs\Database\Factories\DatabaseAbstractFactory;
use Meetee\Libs\Database\DatabaseTemplate;

abstract class Pivot
{
	public QueryBuilderTemplate $queryBuilder;
	protected string $name;
	protected DatabaseTemplate $database;

	public function __construct(string $name)
	{
		$this->name = $name;
		$this->database = DatabaseAbstractFactory::createDatabase();
		$this->queryBuilder = DatabaseAbstractFactory::createQueryBuilder();
	}

	public function find(int $id): ?Entity
	{
		return $this->findOneBy(['id' => $id]);
	}

	public function findOneBy(array $conditions)
	{
		$result = $this->getDataForOneBy($conditions);
		
		return $result;
	}

	public function findManyBy(array $conditions)
	{
		$result = $this->getDataForManyBy($conditions);
		
		return $result;
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
	}

	protected function sendQuery(): void
	{
		$this->database->sendQuery(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	protected function getOneResult()
	{
		return $this->database->findOne(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}

	protected function getManyResults()
	{
		return $this->database->findMany(
			$this->queryBuilder->getResult(),
			$this->queryBuilder->getBindings()
		);
	}
}